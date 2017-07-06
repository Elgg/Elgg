<?php

use Elgg\Includer;

/**
 * Stores site-side plugin settings as private data.
 *
 * This class is currently a stub, allowing a plugin to
 * save settings in an object's private settings for each site.
 *
 * @package    Elgg.Core
 * @subpackage Plugins.Settings
 */
class ElggPlugin extends \ElggObject {
	private $package;
	private $manifest;

	/**
	 * Data from static config file. null if not yet read.
	 *
	 * @var array|null
	 */
	private $static_config;

	private $path;
	private $errorMsg = '';

	/**
	 * Set subtype to 'plugin'
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "plugin";

		// plugins must be public.
		$this->access_id = ACCESS_PUBLIC;
	}

	/**
	 * Creates a new plugin from path
	 *
	 * @note Internal: also supports database objects
	 *
	 * @warning Unlike other \ElggEntity objects, you cannot null instantiate
	 *          \ElggPlugin. You must provide the path to the plugin directory.
	 *
	 * @param string $path The absolute path of the plugin
	 *
	 * @throws PluginException
	 */
	public function __construct($path) {
		if (!$path) {
			throw new \PluginException("ElggPlugin cannot be null instantiated. You must pass a full path.");
		}

		if (is_object($path)) {
			// database object
			parent::__construct($path);
			$this->path = _elgg_services()->config->getPluginsPath() . $this->getID();
			_elgg_cache_plugin_by_id($this);
			return;
		}

		if (is_numeric($path)) {
			// guid
			// @todo plugins with directory names of '12345'
			throw new \InvalidArgumentException('$path cannot be a GUID');
		}

		$this->initializeAttributes();

		// path checking is done in the package
		$path = sanitise_filepath($path);
		$this->path = $path;
		$path_parts = explode('/', rtrim($path, '/'));
		$plugin_id = array_pop($path_parts);
		$this->title = $plugin_id;

		// check if we're loading an existing plugin
		$existing_plugin = elgg_get_plugin_from_id($plugin_id);

		if ($existing_plugin) {
			$this->load($existing_plugin->guid);
		}

		_elgg_cache_plugin_by_id($this);
	}

	/**
	 * Save the plugin object.  Make sure required values exist.
	 *
	 * @see \ElggObject::save()
	 * @return bool
	 */
	public function save() {
		// own by the current site so users can be deleted without affecting plugins
		$site = elgg_get_site_entity();
		$this->attributes['owner_guid'] = $site->guid;
		$this->attributes['container_guid'] = $site->guid;
		
		if (parent::save()) {
			// make sure we have a priority
			$priority = $this->getPriority();
			if ($priority === false || $priority === null) {
				return $this->setPriority('last');
			}
		} else {
			return false;
		}
	}


	// Plugin ID and path

	/**
	 * Returns the ID (dir name) of this plugin
	 *
	 * @return string
	 */
	public function getID() {
		return $this->title;
	}

	/**
	 * Returns the manifest's name if available, otherwise the ID.
	 *
	 * @return string
	 * @since 1.8.1
	 */
	public function getFriendlyName() {
		$manifest = $this->getManifest();
		if ($manifest) {
			return $manifest->getName();
		}

		return $this->getID();
	}

	/**
	 * Returns the plugin's full path with trailing slash.
	 *
	 * @return string
	 */
	public function getPath() {
		return sanitise_filepath($this->path);
	}

	/**
	 * Get a value from the plugins's static config file.
	 *
	 * @note If the system cache is on, Elgg APIs should not call this on every request.
	 *
	 * @param string $key     Config key
	 * @param mixed  $default Value returned if missing
	 *
	 * @return mixed
	 * @throws PluginException
	 * @access private
	 * @internal For Elgg internal use only
	 */
	public function getStaticConfig($key, $default = null) {
		if ($this->static_config === null) {
			$this->static_config = [];

			if ($this->canReadFile(ElggPluginPackage::STATIC_CONFIG_FILENAME)) {
				$this->static_config = $this->includeFile(ElggPluginPackage::STATIC_CONFIG_FILENAME);
			}
		}

		if (array_key_exists($key, $this->static_config)) {
			return $this->static_config[$key];
		} else {
			return $default;
		}
	}

	/**
	 * Sets the location of this plugin.
	 *
	 * @param string $id The path to the plugin's dir.
	 * @return bool
	 */
	public function setID($id) {
		return $this->attributes['title'] = $id;
	}

	/**
	 * Returns an array of available markdown files for this plugin
	 *
	 * @return array
	 */
	public function getAvailableTextFiles() {
		$filenames = $this->getPackage()->getTextFilenames();

		$files = [];
		foreach ($filenames as $filename) {
			if ($this->canReadFile($filename)) {
				$files[$filename] = "$this->path/$filename";
			}
		}

		return $files;
	}

	// Load Priority

	/**
	 * Gets the plugin's load priority.
	 *
	 * @return int
	 */
	public function getPriority() {
		$name = _elgg_namespace_plugin_private_setting('internal', 'priority');
		return $this->$name;
	}

	/**
	 * Sets the priority of the plugin
	 *
	 * @param mixed $priority The priority to set. One of +1, -1, first, last, or a number.
	 *                        If given a number, this will displace all plugins at that number
	 *                        and set their priorities +1
	 * @return bool
	 */
	public function setPriority($priority) {
		if (!$this->guid) {
			return false;
		}

		$db = $this->getDatabase();
		$name = _elgg_namespace_plugin_private_setting('internal', 'priority');
		// if no priority assume a priority of 1
		$old_priority = (int) $this->getPriority();
		$old_priority = (!$old_priority) ? 1 : $old_priority;
		$max_priority = _elgg_get_max_plugin_priority();

		// can't use switch here because it's not strict and
		// php evaluates +1 == 1
		if ($priority === '+1') {
			$priority = $old_priority + 1;
		} elseif ($priority === '-1') {
			$priority = $old_priority - 1;
		} elseif ($priority === 'first') {
			$priority = 1;
		} elseif ($priority === 'last') {
			$priority = $max_priority;
		}

		// should be a number by now
		if ($priority > 0) {
			if (!is_numeric($priority)) {
				return false;
			}

			// there's nothing above the max.
			if ($priority > $max_priority) {
				$priority = $max_priority;
			}

			// there's nothing below 1.
			if ($priority < 1) {
				$priority = 1;
			}

			if ($priority > $old_priority) {
				$op = '-';
				$where = "CAST(value as unsigned) BETWEEN $old_priority AND $priority";
			} else {
				$op = '+';
				$where = "CAST(value as unsigned) BETWEEN $priority AND $old_priority";
			}

			// displace the ones affected by this change
			$q = "UPDATE {$db->prefix}private_settings
				SET value = CAST(value as unsigned) $op 1
				WHERE entity_guid != $this->guid
				AND name = '$name'
				AND $where";

			if (!$db->updateData($q)) {
				return false;
			}

			// set this priority
			if ($this->setPrivateSetting($name, $priority)) {
				return true;
			} else {
				return false;
			}
		}

		return false;
	}


	// Plugin settings

	/**
	 * Returns a plugin setting
	 *
	 * @param string $name    The setting name
	 * @param mixed  $default The default value to return if none is set
	 * @return mixed
	 */
	public function getSetting($name, $default = null) {
		$values = $this->getAllSettings();

		return elgg_extract($name, $values, $default);
	}

	/**
	 * Returns an array of all settings saved for this plugin.
	 *
	 * @note Unlike user settings, plugin settings are not namespaced.
	 *
	 * @return array An array of key/value pairs.
	 */
	public function getAllSettings() {
		
		$defaults = $this->getStaticConfig('settings', []);
		$values = _elgg_services()->pluginSettingsCache->getAll($this->guid);
		
		if ($values !== null) {
			return array_merge($defaults, $values);
		}

		if (!$this->guid) {
			return false;
		}

		$db_prefix = _elgg_services()->config->get('dbprefix');
		// need to remove all namespaced private settings.
		$us_prefix = _elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());
		$is_prefix = _elgg_namespace_plugin_private_setting('internal', '', $this->getID());

		// Get private settings for user
		$q = "SELECT * FROM {$db_prefix}private_settings
			WHERE entity_guid = $this->guid
			AND name NOT LIKE '$us_prefix%'
			AND name NOT LIKE '$is_prefix%'";

		$private_settings = $this->getDatabase()->getData($q);

		$return = [];

		if ($private_settings) {
			foreach ($private_settings as $setting) {
				$return[$setting->name] = $setting->value;
			}
		}

		return array_merge($defaults, $return);
	}

	/**
	 * Set a plugin setting for the plugin
	 *
	 * @todo This will only work once the plugin has a GUID.
	 *
	 * @param string $name  The name to set
	 * @param string $value The value to set
	 *
	 * @return bool
	 */
	public function setSetting($name, $value) {
		if (!$this->guid) {
			return false;
		}
		
		// Hook to validate setting
		$value = elgg_trigger_plugin_hook('setting', 'plugin', [
			'plugin_id' => $this->getID(),
			'plugin' => $this,
			'name' => $name,
			'value' => $value,
		], $value);
		
		return $this->setPrivateSetting($name, $value);
	}

	/**
	 * Removes a plugin setting name and value.
	 *
	 * @param string $name The setting name to remove
	 *
	 * @return bool
	 */
	public function unsetSetting($name) {
		return remove_private_setting($this->guid, $name);
	}

	/**
	 * Removes all settings for this plugin.
	 *
	 * @todo Should be a better way to do this without dropping to raw SQL.
	 * @todo If we could namespace the plugin settings this would be cleaner.
	 * @todo this shouldn't work because ps_prefix will be empty string
	 * @return bool
	 */
	public function unsetAllSettings() {
		_elgg_services()->pluginSettingsCache->clear($this->guid);
		_elgg_services()->boot->invalidateCache();

		$db = $this->getDatabase();
		$us_prefix = _elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());
		$is_prefix = _elgg_namespace_plugin_private_setting('internal', '', $this->getID());

		$q = "DELETE FROM {$db->prefix}private_settings
			WHERE entity_guid = $this->guid
			AND name NOT LIKE '$us_prefix%'
			AND name NOT LIKE '$is_prefix%'";

		return $db->deleteData($q);
	}


	// User settings

	/**
	 * Returns a user's setting for this plugin
	 *
	 * @param string $name      The setting name
	 * @param int    $user_guid The user GUID
	 * @param mixed  $default   The default value to return if none is set
	 *
	 * @return mixed The setting string value, the default value or false if there is no user
	 */
	public function getUserSetting($name, $user_guid = 0, $default = null) {
		$values = $this->getAllUserSettings($user_guid);
		if ($values === false) {
			return false;
		}
		
		return elgg_extract($name, $values, $default);
	}

	/**
	 * Returns an array of all user settings saved for this plugin for the user.
	 *
	 * @note Plugin settings are saved with a prefix. This removes that prefix.
	 *
	 * @param int $user_guid The user GUID. Defaults to logged in.
	 * @return array An array of key/value pairs.
	 */
	public function getAllUserSettings($user_guid = 0) {
		$user_guid = (int) $user_guid;

		if ($user_guid) {
			$user = get_entity($user_guid);
		} else {
			$user = _elgg_services()->session->getLoggedInUser();
		}

		if (!($user instanceof \ElggUser)) {
			return false;
		}

		$defaults = $this->getStaticConfig('user_settings', []);
		
		$db_prefix = _elgg_services()->config->get('dbprefix');
		// send an empty name so we just get the first part of the namespace
		$ps_prefix = _elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());
		$ps_prefix_len = strlen($ps_prefix);

		// Get private settings for user
		$q = "SELECT * FROM {$db_prefix}private_settings
			WHERE entity_guid = {$user->guid}
			AND name LIKE '$ps_prefix%'";

		$private_settings = $this->getDatabase()->getData($q);

		$return = [];

		if ($private_settings) {
			foreach ($private_settings as $setting) {
				$name = substr($setting->name, $ps_prefix_len);
				$value = $setting->value;

				$return[$name] = $value;
			}
		}

		return array_merge($defaults, $return);
	}

	/**
	 * Sets a user setting for a plugin
	 *
	 * @param string $name      The setting name
	 * @param string $value     The setting value
	 * @param int    $user_guid The user GUID
	 *
	 * @return mixed The new setting ID or false
	 */
	public function setUserSetting($name, $value, $user_guid = 0) {
		$user_guid = (int) $user_guid;

		if ($user_guid) {
			$user = get_entity($user_guid);
		} else {
			$user = _elgg_services()->session->getLoggedInUser();
		}

		if (!($user instanceof \ElggUser)) {
			return false;
		}

		// Hook to validate setting
		// note: this doesn't pass the namespaced name
		$value = _elgg_services()->hooks->trigger('usersetting', 'plugin', [
			'user' => $user,
			'plugin' => $this,
			'plugin_id' => $this->getID(),
			'name' => $name,
			'value' => $value
		], $value);

		// set the namespaced name.
		$name = _elgg_namespace_plugin_private_setting('user_setting', $name, $this->getID());

		return set_private_setting($user->guid, $name, $value);
	}

	/**
	 * Removes a user setting name and value.
	 *
	 * @param string $name      The user setting name
	 * @param int    $user_guid The user GUID
	 * @return bool
	 */
	public function unsetUserSetting($name, $user_guid = 0) {
		$user_guid = (int) $user_guid;

		if ($user_guid) {
			$user = get_entity($user_guid);
		} else {
			$user = _elgg_services()->session->getLoggedInUser();
		}

		if (!($user instanceof \ElggUser)) {
			return false;
		}

		// set the namespaced name.
		$name = _elgg_namespace_plugin_private_setting('user_setting', $name, $this->getID());

		return remove_private_setting($user->guid, $name);
	}

	/**
	 * Removes all User Settings for this plugin for a particular user
	 *
	 * Use {@link removeAllUsersSettings()} to remove all user
	 * settings for all users.  (Note the plural 'Users'.)
	 *
	 * @warning 0 does not equal logged in user for this method!
	 * @todo fix that
	 *
	 * @param int $user_guid The user GUID to remove user settings.
	 * @return bool
	 */
	public function unsetAllUserSettings($user_guid) {
		$db = $this->getDatabase();
		$ps_prefix = _elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());

		$q = "DELETE FROM {$db->prefix}private_settings
			WHERE entity_guid = $user_guid
			AND name LIKE '$ps_prefix%'";

		return $db->deleteData($q);
	}

	/**
	 * Removes this plugin's user settings for all users.
	 *
	 * Use {@link removeAllUserSettings()} if you just want to remove
	 * settings for a single user.
	 *
	 * @return bool
	 */
	public function unsetAllUsersSettings() {
		$db = $this->getDatabase();
		$ps_prefix = _elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());

		$q = "DELETE FROM {$db->prefix}private_settings
			WHERE name LIKE '$ps_prefix%'";

		return $db->deleteData($q);
	}


	// validation

	/**
	 * Returns if the plugin is complete, meaning has all required files
	 * and Elgg can read them and they make sense.
	 *
	 * @todo bad name? This could be confused with isValid() from \ElggPluginPackage.
	 *
	 * @return bool
	 */
	public function isValid() {
		if (!$this->getID()) {
			$this->errorMsg = _elgg_services()->translator->translate('ElggPlugin:MissingID', [$this->guid]);
			return false;
		}

		if (!$this->getPackage() instanceof \ElggPluginPackage) {
			$this->errorMsg = _elgg_services()->translator->translate('ElggPlugin:NoPluginPackagePackage', [$this->getID(), $this->guid]);
			return false;
		}

		if (!$this->getPackage()->isValid()) {
			$this->errorMsg = $this->getPackage()->getError();
			return false;
		}

		return true;
	}

	/**
	 * Is this plugin active?
	 *
	 * @return bool
	 */
	public function isActive() {
		if (!$this->guid) {
			return false;
		}

		$site = elgg_get_site_entity();

		if (!($site instanceof \ElggSite)) {
			return false;
		}

		return check_entity_relationship($this->guid, 'active_plugin', $site->guid);
	}

	/**
	 * Checks if this plugin can be activated on the current
	 * Elgg installation.
	 *
	 * @return bool
	 */
	public function canActivate() {
		if ($this->isActive()) {
			return false;
		}

		if ($this->getPackage()) {
			$result = $this->getPackage()->isValid() && $this->getPackage()->checkDependencies();
			if (!$result) {
				$this->errorMsg = $this->getPackage()->getError();
			}

			return $result;
		}

		return false;
	}


	// activating and deactivating

	/**
	 * Actives the plugin for the current site.
	 *
	 * @return bool
	 */
	public function activate() {
		if ($this->isActive()) {
			return false;
		}

		if (!$this->canActivate()) {
			return false;
		}

		// Check this before setting status because the file could potentially throw
		if (!$this->isStaticConfigValid()) {
			return false;
		}

		if (!$this->setStatus(true)) {
			return false;
		}

		// perform tasks and emit events
		// emit an event. returning false will make this not be activated.
		// we need to do this after it's been fully activated
		// or the deactivate will be confused.
		$params = [
			'plugin_id' => $this->getID(),
			'plugin_entity' => $this,
		];

		$return = _elgg_services()->events->trigger('activate', 'plugin', $params);

		// if there are any on_enable functions, start the plugin now and run them
		// Note: this will not run re-run the init hooks!
		if ($return) {
			$this->activateEntities();
			
			if ($this->canReadFile('activate.php')) {
				$flags = ELGG_PLUGIN_INCLUDE_START | ELGG_PLUGIN_REGISTER_CLASSES |
						ELGG_PLUGIN_REGISTER_LANGUAGES | ELGG_PLUGIN_REGISTER_VIEWS | ELGG_PLUGIN_REGISTER_WIDGETS | ELGG_PLUGIN_REGISTER_ACTIONS;

				$this->start($flags);

				$return = $this->includeFile('activate.php');
			}
		}

		if ($return === false) {
			$this->deactivate();
		}

		return $return;
	}

	/**
	 * Checks if this plugin can be deactivated on the current
	 * Elgg installation. Validates that this plugin has no
	 * active dependants.
	 *
	 * @return bool
	 */
	public function canDeactivate() {
		if (!$this->isActive()) {
			return false;
		}

		$dependents = [];

		$active_plugins = elgg_get_plugins();

		foreach ($active_plugins as $plugin) {
			$manifest = $plugin->getManifest();
			if (!$manifest) {
				return true;
			}
			$requires = $manifest->getRequires();

			foreach ($requires as $required) {
				if ($required['type'] == 'plugin' && $required['name'] == $this->getID()) {
					// there are active dependents
					$dependents[$manifest->getPluginID()] = $plugin;
				}
			}
		}

		if (!empty($dependents)) {
			$list = array_map(function(\ElggPlugin $plugin) {
				$css_id = preg_replace('/[^a-z0-9-]/i', '-', $plugin->getManifest()->getID());
				return elgg_view('output/url', [
					'text' => $plugin->getManifest()->getName(),
					'href' => "#$css_id",
				]);
			}, $dependents);
			$name = $this->getManifest()->getName();
			$list = implode(', ', $list);
			$this->errorMsg = elgg_echo('ElggPlugin:Dependencies:ActiveDependent', [$name, $list]);
			return false;
		}

		return true;
	}

	/**
	 * Deactivates the plugin.
	 *
	 * @return bool
	 */
	public function deactivate() {
		if (!$this->isActive()) {
			return false;
		}

		if (!$this->canDeactivate()) {
			return false;
		}
		
		// emit an event. returning false will cause this to not be deactivated.
		$params = [
			'plugin_id' => $this->getID(),
			'plugin_entity' => $this,
		];

		$return = _elgg_services()->events->trigger('deactivate', 'plugin', $params);

		// run any deactivate code
		if ($return) {
			if ($this->canReadFile('deactivate.php')) {
				$return = $this->includeFile('deactivate.php');
			}
			
			$this->deactivateEntities();
		}

		if ($return === false) {
			return false;
		} else {
			return $this->setStatus(false);
		}
	}

	/**
	 * Start the plugin.
	 *
	 * @param int $flags Start flags for the plugin. See the constants in lib/plugins.php for details.
	 * @return true
	 * @throws PluginException
	 */
	public function start($flags) {

		// include classes
		if ($flags & ELGG_PLUGIN_REGISTER_CLASSES) {
			$this->registerClasses();
			
			$autoload_file = 'vendor/autoload.php';
			if ($this->canReadFile($autoload_file)) {
				require_once "{$this->path}/{$autoload_file}";
			}
		}

		// include languages
		if ($flags & ELGG_PLUGIN_REGISTER_LANGUAGES) {
			// should be loaded before the first function that touches the static config (elgg-plugin.php)
			// so translations can be used... for example in registering widgets
			$this->registerLanguages();
		}
		
		// include start file if it exists
		if ($flags & ELGG_PLUGIN_INCLUDE_START) {
			if ($this->canReadFile('start.php')) {
				$this->includeFile('start.php');
			}
			
			$this->registerEntities();
		}
		
		// include views
		if ($flags & ELGG_PLUGIN_REGISTER_VIEWS) {
			$this->registerViews();
		}

		// include actions
		if ($flags & ELGG_PLUGIN_REGISTER_ACTIONS) {
			$this->registerActions();
		}

		// include widgets
		if ($flags & ELGG_PLUGIN_REGISTER_WIDGETS) {
			// should load after views because those are used during registration
			$this->registerWidgets();
		}

		return true;
	}

	/**
	 * Includes one of the plugins files
	 *
	 * @param string $filename The name of the file
	 *
	 * @throws PluginException
	 * @return mixed The return value of the included file (or 1 if there is none)
	 */
	protected function includeFile($filename) {
		$filepath = "$this->path/$filename";

		if (!$this->canReadFile($filename)) {
			$msg = _elgg_services()->translator->translate('ElggPlugin:Exception:CannotIncludeFile',
							[$filename, $this->getID(), $this->guid, $this->path]);
			throw new \PluginException($msg);
		}

		try {
			$ret = include $filepath;
		} catch (Exception $e) {
			$msg = _elgg_services()->translator->translate('ElggPlugin:Exception:IncludeFileThrew',
				[$filename, $this->getID(), $this->guid, $this->path]);
			throw new \PluginException($msg, 0, $e);
		}

		return $ret;
	}

	/**
	 * Checks whether a plugin file with the given name exists
	 *
	 * @param string $filename The name of the file
	 * @return bool
	 */
	protected function canReadFile($filename) {
		$path = "{$this->path}/$filename";
		return is_file($path) && is_readable($path);
	}

	/**
	 * If a static config file is present, is it a serializable array?
	 *
	 * @return bool
	 * @throws PluginException
	 */
	private function isStaticConfigValid() {
		if (!$this->canReadFile(ElggPluginPackage::STATIC_CONFIG_FILENAME)) {
			return true;
		}

		ob_start();
		$value = $this->includeFile(ElggPluginPackage::STATIC_CONFIG_FILENAME);
		if (ob_get_clean() !== '') {
			$this->errorMsg = _elgg_services()->translator->translate('ElggPlugin:activate:ConfigSentOutput');
			return false;
		}

		// make sure can serialize
		$value = @unserialize(serialize($value));
		if (!is_array($value)) {
			$this->errorMsg = _elgg_services()->translator->translate('ElggPlugin:activate:BadConfigFormat');
			return false;
		}

		return true;
	}

	/**
	 * Registers the plugin's views
	 *
	 * @throws PluginException
	 * @return void
	 */
	protected function registerViews() {
		$views = _elgg_services()->views;

		// Declared views first
		$file = "{$this->path}/views.php";
		if (is_file($file)) {
			$spec = Includer::includeFile($file);
			if (is_array($spec)) {
				$views->mergeViewsSpec($spec);
			}
		}

		$spec = $this->getStaticConfig('views');
		if ($spec) {
			$views->mergeViewsSpec($spec);
		}

		// Allow /views directory files to override
		if (!$views->registerPluginViews($this->path, $failed_dir)) {
			$key = 'ElggPlugin:Exception:CannotRegisterViews';
			$args = [$this->getID(), $this->guid, $failed_dir];
			$msg = _elgg_services()->translator->translate($key, $args);
			throw new \PluginException($msg);
		}
	}

	/**
	 * Registers the plugin's entities
	 *
	 * @return void
	 */
	protected function registerEntities() {

		$spec = (array) $this->getStaticConfig('entities', []);
		if (empty($spec)) {
			return;
		}
		
		foreach ($spec as $entity) {
			if (isset($entity['type'], $entity['subtype'], $entity['searchable']) && $entity['searchable']) {
				elgg_register_entity_type($entity['type'], $entity['subtype']);
			}
		}
	}

	/**
	 * Registers the plugin's actions provided in the plugin config file
	 *
	 * @throws PluginException
	 * @return void
	 */
	protected function registerActions() {
		$actions = _elgg_services()->actions;

		$spec = (array) $this->getStaticConfig('actions', []);
		
		foreach ($spec as $action => $action_spec) {
			if (!is_array($action_spec)) {
				continue;
			}
			
			$options = [
				'access' => 'logged_in',
				'filename' => '', // assuming core action is registered
			];
			
			$options = array_merge($options, $action_spec);
			
			$filename = "{$this->getPath()}actions/{$action}.php";
			if (file_exists($filename)) {
				$options['filename'] = $filename;
			}
			
			$actions->register($action, $options['filename'], $options['access']);
		}
	}

	/**
	 * Registers the plugin's widgets provided in the plugin config file
	 *
	 * @throws PluginException
	 * @return void
	 */
	protected function registerWidgets() {
		$widgets = _elgg_services()->widgets;
		
		$spec = (array) $this->getStaticConfig('widgets', []);
		foreach ($spec as $widget_id => $widget_definition) {
			if (!is_array($widget_definition)) {
				continue;
			}
			if (!isset($widget_definition['id'])) {
				$widget_definition['id'] = $widget_id;
			}
			
			$definition = \Elgg\WidgetDefinition::factory($widget_definition);
			
			$widgets->registerType($definition);
		}
	}

	/**
	 * Registers the plugin's languages
	 *
	 * @throws PluginException
	 * @return true
	 */
	protected function registerLanguages() {
		return _elgg_services()->translator->registerPluginTranslations($this->path);
	}

	/**
	 * Registers the plugin's classes
	 *
	 * @throws PluginException
	 * @return true
	 */
	protected function registerClasses() {
		$classes_path = "$this->path/classes";

		if (is_dir($classes_path)) {
			_elgg_services()->autoloadManager->addClasses($classes_path);
		}

		return true;
	}

	/**
	 * Activates the plugin's entities
	 *
	 * @return void
	 */
	protected function activateEntities() {
		$spec = (array) $this->getStaticConfig('entities', []);
		if (empty($spec)) {
			return;
		}
		
		foreach ($spec as $entity) {
			if (isset($entity['type'], $entity['subtype'], $entity['class'])) {
				if (get_subtype_id($entity['type'], $entity['subtype'])) {
					update_subtype($entity['type'], $entity['subtype'], $entity['class']);
				} else {
					add_subtype($entity['type'], $entity['subtype'], $entity['class']);
				}
			}
		}
	}

	/**
	 * Deactivates the plugin's entities
	 *
	 * @return void
	 */
	protected function deactivateEntities() {
		$spec = (array) $this->getStaticConfig('entities', []);
		if (empty($spec)) {
			return;
		}
		
		foreach ($spec as $entity) {
			if (isset($entity['type'], $entity['subtype'], $entity['class'])) {
				update_subtype($entity['type'], $entity['subtype']);
			}
		}
	}

	/**
	 * Get an attribute or private setting value
	 *
	 * @param string $name Name of the attribute or private setting
	 * @return mixed
	 */
	public function __get($name) {
		// See if its in our base attribute
		if (array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		}

		$result = $this->getPrivateSetting($name);
		if ($result !== null) {
			return $result;
		}
		$defaults = $this->getStaticConfig('settings', []);
		return elgg_extract($name, $defaults, $result);
	}

	/**
	 * Set a value as private setting or attribute.
	 *
	 * Attributes include title and description.
	 *
	 * @param string $name  Name of the attribute or private_setting
	 * @param mixed  $value Value to be set
	 * @return void
	 */
	public function __set($name, $value) {
		if (array_key_exists($name, $this->attributes)) {
			// Check that we're not trying to change the guid!
			if ((array_key_exists('guid', $this->attributes)) && ($name == 'guid')) {
				return;
			}

			$this->attributes[$name] = $value;
		} else {
			// to make sure we trigger the correct hooks
			$this->setSetting($name, $value);
		}
	}

	/**
	 * Sets the plugin to active or inactive.
	 *
	 * @param bool $active Set to active or inactive
	 *
	 * @return bool
	 */
	private function setStatus($active) {
		if (!$this->guid) {
			return false;
		}

		$site = elgg_get_site_entity();
		if ($active) {
			$result = add_entity_relationship($this->guid, 'active_plugin', $site->guid);
		} else {
			$result = remove_entity_relationship($this->guid, 'active_plugin', $site->guid);
		}

		_elgg_invalidate_plugins_provides_cache();
		_elgg_services()->boot->invalidateCache();

		return $result;
	}

	/**
	 * Returns the last error message registered.
	 *
	 * @return string|null
	 */
	public function getError() {
		return $this->errorMsg;
	}

	/**
	 * Returns this plugin's \ElggPluginManifest object
	 *
	 * @return \ElggPluginManifest|null
	 */
	public function getManifest() {
		if ($this->manifest instanceof \ElggPluginManifest) {
			return $this->manifest;
		}

		try {
			$package = $this->getPackage();
			if (!$package) {
				throw new \Exception('Package cannot be loaded');
			}
			$this->manifest = $package->getManifest();
		} catch (Exception $e) {
			_elgg_services()->logger->warn("Failed to load manifest for plugin $this->guid. " . $e->getMessage());
			$this->errorMsg = $e->getmessage();
		}

		return $this->manifest;
	}

	/**
	 * Returns this plugin's \ElggPluginPackage object
	 *
	 * @return \ElggPluginPackage|null
	 */
	public function getPackage() {
		if ($this->package instanceof \ElggPluginPackage) {
			return $this->package;
		}

		try {
			$this->package = new \ElggPluginPackage($this->path, false);
		} catch (Exception $e) {
			_elgg_services()->logger->warn("Failed to load package for $this->guid. " . $e->getMessage());
			$this->errorMsg = $e->getmessage();
		}

		return $this->package;
	}
}
