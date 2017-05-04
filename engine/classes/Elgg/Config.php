<?php
namespace Elgg;

use Elgg\Config\EnvReader;
use Elgg\Filesystem\Directory;
use Elgg\Database\ConfigTable;
use Dotenv\Dotenv;
use Elgg\Filesystem\Directory\Local;
use ConfigurationException;

/**
 * Access to configuration values
 *
 * @since 1.10.0
 *
 * @property int           $action_time_limit
 * @property int           $action_token_timeout
 * @property bool          $allow_registration
 * @property string        $allow_user_default_access
 * @property bool          $auto_disable_plugins
 * @property int           $batch_run_time_in_secs
 * @property bool          $boot_complete
 * @property int           $boot_cache_ttl
 * @property array         $breadcrumbs
 * @property string        $cacheroot    Path of cache storage with trailing "/"
 * @property string        $dataroot     Path of data storage with trailing "/"
 * @property bool          $data_dir_override
 * @property string        $dbprefix
 * @property string        $dbencoding
 * @property string        $dbname
 * @property string        $dbuser
 * @property string        $dbhost
 * @property string        $dbpass
 * @property string        $debug
 * @property int           $default_access
 * @property int           $default_limit
 * @property array         $default_widget_info
 * @property string[]      $elgg_cron_periods
 * @property bool          $elgg_load_sync_code
 * @property array         $elgg_lazy_hover_menus
 * @property bool          $elgg_maintenance_mode
 * @property string        $elgg_root    Path of Elgg codebase with trailing "/"
 * @property string        $elgg_settings_file
 * @property bool          $enable_profiling
 * @property mixed         $embed_tab
 * @property string        $exception_include
 * @property string[]      $group
 * @property array         $group_tool_options
 * @property bool          $i18n_loaded_from_cache
 * @property array         $icon_sizes
 * @property string        $installed
 * @property bool          $installer_running
 * @property string        $language     Site language code
 * @property int           $lastcache
 * @property \ElggLogCache $log_cache
 * @property array         $libraries
 * @property bool          $memcache
 * @property array         $memcache_servers
 * @property array         $menus
 * @property int           $min_password_length
 * @property string[]      $pages
 * @property string        $path         Alias of "project_root"
 * @property string        $pluginspath
 * @property string        $plugins_path Path of project "mod/" directory
 * @property array         $profile_custom_fields
 * @property array         $profile_fields
 * @property string        $profiling_minimum_percentage
 * @property bool          $profiling_sql
 * @property array         $processed_upgrades
 * @property string        $project_root Path of composer install with trailing "/"
 * @property string[]      $registered_entities
 * @property bool          $security_disable_password_autocomplete
 * @property bool          $security_email_require_password
 * @property bool          $security_notify_admins
 * @property bool          $security_notify_user_admin
 * @property bool          $security_notify_user_ban
 * @property bool          $security_protect_cron
 * @property bool          $security_protect_upgrade
 * @property int           $simplecache_enabled
 * @property int           $simplecache_lastupdate
 * @property bool          $simplecache_minify_css
 * @property bool          $simplecache_minify_js
 * @property \ElggSite     $site
 * @property string        $sitedescription
 * @property string        $sitename
 * @property string[]      $site_custom_menu_items
 * @property string[]      $site_featured_menu_names
 * @property int           $site_guid
 * @property bool          $system_cache_enabled
 * @property bool          $system_cache_loaded
 * @property string        $url          Alias of "wwwroot"
 * @property int           $version
 * @property string        $view         Default viewtype (usually not set)
 * @property bool          $walled_garden
 * @property string        $wwwroot      Site URL
 * @property string        $x_sendfile_type
 * @property string        $x_accel_mapping
 * @property bool          $_boot_cache_hit
 * @property bool          $_elgg_autofeed
 * @property bool          $_simplecache_enabled_in_settings
 */
class Config {
	use Loggable;

	/**
	 * @var array Configuration storage
	 */
	private $values;

	/**
	 * @var array
	 */
	private $initial_values;

	/**
	 * @var bool
	 */
	private $cookies_configured = false;

	/**
	 * @var ConfigTable Do not use directly. Use getConfigTable().
	 */
	private $config_table;

	/**
	 * @var string[]
	 */
	private $alias_properties = [
		// do not create circular aliases!
		'project_root' => 'path',
		'pluginspath' => 'plugins_path',
		'url' => 'wwwroot',
	];

	/**
	 * Constructor
	 *
	 * @param array $values Initial config values from Env/settings file
	 * @internal Do not use
	 * @access private
	 */
	public function __construct(array $values = []) {
		$this->values = $values;
		$this->initial_values = $values;
	}

	/**
	 * Build a config from default settings locations
	 *
	 * @param string $settings_path Path of settings file
	 * @param bool   $try_env       If path not given, try $_ENV['ELGG_SETTINGS_FILE']
	 * @return Config
	 *
	 * @throws ConfigurationException
	 */
	public static function factory($settings_path = '', $try_env = true) {
		$reason1 = '';
		$reason2 = '';

		if ($try_env && !empty($_ENV['ELGG_SETTINGS_FILE'])) {
			$settings_path = $_ENV['ELGG_SETTINGS_FILE'];
		}

		if ($settings_path) {
			$config = self::fromFile($settings_path, $reason1);
		} else {
			$config = self::fromFile(Application::getDefaultSettingsPath(), $reason1);
			if (!$config) {
				$config = self::fromFile(Application::getLegacySettingsPath(), $reason2);
			}
		}

		if (!$config) {
			$msg = __METHOD__ . ": Reading configs failed: $reason1 $reason2";
			throw new ConfigurationException($msg);
		}

		return $config;
	}

	/**
	 * Build a config from a file
	 *
	 * @param string $path   Path of settings.php, .env, or .env.php
	 * @param string $reason Returned reason for failure
	 *
	 * @return bool|Config false on failure
	 */
	public static function fromFile($path, &$reason = '') {
		if (!is_file($path)) {
			$reason = "File $path not present.";
			return false;
		}

		if (!is_readable($path)) {
			$reason = "File $path not readable.";
			return false;
		}

		if (basename($path) === 'settings.php') {
			// legacy loading. If $CONFIG doesn't exist, remove it after the
			// settings file is read.
			$global_is_set = isset($GLOBALS['CONFIG']);

			Includer::requireFile($path);

			$config = new self(get_object_vars($GLOBALS['CONFIG']));

			if (!$global_is_set) {
				unset($GLOBALS['CONFIG']);
			}
		} else {
			$dot_env = new Dotenv(dirname($path), basename($path));
			$dot_env->load();

			$config = new self((new EnvReader())->getValues($_ENV));
		}

		$config->elgg_settings_file = $path;

		return $config;
	}

	/**
	 * Set up and validate the config values. Called when the Application is constructed.
	 *
	 * @return void
	 * @throws ConfigurationException
	 */
	public function init() {
		$project_root = Local::projectRoot()->getPath();
		$defaults = [
			'elgg_root' => Local::elggRoot()->getPath(),
			'path' => $project_root,
			'plugins_path' => "{$project_root}mod/",
			'site_guid' => 1,
			'language' => 'en',
			'default_limit' => 10,
			'boot_complete' => false,
		];

		$this->values = array_merge($defaults, $this->values);

		if (empty($this->values['dataroot'])) {
			throw new ConfigurationException('Config value "dataroot" is required.');
		}

		$trailing_slashes = [
			'elgg_root' => DIRECTORY_SEPARATOR,
			'project_root' => DIRECTORY_SEPARATOR,
			'dataroot' => DIRECTORY_SEPARATOR,
			'cacheroot' => DIRECTORY_SEPARATOR,
			'plugins_path' => DIRECTORY_SEPARATOR,
			'wwwroot' => '/',
		];
		foreach ($trailing_slashes as $key => $slash) {
			if (!empty($this->values[$key])) {
				$this->values[$key] = rtrim($this->values[$key], '\\/') . $slash;
			}
		}
	}

	/**
	 * Set an array of values
	 *
	 * @param array $values Values
	 * @return void
	 */
	public function setValues(array $values) {
		foreach ($values as $name => $value) {
			$this->__set($name, $value);
		}
	}

	/**
	 * Get all values
	 *
	 * @return array
	 */
	public function getValues() {
		$values = $this->values;
		foreach ($this->alias_properties as $alias => $real) {
			$values[$alias] = $values[$real];
		}

		return $values;
	}

	/**
	 * Set up and return the cookie configuration array resolved from settings
	 *
	 * @return array
	 */
	public function getCookieConfig() {
		if ($this->cookies_configured) {
			return $this->cookies;
		}

		$cookies = $this->cookies;
		if (!is_array($cookies)) {
			$cookies = [];
		}

		if (!isset($cookies['session'])) {
			$cookies['session'] = [];
		}
		$session_defaults = session_get_cookie_params();
		$session_defaults['name'] = 'Elgg';
		$cookies['session'] = array_merge($session_defaults, $cookies['session']);
		if (!isset($cookies['remember_me'])) {
			$cookies['remember_me'] = [];
		}
		$session_defaults['name'] = 'elggperm';
		$session_defaults['expire'] = strtotime("+30 days");
		$cookies['remember_me'] = array_merge($session_defaults, $cookies['remember_me']);

		$this->cookies = $cookies;
		$this->cookies_configured = true;

		return $cookies;
	}

	/**
	 * Get an Elgg configuration value if it's been set or loaded during the boot process.
	 *
	 * Before \Elgg\BootService::boot, values from the database will not be present.
	 *
	 * @param string $name Name
	 *
	 * @return mixed null if does not exist
	 */
	public function __get($name) {
		if (isset($this->values[$name])) {
			return $this->values[$name];
		}

		if (isset($this->alias_properties[$name])) {
			return $this->__get($this->alias_properties[$name]);
		}

		return null;
	}

	/**
	 * Get a value set at construction time
	 *
	 * @param string $name Name
	 * @return mixed null = not set
	 */
	public function getInitialValue($name) {
		return isset($this->initial_values[$name]) ? $this->initial_values[$name] : null;
	}

	/**
	 * Set an Elgg configuration value
	 *
	 * @warning This does not persist the configuration setting. Use elgg_save_config()
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 * @return void
	 */
	public function __set($name, $value) {
		if (isset($this->alias_properties[$name])) {
			$name = $this->alias_properties[$name];
		}
		$this->values[$name] = $value;
	}

	/**
	 * Handle isset()
	 *
	 * @param string $name Name
	 * @return bool
	 */
	public function __isset($name) {
		return $this->__get($name) !== null;
	}

	/**
	 * Handle unset()
	 *
	 * @param string $name Name
	 * @return void
	 */
	public function __unset($name) {
		unset($this->values[$name]);
	}

	/**
	 * Save a configuration setting
	 *
	 * @param string $name  Name (cannot be greater than 255 characters)
	 * @param mixed  $value Value
	 *
	 * @return bool
	 */
	public function save($name, $value) {
		if (strlen($name) > 255) {
			if ($this->logger) {
				$this->logger->error("The name length for configuration variables cannot be greater than 255");
			}
			return false;
		}

		$result = $this->getConfigTable()->set($name, $value);

		$this->__set($name, $value);
	
		return $result;
	}

	/**
	 * Removes a configuration setting
	 *
	 * @param string $name Configuration name
	 *
	 * @return bool
	 */
	public function remove($name) {
		$result = $this->getConfigTable()->remove($name);

		unset($this->values[$name]);
	
		return $result;
	}

	/**
	 * Set the config table service (must be set)
	 *
	 * This is a necessary evil until we refactor so that the service provider has no dependencies.
	 *
	 * @param ConfigTable $table
	 * @return void
	 *
	 * @access private
	 * @internal
	 */
	public function setConfigTable(ConfigTable $table) {
		$this->config_table = $table;
	}

	/**
	 * Get the core entity types
	 *
	 * @return string[]
	 */
	public static function getEntityTypes() {
		return ['group', 'object', 'site', 'user'];
	}

	/**
	 * Get the config table API
	 *
	 * @return ConfigTable
	 */
	private function getConfigTable() {
		if (!$this->config_table) {
			if (!function_exists('_elgg_services')) {
				throw new \RuntimeException('setConfigTable() must be called before using API that' .
					' uses the database.');
			}

			$this->config_table = _elgg_services()->configTable;
		}

		return $this->config_table;
	}
}
