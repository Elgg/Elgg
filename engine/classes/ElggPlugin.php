<?php

use Elgg\Application;
use Elgg\Includer;

/**
 * Stores site-side plugin settings as private data.
 *
 * This class is currently a stub, allowing a plugin to
 * save settings in an object's private settings for each site.
 */
class ElggPlugin extends ElggObject {

	/**
	 * @var ElggPluginPackage
	 */
	protected $package;

	/**
	 * @var ElggPluginManifest
	 */
	protected $manifest;

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
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "plugin";
	}

	/**
	 * Load a plugin object from its ID
	 * Create a new plugin entity if doesn't exist
	 *
	 * @param string $plugin_id Plugin ID
	 * @param string $path      Path, defaults to /mod
	 *
	 * @return ElggPlugin
	 * @throws PluginException
	 */
	public static function fromId($plugin_id, $path = null) {
		if (empty($plugin_id)) {
			throw new PluginException('Plugin ID must be set');
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
	public function save() {

		$site = elgg_get_site_entity();

		$this->attributes['owner_guid'] = $site->guid;
		$this->attributes['container_guid'] = $site->guid;
		$this->attributes['access_id'] = ACCESS_PUBLIC;

		$new = !$this->guid;
		$priority = null;
		if ($new) {
			$name = _elgg_services()->plugins->namespacePrivateSetting('internal', 'priority');
			$priority = elgg_extract($name, $this->temp_private_settings, 'new');
		} else if (!$this->getPriority()) {
			$priority = 'last';
		}

		$guid = parent::save();
		if ($guid && $priority) {
			$this->setPriority($new ? 'new' : 'last');
		}

		return $guid;
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
	 * Returns the manifest's name if available, otherwise the ID.
	 *
	 * @return string
	 * @since 3.0
	 */
	public function getDisplayName() {
		$manifest = $this->getManifest();
		if ($manifest) {
			return $manifest->getName();
		}

		return $this->getID();
	}

	/**
	 * Set path
	 *
	 * @param string $path Path to plugin directory
	 *
	 * @return void
	 * @access private
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * Returns the plugin's full path with trailing slash.
	 *
	 * @return string
	 */
	public function getPath() {
		if (isset($this->path)) {
			$path = $this->path;
		} else {
			$path = elgg_get_plugins_path() . $this->getID();
		}

		return \Elgg\Project\Paths::sanitize($path, true);
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
	 * @access   private
	 * @internal For Elgg internal use only
	 */
	public function getStaticConfig($key, $default = null) {
		if ($this->static_config === null) {
			$this->static_config = [];

			try {
				if ($this->canReadFile(ElggPluginPackage::STATIC_CONFIG_FILENAME)) {
					$this->static_config = $this->includeFile(ElggPluginPackage::STATIC_CONFIG_FILENAME);
				}
			} catch (PluginException $ex) {
				elgg_log($ex->getMessage(), 'WARNING');
			}
		}

		if (isset($this->static_config[$key])) {
			return $this->static_config[$key];
		} else {
			return $default;
		}
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
				$files[$filename] = "{$this->getPath()}{$filename}";
			}
		}

		return $files;
	}

	// Load Priority

	/**
	 * Gets the plugin's load priority.
	 *
	 * @return int|null
	 */
	public function getPriority() {
		$name = _elgg_services()->plugins->namespacePrivateSetting('internal', 'priority');

		$priority = $this->getSetting($name);
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
	 * @throws DatabaseException
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
	 * @access private
	 */
	public function normalizePriority($priority) {
		// if no priority assume a priority of 1
		$old_priority = $this->getPriority();
		$old_priority = $old_priority ? : 1;
		$max_priority = _elgg_get_max_plugin_priority() ? : 1;

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
	 * @throws DatabaseException
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
	 * @throws DatabaseException
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
	 * @throws DatabaseException
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
	 * Returns if the plugin is complete, meaning has all required files
	 * and Elgg can read them and they make sense.
	 *
	 * @return bool
	 */
	public function isValid() {
		if (!$this->getID()) {
			$this->errorMsg = _elgg_services()->translator->translate('ElggPlugin:MissingID', [$this->guid]);

			return false;
		}

		if (!$this->getPackage() instanceof ElggPluginPackage) {
			$this->errorMsg = _elgg_services()->translator->translate('ElggPlugin:NoPluginPackagePackage', [
				$this->getID(),
				$this->guid
			]);

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

		return check_entity_relationship($this->guid, 'active_plugin', $site->guid) instanceof ElggRelationship;
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
	 * @throws InvalidParameterException
	 * @throws PluginException
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

		$return = _elgg_services()->hooks->getEvents()->trigger('activate', 'plugin', $params);

		// if there are any on_enable functions, start the plugin now and run them
		// Note: this will not run re-run the init hooks!
		if ($return) {
			try {
				_elgg_services()->hooks->getEvents()->trigger('cache:flush', 'system');

				$setup = $this->boot();
				if ($setup instanceof Closure) {
					$setup();
				}

				if ($this->canReadFile('activate.php')) {
					$return = $this->includeFile('activate.php');
				}

				$this->init();
			} catch (PluginException $ex) {
				$return = false;
			}
		}

		if ($return === false) {
			$this->deactivate();
		} else {
			_elgg_services()->hooks->getEvents()->trigger('cache:flush', 'system');
			_elgg_services()->logger->notice("Plugin {$this->getID()} has been activated");
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
			$list = array_map(function (\ElggPlugin $plugin) {
				$css_id = preg_replace('/[^a-z0-9-]/i', '-', $plugin->getManifest()->getID());

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

		return true;
	}

	/**
	 * Deactivates the plugin.
	 *
	 * @return bool
	 * @throws PluginException
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

		$return = _elgg_services()->hooks->getEvents()->trigger('deactivate', 'plugin', $params);
		if ($return === false) {
			return false;
		}

		// run any deactivate code
		if ($this->canReadFile('deactivate.php')) {
			// allows you to prevent disabling a plugin by returning false in a deactivate.php file
			if ($this->includeFile('deactivate.php') === false) {
				return false;
			}
		}

		$this->deactivateEntities();

		_elgg_services()->hooks->getEvents()->trigger('cache:flush', 'system');

		_elgg_services()->logger->notice("Plugin {$this->getID()} has been deactivated");

		return $this->setStatus(false);
	}

	/**
	 * Boot the plugin by autoloading files, classes etc
	 *
	 * @throws PluginException
	 * @return \Closure|null
	 */
	public function boot() {
		// Detect plugins errors early and throw so that plugins service can disable the plugin
		if (!$this->getManifest()) {
			throw new PluginException($this->getError());
		}

		$this->registerClasses();

		$autoload_file = 'vendor/autoload.php';
		if ($this->canReadFile($autoload_file)) {
			Application::requireSetupFileOnce("{$this->getPath()}{$autoload_file}");
		}

		$this->activateEntities();

		$result = null;
		if ($this->canReadFile('start.php')) {
			$result = Application::requireSetupFileOnce("{$this->getPath()}start.php");
		}

		$this->registerLanguages();
		$this->registerViews();

		return $result;
	}

	/**
	 * Init the plugin
	 * @return void
	 * @throws InvalidParameterException
	 * @throws PluginException
	 */
	public function init() {
		$this->registerRoutes();
		$this->registerActions();
		$this->registerEntities();
		$this->registerWidgets();
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
		$filepath = "{$this->getPath()}{$filename}";

		if (!$this->canReadFile($filename)) {
			$msg = _elgg_services()->translator->translate('ElggPlugin:Exception:CannotIncludeFile',
				[$filename, $this->getID(), $this->guid, $this->getPath()]);
			throw new PluginException($msg);
		}

		try {
			$ret = Includer::includeFile($filepath);
		} catch (Exception $e) {
			$msg = _elgg_services()->translator->translate('ElggPlugin:Exception:IncludeFileThrew',
				[$filename, $this->getID(), $this->guid, $this->getPath()]);
			throw new PluginException($msg, 0, $e);
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
		if (_elgg_config()->system_cache_loaded) {
			return;
		}

		$views = _elgg_services()->views;

		// Declared views first
		$file = "{$this->getPath()}views.php";
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
		if (!$views->registerPluginViews($this->getPath(), $failed_dir)) {
			$key = 'ElggPlugin:Exception:CannotRegisterViews';
			$args = [$this->getID(), $this->guid, $failed_dir];
			$msg = _elgg_services()->translator->translate($key, $args);
			throw new PluginException($msg);
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
	 * @return void
	 */
	protected function registerActions() {
		self::addActionsFromStaticConfig($this->getStaticConfig('actions', []), $this->getPath());
	}

	/**
	 * Register a plugin's actions provided in the config file
	 *
	 * @todo   move to a static config service
	 *
	 * @param array  $spec      'actions' section of static config
	 * @param string $root_path Plugin path
	 *
	 * @return void
	 * @access private
	 * @internal
	 */
	public static function addActionsFromStaticConfig(array $spec, $root_path) {
		$actions = _elgg_services()->actions;
		$root_path = rtrim($root_path, '/\\');

		foreach ($spec as $action => $action_spec) {
			if (!is_array($action_spec)) {
				continue;
			}

			$options = [
				'access' => 'logged_in',
				'filename' => '', // assuming core action is registered
			];

			$options = array_merge($options, $action_spec);

			$filename = "$root_path/actions/{$action}.php";
			if (is_file($filename)) {
				$options['filename'] = $filename;
			}

			$actions->register($action, $options['filename'], $options['access']);
		}
	}

	/**
	 * Registers the plugin's routes provided in the plugin config file
	 *
	 * @return void
	 * @throws InvalidParameterException
	 */
	protected function registerRoutes() {
		$router = _elgg_services()->router;

		$spec = (array) $this->getStaticConfig('routes', []);

		foreach ($spec as $name => $route_spec) {
			if (!is_array($route_spec)) {
				continue;
			}

			$router->registerRoute($name, $route_spec);
		}
	}

	/**
	 * Registers the plugin's widgets provided in the plugin config file
	 *
	 * @return void
	 * @throws \InvalidParameterException
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
	 * @return void
	 */
	protected function registerLanguages() {
		$languages_path = $this->getPath() . 'languages';
		if (!is_dir($languages_path)) {
			return;
		}

		$path_only = !_elgg_services()->translator->wasLoadedFromCache();
		if ($path_only) {
			_elgg_services()->translator->registerLanguagePath($languages_path);
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

		if (is_dir($classes_path)) {
			_elgg_services()->autoloadManager->addClasses($classes_path);
		}
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
		if (empty($spec)) {
			return;
		}

		foreach ($spec as $entity) {
			if (isset($entity['type'], $entity['subtype'], $entity['class'])) {
				elgg_set_entity_class($entity['type'], $entity['subtype']);
			}
		}
	}

	/**
	 * Get an attribute, metadata or private setting value
	 *
	 * @param string $name Name of the attribute or private setting
	 *
	 * @return mixed
	 * @throws DatabaseException
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
	 * Returns this plugin's \ElggPluginManifest object
	 *
	 * @return ElggPluginManifest|null
	 */
	public function getManifest() {
		if ($this->manifest instanceof ElggPluginManifest) {
			return $this->manifest;
		}

		try {
			$package = $this->getPackage();
			if (!$package) {
				throw new PluginException('Package cannot be loaded');
			}
			$this->manifest = $package->getManifest();

			return $this->manifest;
		} catch (PluginException $e) {
			_elgg_services()->logger->warn("Failed to load manifest for plugin $this->guid. " . $e->getMessage());
			$this->errorMsg = $e->getmessage();
		}
	}

	/**
	 * Returns this plugin's \ElggPluginPackage object
	 *
	 * @return ElggPluginPackage|null
	 */
	public function getPackage() {
		if ($this->package instanceof ElggPluginPackage) {
			return $this->package;
		}

		try {
			$this->package = new ElggPluginPackage($this->getPath(), false);

			return $this->package;
		} catch (Exception $e) {
			_elgg_services()->logger->warn("Failed to load package for $this->guid. " . $e->getMessage());
			$this->errorMsg = $e->getmessage();
		}
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
		
		_elgg_services()->boot->invalidateCache();
		_elgg_services()->plugins->invalidateCache($this->getID());

		parent::invalidateCache();
	}
}
