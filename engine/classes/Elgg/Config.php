<?php
namespace Elgg;

use Elgg\Config\DatarootSettingMigrator;
use Elgg\Config\WwwrootSettingMigrator;
use Elgg\Database\ConfigTable;
use ConfigurationException;
use Elgg\Project\Paths;

/**
 * Access to configuration values
 *
 * @since 1.10.0
 *
 * @property int           $action_time_limit						Maximum php execution time for actions (in seconds)
 * @property int           $action_token_timeout
 * @property bool          $allow_phpinfo							Allow access tot PHPInfo
 * @property bool          $allow_registration						Is registration enabled
 * @property string        $allow_user_default_access				Are users allowed to set their own default access level
 * @property string        $allowed_languages						Comma seperated string of admin allowed languages
 * @property string        $assetroot            					Path of asset (views) simplecache with trailing "/"
 * @property bool          $auto_disable_plugins					Are unbootable plugins automatically disabled
 * @property int           $batch_run_time_in_secs					Max time for a single upgrade loop
 * @property-read int      $bootdata_plugin_settings_limit			Max amount of plugin settings to determine if plugin will be cached
 * @property bool          $boot_complete
 * @property int           $boot_cache_ttl
 * @property array         $breadcrumbs
 * @property string        $cacheroot            					Path of cache storage with trailing "/"
 * @property bool          $can_change_username						Is user allowed to change the username
 * @property bool          $comment_box_collapses					Determines if the comment box collapses after the first comment
 * @property bool          $comments_latest_first					Determines if the default order of comments is latest first
 * @property array         $css_compiler_options 					Options passed to CssCrush during CSS compilation
 * @property string        $dataroot             					Path of data storage with trailing "/"
 * @property bool          $data_dir_override
 * @property string        $date_format          					Preferred PHP date format
 * @property string        $date_format_datepicker 					Preferred jQuery datepicker date format
 * @property array         $db
 * @property string        $dbencoding
 * @property string        $dbname
 * @property string        $dbuser
 * @property string        $dbhost
 * @property int           $dbport
 * @property string        $dbpass
 * @property string        $dbprefix
 * @property bool          $db_disable_query_cache
 * @property string        $debug
 * @property int           $default_access							Default access
 * @property int           $default_limit							The default "limit" used in listings and queries
 * @property array         $default_widget_info
 * @property bool          $disable_rss 							Is RSS disabled
 * @property bool          $elgg_config_locks 						The application will lock some settings (default true)
 * @property bool          $elgg_load_sync_code
 * @property bool          $elgg_maintenance_mode
 * @property string        $elgg_settings_file
 * @property bool          $elgg_config_set_secret
 * @property bool          $enable_profiling
 * @property string        $emailer_transport                       This is an override for Elgg's default email handling transport (default sendmail)
 * @property array         $emailer_smtp_settings                   This configures SMTP if $emailer_transport is set to "smtp"
 * @property mixed         $embed_tab
 * @property string        $exception_include						This is an optional script used to override Elgg's default handling of uncaught exceptions.
 * @property string[]      $group
 * @property string[]      $http_request_trusted_proxy_ips			When Elgg is behind a loadbalancer/proxy this can contain IP adresses to allow access to better client information
 * @property int           $http_request_trusted_proxy_headers		When Elgg is behind a loadbalancer/proxy this can contain a bitwise string of allowed headers for better client information
 * @property bool          $i18n_loaded_from_cache
 * @property array         $icon_sizes
 * @property string        $image_processor
 * @property string        $installed 								Is the site fully installed?
 * @property bool          $installer_running
 * @property string        $language                   				Site language code
 * @property string[]      $language_to_locale_mapping 				A language to locale mapping (eg. 'en' => ['en_US'] or 'nl' => ['nl_NL'])
 * @property int           $lastcache								The timestamp the cache was last invalidated
 * @property string        $localcacheroot            				Path of local cache storage with trailing "/"
 * @property bool          $memcache
 * @property string        $memcache_namespace_prefix
 * @property array         $memcache_servers
 * @property array         $menus
 * @property int           $min_password_length                     The minimal length of a password
 * @property int           $min_password_lower                      The minimal number of lower case characters in a password
 * @property int           $min_password_upper                      The minimal number of upper case characters in a password
 * @property int           $min_password_number                     The minimal number of numbers in a password
 * @property int           $min_password_special                    The minimal number of special characters in a password
 * @property int           $minusername                             The minimal length of a username
 * @property string[]      $pages
 * @property-read string   $path         							Path of composer install with trailing "/"
 * @property-read string   $pluginspath  							Alias of plugins_path
 * @property-read string   $plugins_path 							Path of project "mod/" directory where the plugins are stored
 * @property array         $profile_custom_fields
 * @property array         $profile_fields
 * @property string        $profiling_minimum_percentage
 * @property bool          $profiling_sql
 * @property array         $processed_upgrades
 * @property bool          $redis
 * @property array         $redis_options
 * @property array         $redis_servers
 * @property string[]      $registered_entities						A list of registered entities and subtypes. Used in search.
 * @property bool          $remove_branding 						Is Elgg branding disabled
 * @property bool          $require_admin_validation
 * @property bool          $security_disable_password_autocomplete
 * @property bool          $security_email_require_password
 * @property bool          $security_notify_admins
 * @property bool          $security_notify_user_admin
 * @property bool          $security_notify_user_ban
 * @property bool          $security_protect_cron
 * @property bool          $security_protect_upgrade
 * @property array		   $servicehandler							Holds the service handlers as registered by the webservice plugin
 * @property string        $seeder_local_image_folder 				Path to a local folder containing images used for seeding
 * @property bool          $simplecache_enabled						Is simplecache enabled?
 * @property bool          $simplecache_minify_css
 * @property bool          $simplecache_minify_js
 * @property \ElggSite     $site 									The site entity
 * @property string        $sitedescription							The site description
 * @property string        $sitename 								The name of the site
 * @property string[]      $site_custom_menu_items
 * @property string[]      $site_featured_menu_names
 * @property-read int      $site_guid 								The guid of the site object
 * @property bool          $system_cache_enabled					Is the system cache enabled?
 * @property bool          $system_cache_loaded
 * @property bool          $testing_mode  							Is the current application running (PHPUnit) tests
 * @property string        $time_format  							Preferred PHP time format
 * @property string        $url          							Alias of "wwwroot"
 * @property int           $version
 * @property string        $view         							Default viewtype (usually not set)
 * @property bool          $walled_garden							Is current site in walled garden mode?
 * @property string        $wwwroot      							Site URL
 * @property string        $x_sendfile_type
 * @property string        $x_accel_mapping
 * @property bool          $_boot_cache_hit
 * @property bool          $_elgg_autofeed
 *
 * @property bool          $_service_boot_complete
 * @property bool          $_plugins_boot_complete
 * @property bool          $_application_boot_complete
 *
 * @internal
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
	 * @var array
	 */
	private $cookies = [];

	/**
	 * @var ConfigTable Do not use directly. Use getConfigTable().
	 */
	private $config_table;

	/**
	 * @var array
	 */
	private $locked = [];

	/**
	 * @var string
	 */
	private $settings_path;
	
	/**
	 * Holds the set of default values
	 *
	 * @var array
	 */
	protected $config_defaults = [
		'comment_box_collapses' => true,
		'comments_latest_first' => true,
		'testing_mode' => false,
	];

	/**
	 * Constructor
	 *
	 * @param array $values Initial config values from Env/settings file
	 */
	public function __construct(array $values = []) {
		$values = array_merge($this->config_defaults, $values);
		$this->values = $values;

		// Don't keep copies of these in case config gets dumped
		$sensitive_props = [
			'__site_secret__',
			'db',
			'dbhost',
			'dbport',
			'dbuser',
			'dbpass',
			'dbname',
			'profiler_secret_get_var'
		];
		foreach ($sensitive_props as $name) {
			unset($values[$name]);
		}
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

		$settings_path = self::resolvePath($settings_path, $try_env);

		$config = self::fromFile($settings_path, $reason1);

		if (!$config) {
			$msg = __METHOD__ . ": Reading configs failed: $reason1";
			throw new ConfigurationException($msg);
		}

		$config->settings_path = $settings_path;

		return $config;
	}

	/**
	 * Build a config from a file
	 *
	 * @param string $path   Path of settings.php
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

		// legacy loading. If $CONFIG doesn't exist, remove it after the
		// settings file is read.
		if (isset($GLOBALS['CONFIG'])) {
			// don't overwrite it
			$global = $GLOBALS['CONFIG'];
			unset($GLOBALS['CONFIG']);
		} else {
			$global = null;
		}

		Includer::requireFile($path);

		$get_db = function() {
			// try to migrate settings to the file
			$db_conf = new \Elgg\Database\DbConfig($GLOBALS['CONFIG']);
			$cache = new \Elgg\Cache\QueryCache(50, true);
			return new Database($db_conf, $cache);
		};

		if (empty($GLOBALS['CONFIG']->dataroot)) {
			$dataroot = (new DatarootSettingMigrator($get_db(), $path))->migrate();
			if (!isset($dataroot)) {
				$reason = 'The Elgg settings file is missing $CONFIG->dataroot.';
				return false;
			}

			$GLOBALS['CONFIG']->dataroot = $dataroot;

			// just try this one time to migrate wwwroot
			if (!isset($GLOBALS['CONFIG']->wwwroot)) {
				$wwwroot = (new WwwrootSettingMigrator($get_db(), $path))->migrate();
				if (isset($wwwroot)) {
					$GLOBALS['CONFIG']->wwwroot = $wwwroot;
				}
			}
		}

		$config = new self(get_object_vars($GLOBALS['CONFIG']));

		if ($global !== null) {
			// restore
			$GLOBALS['CONFIG'] = $global;
		} else {
			unset($GLOBALS['CONFIG']);
		}

		if ($config->{'X-Sendfile-Type'}) {
			$config->{'x_sendfile_type'} = $config->{'X-Sendfile-Type'};
			unset($config->{'X-Sendfile-Type'});
		}
		if ($config->{'X-Accel-Mapping'}) {
			$config->{'x_accel_mapping'} = $config->{'X-Accel-Mapping'};
			unset($config->{'X-Accel-Mapping'});
		}

		$config->elgg_settings_file = $path;
		$config->lock('elgg_settings_file');

		return $config;
	}

	/**
	 * Resolve settings path
	 *
	 * @param string $settings_path Path of settings file
	 * @param bool   $try_env       If path not given, try $_ENV['ELGG_SETTINGS_FILE']
	 * @return string
	 */
	public static function resolvePath($settings_path = '', $try_env = true) {
		if (!$settings_path) {
			if ($try_env && !empty($_ENV['ELGG_SETTINGS_FILE'])) {
				$settings_path = $_ENV['ELGG_SETTINGS_FILE'];
			} else if (!$settings_path) {
				$settings_path = Paths::settingsFile(Paths::SETTINGS_PHP);
			}
		}

		return \Elgg\Project\Paths::sanitize($settings_path, false);
	}

	/**
	 * Set an array of values
	 *
	 * @param array $values Values
	 * @return void
	 */
	public function mergeValues(array $values) {
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
		return $this->values;
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

		$cookies = [];
		if ($this->hasInitialValue('cookies')) {
			$cookies = $this->getInitialValue('cookies');
		}

		// session cookie config
		if (!isset($cookies['session'])) {
			$cookies['session'] = [];
		}
		$session_defaults = session_get_cookie_params();
		$session_defaults['name'] = 'Elgg';
		$cookies['session'] = array_merge($session_defaults, $cookies['session']);
		
		// remember me cookie config
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
		switch ($name) {
			case 'group_tool_options':
				elgg_deprecated_notice("'$name' config option is no longer in use. Use elgg()->group_tools->all()", '3.0');
				return elgg()->group_tools->all();
			case 'simplecache_lastupdate':
				elgg_deprecated_notice("'$name' config option is deprecated. Use 'lastcache'", '3.3');
				break;
		}

		if (isset($this->values[$name])) {
			return $this->values[$name];
		}

		return null;
	}

	/**
	 * Test if we have a set value
	 *
	 * @param string $name Name
	 *
	 * @return bool
	 */
	public function hasValue($name) {
		return isset($this->values[$name]);
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
	 * Was a value available at construction time? (From settings.php)
	 *
	 * @param string $name Name
	 *
	 * @return bool
	 */
	public function hasInitialValue($name) {
		return isset($this->initial_values[$name]);
	}

	/**
	 * Make a value read-only
	 *
	 * @param string $name Name
	 * @return void
	 */
	public function lock($name) {
		$this->locked[$name] = true;
	}

	/**
	 * Is this value locked?
	 *
	 * @param string $name Name
	 *
	 * @return bool
	 */
	public function isLocked($name) {
		return isset($this->locked[$name]);
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
		if ($this->wasWarnedLocked($name)) {
			return;
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
		if ($this->wasWarnedLocked($name)) {
			return;
		}

		unset($this->values[$name]);
	}

	/**
	 * Save a configuration setting to the database
	 *
	 * @param string $name  Name (cannot be greater than 255 characters)
	 * @param mixed  $value Value
	 *
	 * @return bool
	 */
	public function save($name, $value) {
		if ($this->wasWarnedLocked($name)) {
			return false;
		}

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
	 * Removes a configuration setting from the database
	 *
	 * @param string $name Configuration name
	 *
	 * @return bool
	 */
	public function remove($name) {
		if ($this->wasWarnedLocked($name)) {
			return false;
		}

		$result = $this->getConfigTable()->remove($name);

		unset($this->values[$name]);
	
		return $result;
	}

	/**
	 * Log a read-only warning if the name is read-only
	 *
	 * @param string $name Name
	 * @return bool
	 */
	private function wasWarnedLocked($name) {
		if (!isset($this->locked[$name])) {
			return false;
		}

		if ($this->logger) {
			$this->logger->warning("The property $name is read-only.");
		}
		return true;
	}

	/**
	 * Set the config table service (must be set)
	 *
	 * This is a necessary evil until we refactor so that the service provider has no dependencies.
	 *
	 * @param ConfigTable $table the config table service
	 * @return void
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
