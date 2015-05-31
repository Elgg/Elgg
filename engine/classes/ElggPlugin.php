<?php
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
		} else if (is_numeric($path)) {
			// guid
			// @todo plugins with directory names of '12345'
			elgg_deprecated_notice("Use elgg_get_plugin_from_id() to load a plugin.", 1.9);
			parent::__construct($path);
			$this->path = _elgg_services()->config->getPluginsPath() . $this->getID();
		} else {
			$this->initializeAttributes();
			
			$mod_dir = _elgg_services()->config->getPluginsPath();

			// not a full path, so assume a directory name and use the default path
			if (strpos($path, $mod_dir) !== 0) {
				elgg_deprecated_notice("You should pass a full path to ElggPlugin.", 1.9);
				$path = $mod_dir . $path;
			}

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
		$site = _elgg_services()->configTable->get('site');
		$this->attributes['site_guid'] = $site->guid;
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

		$files = array();
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
	 * @param mixed $priority  The priority to set. One of +1, -1, first, last, or a number.
	 *                         If given a number, this will displace all plugins at that number
	 *                         and set their priorities +1
	 * @param mixed $site_guid Optional site GUID.
	 * @return bool
	 */
	public function setPriority($priority, $site_guid = null) {
		if (!$this->guid) {
			return false;
		}

		$db_prefix = _elgg_services()->configTable->get('dbprefix');
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
			$q = "UPDATE {$db_prefix}private_settings
				SET value = CAST(value as unsigned) $op 1
				WHERE entity_guid != $this->guid
				AND name = '$name'
				AND $where";

			if (!$this->getDatabase()->updateData($q)) {
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
		$val = $this->$name;
		return $val !== null ? $val : $default;
	}

	/**
	 * Returns an array of all settings saved for this plugin.
	 *
	 * @note Unlike user settings, plugin settings are not namespaced.
	 *
	 * @return array An array of key/value pairs.
	 */
	public function getAllSettings() {
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

		$return = array();

		if ($private_settings) {
			foreach ($private_settings as $setting) {
				$return[$setting->name] = $setting->value;
			}
		}

		return $return;
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
		$value = elgg_trigger_plugin_hook('setting', 'plugin', array(
			'plugin_id' => $this->getID(),
			'plugin' => $this,
			'name' => $name,
			'value' => $value,
		), $value);
		
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
		$db_prefix = _elgg_services()->configTable->get('dbprefix');
		$us_prefix = _elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());
		$is_prefix = _elgg_namespace_plugin_private_setting('internal', '', $this->getID());

		$q = "DELETE FROM {$db_prefix}private_settings
			WHERE entity_guid = $this->guid
			AND name NOT LIKE '$us_prefix%'
			AND name NOT LIKE '$is_prefix%'";

		return $this->getDatabase()->deleteData($q);
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
		$user_guid = (int)$user_guid;

		if ($user_guid) {
			$user = get_entity($user_guid);
		} else {
			$user = _elgg_services()->session->getLoggedInUser();
		}

		if (!($user instanceof \ElggUser)) {
			return false;
		}

		$name = _elgg_namespace_plugin_private_setting('user_setting', $name, $this->getID());
		
		$val = get_private_setting($user->guid, $name);
		return $val !== null ? $val : $default;
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
		$user_guid = (int)$user_guid;

		if ($user_guid) {
			$user = get_entity($user_guid);
		} else {
			$user = _elgg_services()->session->getLoggedInUser();
		}

		if (!($user instanceof \ElggUser)) {
			return false;
		}

		$db_prefix = _elgg_services()->config->get('dbprefix');
		// send an empty name so we just get the first part of the namespace
		$ps_prefix = _elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());
		$ps_prefix_len = strlen($ps_prefix);

		// Get private settings for user
		$q = "SELECT * FROM {$db_prefix}private_settings
			WHERE entity_guid = {$user->guid}
			AND name LIKE '$ps_prefix%'";

		$private_settings = $this->getDatabase()->getData($q);

		$return = array();

		if ($private_settings) {
			foreach ($private_settings as $setting) {
				$name = substr($setting->name, $ps_prefix_len);
				$value = $setting->value;

				$return[$name] = $value;
			}
		}

		return $return;
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
		$user_guid = (int)$user_guid;

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
		$value = _elgg_services()->hooks->trigger('usersetting', 'plugin', array(
			'user' => $user,
			'plugin' => $this,
			'plugin_id' => $this->getID(),
			'name' => $name,
			'value' => $value
		), $value);

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
		$user_guid = (int)$user_guid;

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
		$db_prefix = _elgg_services()->configTable->get('dbprefix');
		$ps_prefix = _elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());

		$q = "DELETE FROM {$db_prefix}private_settings
			WHERE entity_guid = $user_guid
			AND name LIKE '$ps_prefix%'";

		return $this->getDatabase()->deleteData($q);
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
		$db_prefix = _elgg_services()->configTable->get('dbprefix');
		$ps_prefix = _elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());

		$q = "DELETE FROM {$db_prefix}private_settings
			WHERE name LIKE '$ps_prefix%'";

		return $this->getDatabase()->deleteData($q);
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
			$this->errorMsg = _elgg_services()->translator->translate('ElggPlugin:MissingID', array($this->guid));
			return false;
		}

		if (!$this->getPackage() instanceof \ElggPluginPackage) {
			$this->errorMsg = _elgg_services()->translator->translate('ElggPlugin:NoPluginPackagePackage', array($this->getID(), $this->guid));
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
	 * @param int $site_guid Optional site guid.
	 * @return bool
	 */
	public function isActive($site_guid = null) {
		if (!$this->guid) {
			return false;
		}

		if ($site_guid) {
			$site = get_entity($site_guid);
		} else {
			$site = _elgg_services()->configTable->get('site');
		}

		if (!($site instanceof \ElggSite)) {
			return false;
		}

		return check_entity_relationship($this->guid, 'active_plugin', $site->guid);
	}

	/**
	 * Checks if this plugin can be activated on the current
	 * Elgg installation.
	 *
	 * @todo remove $site_guid param or implement it
	 *
	 * @param mixed $site_guid Optional site guid
	 * @return bool
	 */
	public function canActivate($site_guid = null) {
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
	 * @param mixed $site_guid Optional site GUID.
	 * @return bool
	 */
	public function activate($site_guid = null) {
		if ($this->isActive($site_guid)) {
			return false;
		}

		if (!$this->canActivate()) {
			return false;
		}

		// set in the db, now perform tasks and emit events
		if ($this->setStatus(true, $site_guid)) {
			// emit an event. returning false will make this not be activated.
			// we need to do this after it's been fully activated
			// or the deactivate will be confused.
			$params = array(
				'plugin_id' => $this->getID(),
				'plugin_entity' => $this,
			);

			$return = _elgg_services()->events->trigger('activate', 'plugin', $params);

			// if there are any on_enable functions, start the plugin now and run them
			// Note: this will not run re-run the init hooks!
			if ($return) {
				if ($this->canReadFile('activate.php')) {
					$flags = ELGG_PLUGIN_INCLUDE_START | ELGG_PLUGIN_REGISTER_CLASSES |
							ELGG_PLUGIN_REGISTER_LANGUAGES | ELGG_PLUGIN_REGISTER_VIEWS;

					$this->start($flags);

					$return = $this->includeFile('activate.php');
				}
			}

			if ($return === false) {
				$this->deactivate($site_guid);
			}

			return $return;
		}

		return false;
	}

	/**
	 * Deactivates the plugin.
	 *
	 * @param mixed $site_guid Optional site GUID.
	 * @return bool
	 */
	public function deactivate($site_guid = null) {
		if (!$this->isActive($site_guid)) {
			return false;
		}

		// emit an event. returning false will cause this to not be deactivated.
		$params = array(
			'plugin_id' => $this->getID(),
			'plugin_entity' => $this,
		);

		$return = _elgg_services()->events->trigger('deactivate', 'plugin', $params);

		// run any deactivate code
		if ($return) {
			if ($this->canReadFile('deactivate.php')) {
				$return = $this->includeFile('deactivate.php');
			}
		}

		if ($return === false) {
			return false;
		} else {
			return $this->setStatus(false, $site_guid);
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
		//if (!$this->canActivate()) {
		//	return false;
		//}

		// include classes
		if ($flags & ELGG_PLUGIN_REGISTER_CLASSES) {
			$this->registerClasses();
		}
		
		// include start file
		if ($flags & ELGG_PLUGIN_INCLUDE_START) {
			$this->includeFile('start.php');
		}

		// include views
		if ($flags & ELGG_PLUGIN_REGISTER_VIEWS) {
			$this->registerViews();
		}

		// include languages
		if ($flags & ELGG_PLUGIN_REGISTER_LANGUAGES) {
			$this->registerLanguages();
		}

		return true;
	}


	// start helpers

	/**
	 * Get the config object in a deprecation wrapper
	 *
	 * @return \Elgg\DeprecationWrapper
	 */
	protected static function getConfigWrapper() {
		static $wrapper;
		if (null === $wrapper) {
			global $CONFIG;
			$warning = 'Do not rely on local $CONFIG being available in start.php';
			$wrapper = new \Elgg\DeprecationWrapper($CONFIG, $warning, "1.10");
		}
		return $wrapper;
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
		// This needs to be here to be backwards compatible for 1.0-1.7.
		// They expect the global config object to be available in start.php.
		if ($filename == 'start.php') {
			$CONFIG = self::getConfigWrapper();
		}

		$filepath = "$this->path/$filename";

		if (!$this->canReadFile($filename)) {
			$msg = _elgg_services()->translator->translate('ElggPlugin:Exception:CannotIncludeFile',
							array($filename, $this->getID(), $this->guid, $this->path));
			throw new \PluginException($msg);
		}

		return include $filepath;
	}

	/**
	 * Checks whether a plugin file with the given name exists
	 *
	 * @param string $filename The name of the file
	 * @return bool
	 */
	protected function canReadFile($filename) {
		return is_readable($this->path . '/' . $filename);
	}

	/**
	 * Registers the plugin's views
	 *
	 * @throws PluginException
	 * @return void
	 */
	protected function registerViews() {
		if (!_elgg_services()->views->registerPluginViews($this->path, $failed_dir)) {
			$msg = _elgg_services()->translator->translate('ElggPlugin:Exception:CannotRegisterViews',
				array($this->getID(), $this->guid, $failed_dir));
			throw new \PluginException($msg);
		}
	}

	/**
	 * Registers the plugin's languages
	 *
	 * @throws PluginException
	 * @return true
	 */
	protected function registerLanguages() {
		$languages_path = "$this->path/languages";

		// don't need to have classes
		if (!is_dir($languages_path)) {
			return true;
		}

		// but need to have working ones.
		if (!_elgg_services()->translator->registerTranslations($languages_path)) {
			$msg = _elgg_services()->translator->translate('ElggPlugin:Exception:CannotRegisterLanguages',
							array($this->getID(), $this->guid, $languages_path));
			throw new \PluginException($msg);
		}

		return true;
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
	 * Get an attribute or private setting value
	 *
	 * @param string $name Name of the attribute or private setting
	 * @return mixed
	 */
	public function __get($name) {
		// rewrite for old and inaccurate plugin:setting
		if (strstr($name, 'plugin:setting:')) {
			$msg = 'Direct access of user settings is deprecated. Use ElggPlugin->getUserSetting()';
			elgg_deprecated_notice($msg, 1.8);
			$name = str_replace('plugin:setting:', '', $name);
			$name = _elgg_namespace_plugin_private_setting('user_setting', $name, $this->getID());
		}

		// See if its in our base attribute
		if (array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		}

		// @todo clean below - getPrivateSetting() should return null now
		// No, so see if its in the private data store.
		// get_private_setting() returns false if it doesn't exist
		$meta = $this->getPrivateSetting($name);

		if ($meta === false) {
			// Can't find it, so return null
			return null;
		}

		return $meta;
	}

	/**
	 * Get a value from private settings.
	 *
	 * @param string $name Name
	 * @return mixed
	 * @deprecated 1.9
	 */
	public function get($name) {
		elgg_deprecated_notice("Use -> instead of get()", 1.9);
		return $this->__get($name);
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
	 * Save a value as private setting or attribute.
	 *
	 * Attributes include title and description.
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 * @return bool
	 */
	public function set($name, $value) {
		elgg_deprecated_notice("Use -> instead of set()", 1.9);
		$this->__set($name, $value);

		return true;
	}

	/**
	 * Sets the plugin to active or inactive for $site_guid.
	 *
	 * @param bool  $active    Set to active or inactive
	 * @param mixed $site_guid Int for specific site, null for current site.
	 *
	 * @return bool
	 */
	private function setStatus($active, $site_guid = null) {
		if (!$this->guid) {
			return false;
		}

		if ($site_guid) {
			$site = get_entity($site_guid);

			if (!($site instanceof \ElggSite)) {
				return false;
			}
		} else {
			$site = _elgg_services()->configTable->get('site');
		}

		if ($active) {
			$result = add_entity_relationship($this->guid, 'active_plugin', $site->guid);
		} else {
			$result = remove_entity_relationship($this->guid, 'active_plugin', $site->guid);
		}

		_elgg_invalidate_plugins_provides_cache();

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
	 * @return \ElggPluginManifest
	 */
	public function getManifest() {
		if ($this->manifest instanceof \ElggPluginManifest) {
			return $this->manifest;
		}

		try {
			$this->manifest = $this->getPackage()->getManifest();
		} catch (Exception $e) {
			_elgg_services()->logger->warn("Failed to load manifest for plugin $this->guid. " . $e->getMessage());
			$this->errorMsg = $e->getmessage();
		}

		return $this->manifest;
	}

	/**
	 * Returns this plugin's \ElggPluginPackage object
	 *
	 * @return \ElggPluginPackage
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
