<?php

use Elgg\Includer;
use Elgg\Database\Delete;
use Elgg\Exceptions\DatabaseException;
use Elgg\Exceptions\Http\PluginException;
use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;
use Elgg\Database\Plugins;

/**
 * Plugin class containing helper functions for plugin activation/deactivation,
 * dependency checking capabilities and (user)pluginsettings.
 */
class ElggPlugin extends ElggObject {

	const STATIC_CONFIG_FILENAME = 'elgg-plugin.php';
	
	/**
	 * The optional files that can be read and served through the markdown page handler
	 *
	 * @var string[]
	 */
	const ADDITIONAL_TEXT_FILES = [
		'README.txt',
		'CHANGES.txt',
		'INSTALL.txt',
		'COPYRIGHT.txt',
		'LICENSE.txt',
		'README',
		'README.md',
		'README.markdown',
	];
	
	/**
	 * @var \Elgg\Plugin\Composer
	 */
	protected $composer;

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * Data from static config file. null if not yet read.
	 *
	 * @var array|null
	 */
	protected $static_config;

	/**
	 * @var string
	 */
	protected $errorMsg = '';
	
	/**
	 * @var bool
	 */
	protected $activated;

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'plugin';
	}

	/**
	 * Load a plugin object from its ID
	 * Create a new plugin entity if doesn't exist
	 *
	 * @param string $plugin_id Plugin ID
	 * @param string $path      Path, defaults to /mod
	 *
	 * @return ElggPlugin
	 * @throws ElggInvalidArgumentException
	 */
	public static function fromId($plugin_id, $path = null) {
		if (empty($plugin_id)) {
			throw new ElggInvalidArgumentException('Plugin ID must be set');
		}

		$plugin = elgg_get_plugin_from_id($plugin_id);

		if (!$plugin) {
			$ia = _elgg_services()->session->setIgnoreAccess(true);
			$plugin = new ElggPlugin();
			$plugin->title = $plugin_id;
			$plugin->save();

			_elgg_services()->session->setIgnoreAccess($ia);
		}

		if (!$path) {
			$path = elgg_get_plugins_path();
		}

		$path = rtrim($path, '/');
		$plugin->setPath($path . '/' . $plugin_id);

		return $plugin;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() : bool {

		$site = elgg_get_site_entity();

		$this->attributes['owner_guid'] = $site->guid;
		$this->attributes['container_guid'] = $site->guid;
		$this->attributes['access_id'] = ACCESS_PUBLIC;

		$new = !$this->guid;
		$priority = null;
		if ($new) {
			$name = _elgg_services()->plugins->namespacePrivateSetting('internal', 'priority');
			$priority = elgg_extract($name, $this->temp_private_settings, 'new');
		} elseif ($this->getPriority() === null) {
			$priority = 'last';
		}

		if ($priority) {
			$this->setPriority($priority);
		}
		
		return parent::save();
	}

	/**
	 * Returns the ID (dir name) of this plugin
	 *
	 * @return string
	 */
	public function getID() {
		return $this->title;
	}

	/**
	 * Returns the name from elgg-plugin.php if available, otherwise a nicely formatted ID.
	 *
	 * @return string
	 * @since 3.0
	 */
	public function getDisplayName() {
		$name = elgg_extract('name', $this->getStaticConfig('plugin', []));
		if (!empty($name)) {
			return $name;
		}
		
		return ucwords(str_replace(['-', '_'], ' ', $this->getID()));
	}

	/**
	 * Set path
	 *
	 * @param string $path Path to plugin directory
	 *
	 * @return void
	 * @internal
	 */
	public function setPath($path) {
		$this->path = \Elgg\Project\Paths::sanitize($path, true);
	}

	/**
	 * Returns the plugin's full path with trailing slash.
	 *
	 * @return string
	 */
	public function getPath() {
		if (isset($this->path)) {
			return $this->path;
		}
		
		$this->setPath(elgg_get_plugins_path() . $this->getID());
		return $this->path;
	}

	/**
	 * Returns the plugin's languages directory full path with trailing slash.
	 * Returns false if directory does not exist
	 *
	 * @return string|false
	 */
	protected function getLanguagesPath() {
		$languages_path = $this->getPath() . 'languages/';
		if (!is_dir($languages_path)) {
			return false;
		}
		
		return $languages_path;
	}

	/**
	 * Get a value from the plugins's static config file.
	 *
	 * @note     If the system cache is on, Elgg APIs should not call this on every request.
	 *
	 * @param string $key     Config key
	 * @param mixed  $default Value returned if missing
	 *
	 * @return mixed
	 * @internal
	 */
	public function getStaticConfig($key, $default = null) {
		if ($this->static_config === null) {
			$this->static_config = [];

			try {
				if ($this->canReadFile(self::STATIC_CONFIG_FILENAME)) {
					$this->static_config = $this->includeFile(self::STATIC_CONFIG_FILENAME);
				}
			} catch (PluginException $ex) {
				elgg_log($ex, \Psr\Log\LogLevel::ERROR);
			}
		}

		if (isset($this->static_config[$key])) {
			return $this->static_config[$key];
		} else {
			return $default;
		}
	}

	// Load Priority

	/**
	 * Gets the plugin's load priority.
	 *
	 * @return int|null
	 */
	public function getPriority() {
		$name = _elgg_services()->plugins->namespacePrivateSetting('internal', 'priority');

		$priority = $this->getPrivateSetting($name);
		if (isset($priority)) {
			return (int) $priority;
		}

		return null;
	}

	/**
	 * Sets the priority of the plugin
	 * Returns the new priority or false on error
	 *
	 * @param mixed $priority The priority to set
	 *                        One of +1, -1, first, last, or a number.
	 *                        If given a number, this will displace all plugins at that number
	 *                        and set their priorities +1
	 *
	 * @return int|false
	 */
	public function setPriority($priority) {
		$priority = $this->normalizePriority($priority);

		return _elgg_services()->plugins->setPriority($this, $priority);
	}

	/**
	 * Normalize and validate new priority
	 *
	 * @param mixed $priority Priority to normalize
	 *
	 * @return int
	 * @internal
	 */
	protected function normalizePriority($priority) {
		// if no priority assume a priority of 1
		$old_priority = $this->getPriority();
		$old_priority = $old_priority ? : 1;
		$max_priority = _elgg_services()->plugins->getMaxPriority() ? : 1;

		// can't use switch here because it's not strict and php evaluates +1 == 1
		if ($priority === '+1') {
			$priority = $old_priority + 1;
		} else if ($priority === '-1') {
			$priority = $old_priority - 1;
		} else if ($priority === 'first') {
			$priority = 1;
		} else if ($priority === 'last') {
			$priority = $max_priority;
		} else if ($priority === 'new') {
			$max_priority++;
			$priority = $max_priority;
		}

		return min($max_priority, max(1, (int) $priority));
	}

	// Plugin settings

	/**
	 * Returns a plugin setting
	 *
	 * @param string $name    The setting name
	 * @param mixed  $default The default value to return if none is set
	 *
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

		try {
			$defaults = [];
			if ($this->isActive()) {
				// only load settings from static config for active plugins to prevent issues
				// with internal plugin references ie. classes and language keys
				$defaults = $this->getStaticConfig('settings', []);
			}

			if (!$this->guid) {
				$settings = $this->temp_private_settings;
			} else {
				$settings = _elgg_services()->plugins->getAllSettings($this);
			}

			return array_merge($defaults, $settings);
		} catch (DatabaseException $ex) {
			return [];
		}
	}

	/**
	 * Set a plugin setting for the plugin
	 *
	 * @param string $name  The name to set
	 * @param string $value The value to set
	 *
	 * @return bool
	 */
	public function setSetting($name, $value) {

		$value = _elgg_services()->hooks->trigger('setting', 'plugin', [
			'plugin_id' => $this->getID(),
			'plugin' => $this,
			'name' => $name,
			'value' => $value,
		], $value);

		if (is_array($value)) {
			elgg_log('Plugin settings cannot store arrays.', 'ERROR');

			return false;
		}

		return $this->setPrivateSetting($name, $value);
	}

	/**
	 * Removes a plugin setting name and value
	 *
	 * @param string $name The setting name to remove
	 *
	 * @return bool
	 */
	public function unsetSetting($name) {
		return $this->removePrivateSetting($name);
	}

	/**
	 * Removes all settings for this plugin
	 * @return bool
	 */
	public function unsetAllSettings() {
		$settings = $this->getAllSettings();

		foreach ($settings as $name => $value) {
			if (strpos($name, 'elgg:internal:') === 0) {
				continue;
			}
			$this->unsetSetting($name);
		}

		return true;
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
	 *
	 * @return array An array of key/value pairs
	 */
	public function getAllUserSettings($user_guid = 0) {

		$user = _elgg_services()->entityTable->getUserForPermissionsCheck($user_guid);
		if (!$user instanceof ElggUser) {
			return [];
		}

		$defaults = $this->getStaticConfig('user_settings', []);

		$settings = _elgg_services()->plugins->getAllUserSettings($this, $user);

		return array_merge($defaults, $settings);
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
		$user = _elgg_services()->entityTable->getUserForPermissionsCheck($user_guid);
		if (!$user instanceof ElggUser) {
			return false;
		}

		$value = _elgg_services()->hooks->trigger('usersetting', 'plugin', [
			'user' => $user,
			'plugin' => $this,
			'plugin_id' => $this->getID(),
			'name' => $name,
			'value' => $value
		], $value);

		if (is_array($value)) {
			elgg_log('Plugin user settings cannot store arrays.', 'ERROR');

			return false;
		}

		$name = _elgg_services()->plugins->namespacePrivateSetting('user_setting', $name, $this->getID());

		return $user->setPrivateSetting($name, $value);
	}

	/**
	 * Removes a user setting name and value.
	 *
	 * @param string $name      The user setting name
	 * @param int    $user_guid The user GUID
	 *
	 * @return bool
	 */
	public function unsetUserSetting($name, $user_guid = 0) {
		$user = _elgg_services()->entityTable->getUserForPermissionsCheck($user_guid);

		if (!$user instanceof ElggUser) {
			return false;
		}

		$name = _elgg_services()->plugins->namespacePrivateSetting('user_setting', $name, $this->getID());

		return $user->removePrivateSetting($name);
	}

	/**
	 * Removes all plugin settings for a given user
	 *
	 * @param int $user_guid The user GUID to remove user settings.
	 *
	 * @return bool
	 */
	public function unsetAllUserSettings($user_guid = 0) {
		$user = _elgg_services()->entityTable->getUserForPermissionsCheck($user_guid);

		if (!$user instanceof ElggUser) {
			return false;
		}

		$settings = $this->getAllUserSettings($user_guid);

		foreach ($settings as $name => $value) {
			$name = _elgg_services()->plugins->namespacePrivateSetting('user_setting', $name, $this->getID());
			$user->removePrivateSetting($name);
		}

		return true;
	}
	
	/**
	 * Remove all user and plugin settings for this plugin
	 *
	 * @return bool
	 * @since 3.3
	 */
	public function unsetAllUserAndPluginSettings() {
		// remove all plugin settings
		$result = $this->unsetAllSettings();
		
		// user plugin settings are stored with the user
		$prefix = _elgg_services()->plugins->namespacePrivateSetting('user_setting', '', $this->getID());
		
		$delete = Delete::fromTable('private_settings');
		$delete->andWhere($delete->compare('name', 'like', "{$prefix}%", ELGG_VALUE_STRING));
		
		try {
			elgg()->db->deleteData($delete);
			
			$result &= true;
		} catch (DatabaseException $e) {
			elgg_log($e, 'ERROR');
			
			$result &= false;
		}
		
		// trigger a hook, so plugin devs can also remove settings
		$params = [
			'entity' => $this,
		];
		return (bool) elgg()->hooks->trigger('remove:settings', 'plugin', $params, $result);
	}
	
	/**
	 * Returns if the plugin is complete, meaning has all required files
	 * and Elgg can read them and they make sense.
	 *
	 * @return bool
	 */
	public function isValid() {
		if (!$this->getID()) {
			$this->errorMsg = elgg_echo('ElggPlugin:MissingID', [$this->guid]);
			return false;
		}
		
		try {
			$this->getComposer()->assertPluginId();
		} catch (\Elgg\Exceptions\PluginException $e) {
			$this->errorMsg = $e->getMessage();
			return false;
		}
		
		if (file_exists($this->getPath() . 'start.php')) {
			$this->errorMsg = elgg_echo('ElggPlugin:StartFound', [$this->getID()]);
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
		if (isset($this->activated)) {
			return $this->activated;
		}

		$this->activated = elgg_is_active_plugin($this->getID());
		return $this->activated;
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
		
		if (!$this->isValid()) {
			return false;
		}

		return $this->meetsDependencies();
	}

	// activating and deactivating

	/**
	 * Activates the plugin for the current site.
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
			try {
				elgg_clear_caches();

				$this->register();
				
				$services = $this->getPath() . 'elgg-services.php';
				if (is_file($services) && is_readable($services)) {
					// reset dic so new services can be detected
					_elgg_services()->reset('dic_loader');
					_elgg_services()->reset('dic_builder');
					_elgg_services()->reset('dic');
				}
				
				// directly load languages to have them available during runtime
				$this->loadLanguages();
				
				$this->boot();
				
				$this->getBootstrap()->activate();

				$this->init();
			} catch (PluginException $ex) {
				elgg_log($ex, \Psr\Log\LogLevel::ERROR);

				$return = false;
			}
		}

		if ($return === false) {
			$this->deactivate();
		} else {
			elgg_delete_admin_notice("cannot_start {$this->getID()}");

			elgg_clear_caches();
			_elgg_services()->logger->notice("Plugin {$this->getID()} has been activated");
		}

		return $return;
	}
	
	/**
	 * Returns an array of dependencies as configured in the static config
	 *
	 * @return array
	 */
	public function getDependencies() {
		$plugin_config = $this->getStaticConfig('plugin', []);
		return (array) elgg_extract('dependencies', $plugin_config, []);
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
			$dependencies = $plugin->getDependencies();
			if (!array_key_exists($this->getID(), $dependencies)) {
				continue;
			}
				
			if (elgg_extract('must_be_active', $dependencies[$this->getID()], true)) {
				$dependents[$plugin->getID()] = $plugin;
			}
		}

		if (empty($dependents)) {
			return true;
		}
			
		$list = array_map(function (\ElggPlugin $plugin) {
			$css_id = preg_replace('/[^a-z0-9-]/i', '-', $plugin->getID());

			return elgg_view('output/url', [
				'text' => $plugin->getDisplayName(),
				'href' => "#$css_id",
			]);
		}, $dependents);
		
		$name = $this->getDisplayName();
		$list = implode(', ', $list);
		$this->errorMsg = elgg_echo('ElggPlugin:Dependencies:ActiveDependent', [$name, $list]);

		return false;
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
		if ($return === false) {
			return false;
		}

		$this->getBootstrap()->deactivate();

		$this->deactivateEntities();

		elgg_clear_caches();

		_elgg_services()->logger->notice("Plugin {$this->getID()} has been deactivated");

		return $this->setStatus(false);
	}

	/**
	 * Bootstrap object
	 * @return \Elgg\PluginBootstrapInterface
	 * @throws PluginException
	 * @internal
	 */
	public function getBootstrap() {
		$bootstrap = $this->getStaticConfig('bootstrap');
		if ($bootstrap) {
			if (!is_subclass_of($bootstrap, \Elgg\PluginBootstrapInterface::class)) {
				throw PluginException::factory(
					'InvalidBootstrap',
					$this,
					elgg_echo('LogicException:InterfaceNotImplemented', [
						$bootstrap,
						\Elgg\PluginBootstrapInterface::class
					])
				);
			}

			return new $bootstrap($this, _elgg_services()->dic);
		}

		return new \Elgg\DefaultPluginBootstrap($this, _elgg_services()->dic);
	}

	/**
	 * Register plugin classes and require composer autoloader
	 *
	 * @return void
	 * @internal
	 */
	public function autoload() {
		$this->registerClasses();

		$autoload_file = 'vendor/autoload.php';
		if (!$this->canReadFile($autoload_file)) {
			return;
		}
		
		$autoloader = Includer::requireFileOnce("{$this->getPath()}{$autoload_file}");
		
		if (!$autoloader instanceof \Composer\Autoload\ClassLoader) {
			return;
		}
		
		$autoloader->unregister();
		
		// plugins should be appended, composer defaults to prepend
		$autoloader->register(false);
	}

	/**
	 * Autoload plugin classes and vendor libraries
	 * Register plugin-specific entity classes and execute bootstrapped load scripts
	 * Register languages and views
	 *
	 * @return void
	 * @internal
	 */
	public function register() {
		$this->autoload();

		$this->activateEntities();
		$this->registerLanguages();
		$this->registerViews();

		$this->getBootstrap()->load();
	}

	/**
	 * Boot the plugin
	 *
	 * @return void
	 * @internal
	 */
	public function boot() {
		$this->getBootstrap()->boot();
	}

	/**
	 * Init the plugin
	 *
	 * @return void
	 * @internal
	 */
	public function init() {
		$this->registerRoutes();
		$this->registerActions();
		$this->registerEntities();
		$this->registerWidgets();
		$this->registerHooks();
		$this->registerEvents();
		$this->registerViewExtensions();
		$this->registerGroupTools();
		$this->registerViewOptions();

		$this->getBootstrap()->init();
	}

	/**
	 * Includes one of the plugins files
	 *
	 * @param string $filename The name of the file
	 *
	 * @return mixed The return value of the included file (or 1 if there is none)
	 * @throws PluginException
	 */
	protected function includeFile($filename) {
		$filepath = "{$this->getPath()}{$filename}";

		if (!$this->canReadFile($filename)) {
			$msg = elgg_echo(
				'ElggPlugin:Exception:CannotIncludeFile',
				[$filename, $this->getID(), $this->guid, $this->getPath()]
			);

			throw PluginException::factory('CannotIncludeFile', $this, $msg);
		}

		try {
			$ret = Includer::requireFile($filepath);
		} catch (Exception $e) {
			$msg = elgg_echo(
				'ElggPlugin:Exception:IncludeFileThrew',
				[$filename, $this->getID(), $this->guid, $this->getPath()]
			);

			throw PluginException::factory('IncludeFileThrew', $this, $msg, $e);
		}

		return $ret;
	}

	/**
	 * Checks whether a plugin file with the given name exists
	 *
	 * @param string $filename The name of the file
	 *
	 * @return bool
	 */
	protected function canReadFile($filename) {
		$path = "{$this->getPath()}{$filename}";

		return is_file($path) && is_readable($path);
	}

	/**
	 * If a static config file is present, is it a serializable array?
	 *
	 * @return bool
	 */
	protected function isStaticConfigValid() {
		if (!$this->canReadFile(self::STATIC_CONFIG_FILENAME)) {
			return true;
		}

		ob_start();
		$value = $this->includeFile(self::STATIC_CONFIG_FILENAME);
		if (ob_get_clean() !== '') {
			$this->errorMsg = elgg_echo('ElggPlugin:activate:ConfigSentOutput');

			return false;
		}

		// make sure can serialize
		$value = @unserialize(serialize($value));
		if (!is_array($value)) {
			$this->errorMsg = elgg_echo('ElggPlugin:activate:BadConfigFormat');

			return false;
		}

		return true;
	}

	/**
	 * Registers the plugin's views
	 *
	 * @return void
	 * @throws PluginException
	 */
	protected function registerViews() {
		if (_elgg_services()->config->system_cache_loaded) {
			return;
		}

		$views = _elgg_services()->views;

		// Declared views first
		$spec = $this->getStaticConfig('views');
		if ($spec) {
			$views->mergeViewsSpec($spec);
		}

		// Allow /views directory files to override
		if (!$views->registerPluginViews($this->getPath(), $failed_dir)) {
			$key = 'ElggPlugin:Exception:CannotRegisterViews';
			$args = [$this->getID(), $this->guid, $failed_dir];
			$msg = elgg_echo($key, $args);

			throw PluginException::factory('CannotRegisterViews', $this, $msg);
		}
	}

	/**
	 * Registers the plugin's entities
	 *
	 * @return void
	 */
	protected function registerEntities() {
		$spec = (array) $this->getStaticConfig('entities', []);
		
		foreach ($spec as $entity) {
			if (isset($entity['type'], $entity['subtype'], $entity['searchable']) && $entity['searchable']) {
				elgg_register_entity_type($entity['type'], $entity['subtype']);
			}
		}
	}

	/**
	 * Registers the plugin's actions provided in the plugin config file
	 *
	 * @return void
	 */
	protected function registerActions() {
		$actions = _elgg_services()->actions;
		$root_path = rtrim($this->getPath(), '/\\');

		$spec = (array) $this->getStaticConfig('actions', []);
		
		foreach ($spec as $action => $action_spec) {
			if (!is_array($action_spec)) {
				continue;
			}

			$access = elgg_extract('access', $action_spec, 'logged_in');
			$handler = elgg_extract('controller', $action_spec);
			if (!$handler) {
				$handler = elgg_extract('filename', $action_spec);
				if (!$handler) {
					$handler = "$root_path/actions/{$action}.php";
				}
			}

			$actions->register($action, $handler, $access);
		}
	}

	/**
	 * Registers the plugin's routes provided in the plugin config file
	 *
	 * @return void
	 */
	protected function registerRoutes() {
		$routes = _elgg_services()->routes;

		$spec = (array) $this->getStaticConfig('routes', []);
		foreach ($spec as $name => $route_spec) {
			if (!is_array($route_spec)) {
				continue;
			}

			$routes->register($name, $route_spec);
		}
	}

	/**
	 * Registers the plugin's widgets provided in the plugin config file
	 *
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
	 * Makes the language paths available to the system. Commonly used during boot of engine.
	 *
	 * @return void
	 */
	public function registerLanguages() {
		$languages_path = $this->getLanguagesPath();
		if (empty($languages_path)) {
			return;
		}

		_elgg_services()->translator->registerLanguagePath($languages_path);
	}

	/**
	 * Loads the plugin's translations
	 *
	 * Directly loads the translations for this plugin into available translations.
	 *
	 * Use when on runtime activating a plugin.
	 *
	 * @return void
	 */
	protected function loadLanguages() {
		$languages_path = $this->getLanguagesPath();
		if (empty($languages_path)) {
			return;
		}

		_elgg_services()->translator->registerTranslations($languages_path);
	}

	/**
	 * Registers the plugin's classes
	 *
	 * @return void
	 */
	protected function registerClasses() {
		$classes_path = "{$this->getPath()}classes";

		_elgg_services()->autoloadManager->addClasses($classes_path);
	}

	/**
	 * Activates the plugin's entities
	 *
	 * @return void
	 */
	protected function activateEntities() {
		$spec = (array) $this->getStaticConfig('entities', []);
		
		foreach ($spec as $entity) {
			if (isset($entity['type'], $entity['subtype'], $entity['class'])) {
				elgg_set_entity_class($entity['type'], $entity['subtype'], $entity['class']);
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
		
		foreach ($spec as $entity) {
			if (isset($entity['type'], $entity['subtype'], $entity['class'])) {
				elgg_set_entity_class($entity['type'], $entity['subtype']);
			}
		}
	}
	
	/**
	 * Registers the plugin's hooks provided in the plugin config file
	 *
	 * @return void
	 */
	protected function registerHooks() {
		$hooks = _elgg_services()->hooks;

		$spec = (array) $this->getStaticConfig('hooks', []);

		foreach ($spec as $name => $types) {
			foreach ($types as $type => $callbacks) {
				foreach ($callbacks as $callback => $hook_spec) {
					if (!is_array($hook_spec)) {
						continue;
					}
					
					$unregister = (bool) elgg_extract('unregister', $hook_spec, false);
					
					if ($unregister) {
						$hooks->unregisterHandler($name, $type, $callback);
					} else {
						$priority = (int) elgg_extract('priority', $hook_spec, 500);
			
						$hooks->registerHandler($name, $type, $callback, $priority);
					}
				}
			}
		}
	}
	
	/**
	 * Registers the plugin's events provided in the plugin config file
	 *
	 * @return void
	 */
	protected function registerEvents() {
		$events = _elgg_services()->events;

		$spec = (array) $this->getStaticConfig('events', []);

		foreach ($spec as $name => $types) {
			foreach ($types as $type => $callbacks) {
				foreach ($callbacks as $callback => $hook_spec) {
					if (!is_array($hook_spec)) {
						continue;
					}
					
					$unregister = (bool) elgg_extract('unregister', $hook_spec, false);

					if ($unregister) {
						$events->unregisterHandler($name, $type, $callback);
					} else {
						$priority = (int) elgg_extract('priority', $hook_spec, 500);
			
						$events->registerHandler($name, $type, $callback, $priority);
					}
				}
			}
		}
	}
	
	/**
	 * Registers the plugin's view extensions provided in the plugin config file
	 *
	 * @return void
	 */
	protected function registerViewExtensions() {
		$views = _elgg_services()->views;
		
		$spec = (array) $this->getStaticConfig('view_extensions', []);

		foreach ($spec as $src_view => $extensions) {
			foreach ($extensions as $extention => $extention_spec) {
				if (!is_array($extention_spec)) {
					continue;
				}
				
				$unextend = (bool) elgg_extract('unextend', $extention_spec, false);

				if ($unextend) {
					$views->unextendView($src_view, $extention);
				} else {
					$priority = (int) elgg_extract('priority', $extention_spec, 501);
		
					$views->extendView($src_view, $extention, $priority);
				}
			}
		}
	}
	
	/**
	 * Registers the plugin's group tools provided in the plugin config file
	 *
	 * @return void
	 */
	protected function registerGroupTools() {
		$tools = _elgg_services()->group_tools;
		
		$spec = (array) $this->getStaticConfig('group_tools', []);

		foreach ($spec as $tool_name => $tool_options) {
			if (!is_array($tool_options)) {
				continue;
			}
			
			$unregister = (bool) elgg_extract('unregister', $tool_options, false);

			if ($unregister) {
				$tools->unregister($tool_name);
			} else {
				$tools->register($tool_name, $tool_options);
			}
		}
	}
	
	/**
	 * Registers the plugin's view options provided in the plugin config file
	 *
	 * @return void
	 */
	protected function registerViewOptions() {
		$spec = (array) $this->getStaticConfig('view_options', []);

		foreach ($spec as $view_name => $options) {
			if (!is_array($options)) {
				continue;
			}
			
			if (isset($options['ajax'])) {
				if ($options['ajax'] === true) {
					_elgg_services()->ajax->registerView($view_name);
				} else {
					_elgg_services()->ajax->unregisterView($view_name);
				}
			}
			
			if (isset($options['simplecache']) && $options['simplecache'] === true) {
				_elgg_services()->views->registerCacheableView($view_name);
			}
		}
	}

	/**
	 * Get an attribute, metadata or private setting value
	 *
	 * @param string $name Name of the attribute or private setting
	 *
	 * @return mixed
	 */
	public function __get($name) {
		// See if its in our base attribute
		if (array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		}

		// object title and description are stored as metadata
		if (in_array($name, ['title', 'description'])) {
			return parent::__get($name);
		}

		$result = $this->getPrivateSetting($name);
		if ($result !== null) {
			return $result;
		}

		$defaults = $this->getStaticConfig('settings', []);

		return elgg_extract($name, $defaults, $result);
	}

	/**
	 * Set a value as attribute, metadata or private setting.
	 *
	 * Metadata applies to title and description.
	 *
	 * @param string $name  Name of the attribute or private_setting
	 * @param mixed  $value Value to be set
	 *
	 * @return void
	 */
	public function __set($name, $value) {
		if (array_key_exists($name, $this->attributes)) {
			// Check that we're not trying to change the guid!
			if ((array_key_exists('guid', $this->attributes)) && ($name == 'guid')) {
				return;
			}

			$this->attributes[$name] = $value;

			return;
		}

		// object title and description are stored as metadata
		if (in_array($name, ['title', 'description'])) {
			parent::__set($name, $value);

			return;
		}

		// to make sure we trigger the correct hooks
		$this->setSetting($name, $value);
	}

	/**
	 * Sets the plugin to active or inactive.
	 *
	 * @param bool $active Set to active or inactive
	 *
	 * @return bool
	 */
	protected function setStatus($active) {
		if (!$this->guid) {
			return false;
		}

		$site = elgg_get_site_entity();
		if ($active) {
			$result = add_entity_relationship($this->guid, 'active_plugin', $site->guid);
		} else {
			$result = remove_entity_relationship($this->guid, 'active_plugin', $site->guid);
		}
		
		if ($result) {
			$this->activated = $active;
		}

		$this->invalidateCache();

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
	 * {@inheritdoc}
	 */
	public function isCacheable() {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function cache($persist = true) {
		_elgg_services()->plugins->cache($this);

		parent::cache($persist);
	}

	/**
	 * {@inheritdoc}
	 */
	public function invalidateCache() {
		
		_elgg_services()->boot->clearCache();
		_elgg_services()->plugins->invalidateCache($this->getID());

		parent::invalidateCache();
	}

	/**
	 * Returns the composer parser
	 *
	 * @return \Elgg\Plugin\Composer
	 *
	 * @since 4.0
	 */
	protected function getComposer() {
		if (isset($this->composer)) {
			return $this->composer;
		}
		
		$this->composer = new \Elgg\Plugin\Composer($this);
		return $this->composer;
	}
	
	/**
	 * Checks if dependencies are met
	 *
	 * @return boolean
	 *
	 * @since 4.0
	 */
	public function meetsDependencies() {
		try {
			$this->assertDependencies();
			
			return true;
		} catch (\Elgg\Exceptions\PluginException $e) {
			return false;
		}
	}
	
	/**
	 * Assert plugin dependencies
	 *
	 * @return void
	 * @throws \Elgg\Exceptions\PluginException
	 */
	public function assertDependencies() {
		try {
			$this->getComposer()->assertConflicts();
			$this->getComposer()->assertActivePluginConflicts();
			$this->getComposer()->assertRequiredPhpVersion();
			$this->getComposer()->assertRequiredPhpExtensions();
		} catch (\Elgg\Exceptions\PluginException $e) {
			$this->errorMsg = $e->getMessage();
			throw $e;
		}
	}
	
	/**
	 * Returns the plugin version
	 *
	 * @return string
	 */
	public function getVersion() {
		// composer version
		$version = $this->getComposer()->getConfiguration()->version();
		if (!elgg_is_empty($version)) {
			return $version;
		}
		
		// elgg-plugin version
		$plugin_config = $this->getStaticConfig('plugin', []);
		$version = elgg_extract('version', $plugin_config);
		if (!elgg_is_empty($version)) {
			return $version;
		}
		
		// bundled plugins use elgg version
		if (in_array($this->getID(), Plugins::BUNDLED_PLUGINS)) {
			return elgg_get_version(true);
		}
		
		return '0.1';
	}
	
	/**
	 * Returns an array with categories
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function getCategories() {
		return $this->getComposer()->getCategories();
	}
	
	/**
	 * Returns the license
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function getLicense() {
		return $this->getComposer()->getLicense();
	}
	
	/**
	 * Return the description
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function getDescription() {
		return (string) $this->getComposer()->getConfiguration()->description();
	}
	
	/**
	 * Returns the repository url
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function getRepositoryURL() {
		return (string) $this->getComposer()->getConfiguration()->support()->source();
	}
	
	/**
	 * Returns the bug tracker page
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function getBugTrackerURL() {
		return (string) $this->getComposer()->getConfiguration()->support()->issues();
	}
	
	/**
	 * Return the website
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function getWebsite() {
		return (string) $this->getComposer()->getConfiguration()->homepage();
	}
	
	/**
	 * Returns an array of authors
	 *
	 * @return \Eloquent\Composer\Configuration\Element\Author[]
	 *
	 * @since 4.0
	 */
	public function getAuthors() {
		return (array) $this->getComposer()->getConfiguration()->authors();
	}
	
	/**
	 * Returns an array of projectnames with their conflicting version
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function getConflicts() {
		return $this->getComposer()->getConflicts();
	}
}
