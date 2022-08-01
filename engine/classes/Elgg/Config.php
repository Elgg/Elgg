<?php

namespace Elgg;

use Elgg\Exceptions\ConfigurationException;
use Elgg\Project\Paths;
use Elgg\Traits\Loggable;

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
 * @property int           $authentication_failures_lifetime        Number of seconds before an authentication failure expires
 * @property int           $authentication_failures_limit           Number of allowed authentication failures
 * @property bool          $auto_disable_plugins					Are unbootable plugins automatically disabled
 * @property int           $batch_run_time_in_secs					Max time for a single upgrade loop
 * @property int           $bootdata_plugin_settings_limit			Max amount of plugin settings to determine if plugin will be cached
 * @property int           $boot_cache_ttl                          Time to live for boot cache in seconds
 * @property array         $breadcrumbs
 * @property string        $cacheroot            					Path of cache storage with trailing "/"
 * @property bool          $can_change_username						Is user allowed to change the username
 * @property bool          $class_loader_verify_file_existence		Determines if the class loader checks for file existence when loading files from the class map
 * @property bool          $comment_box_collapses					Determines if the comment box collapses after the first comment
 * @property bool          $comments_group_only					    Are comments on group content only allowed for group members
 * @property bool          $comments_latest_first					Determines if the default order of comments is latest first
 * @property int           $comments_max_depth						Maximum level of threaded comments (0 means disabled)
 * @property int           $comments_per_page						Number of comments per page
 * @property array         $css_compiler_options 					Options passed to CssCrush during CSS compilation
 * @property string        $dataroot             					Path of data storage with trailing "/"
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
 * @property int           $db_query_cache_limit                    Limit for the query cache
 * @property string        $debug
 * @property int           $default_access							Default access
 * @property int           $default_limit							The default "limit" used in listings and queries
 * @property bool          $disable_rss 							Is RSS disabled
 * @property bool          $elgg_maintenance_mode                   Flag if maintenance mode is enabled
 * @property-read string   $elgg_settings_file                      Location of the settings file used to initialize the config
 * @property bool          $email_html_part                         Determines if email has a html part
 * @property string        $email_html_part_images                  How to deal with images in html part of email
 * @property int           $email_subject_limit                     The length limit for email subjects, defaults to 998 as described in http://www.faqs.org/rfcs/rfc2822.html
 * @property bool          $enable_delayed_email                    Is the delivery method 'delayed_email' enabled
 * @property bool          $enable_profiling
 * @property string        $emailer_transport                       This is an override for Elgg's default email handling transport (default sendmail)
 * @property array         $emailer_sendmail_settings               This configures SendMail if $emailer_transport is set to "sendmail" or default
 * @property array         $emailer_smtp_settings                   This configures SMTP if $emailer_transport is set to "smtp"
 * @property mixed         $embed_tab
 * @property string        $exception_include						This is an optional script used to override Elgg's default handling of uncaught exceptions.
 * @property int           $friendly_time_number_of_days            Number of days after which timestamps will no longer be presented in a friendly format (x hours ago) but in a full date
 * @property string[]      $http_request_trusted_proxy_ips			When Elgg is behind a loadbalancer/proxy this can contain IP adresses to allow access to better client information
 * @property int           $http_request_trusted_proxy_headers		When Elgg is behind a loadbalancer/proxy this can contain a bitwise string of allowed headers for better client information
 * @property array         $icon_sizes
 * @property string        $image_processor
 * @property int           $installed 								Set during installation to the timestamp of installation
 * @property bool          $installer_running
 * @property string        $language                   				Site language code
 * @property string[]      $language_to_locale_mapping 				A language to locale mapping (eg. 'en' => ['en_US'] or 'nl' => ['nl_NL'])
 * @property bool          $language_detect_from_browser            Control if language can be detected from browser
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
 * @property int           $notifications_max_runtime               The max runtime for the notification queue processing in seconds since the start of the cron interval
 * @property int           $notifications_queue_delay               Number of seconds to delay the processing of the notifications queue
 * @property string        $pagination_behaviour                    Behaviour of pagination in lists
 * @property-read string   $plugins_path 							Path of project "mod/" directory where the plugins are stored
 * @property array         $profile_custom_fields
 * @property string        $profiling_minimum_percentage
 * @property bool          $profiling_sql
 * @property array         $proxy                                   Contains proxy related settings
 * @property bool          $redis
 * @property array         $redis_options
 * @property array         $redis_servers
 * @property bool          $remove_branding 						Is Elgg branding disabled
 * @property int           $remove_unvalidated_users_days			The number of days after which unvalidated users will be removed
 * @property bool          $require_admin_validation
 * @property bool          $security_disable_password_autocomplete
 * @property bool          $security_email_require_password
 * @property bool          $security_notify_admins
 * @property bool          $security_notify_user_admin
 * @property bool          $security_notify_user_ban
 * @property bool          $security_protect_cron
 * @property bool          $security_protect_upgrade
 * @property string        $seeder_local_image_folder 				Path to a local folder containing images used for seeding
 * @property bool          $session_bound_entity_icons 				Are the URLs to entity icons session bound (unique per user)
 * @property bool          $simplecache_enabled						Is simplecache enabled?
 * @property bool          $simplecache_minify_css
 * @property bool          $simplecache_minify_js
 * @property \ElggSite     $site 									The site entity
 * @property string[]      $site_custom_menu_items
 * @property string[]      $site_featured_menu_names
 * @property bool          $subresource_integrity_enabled			Should subresources (js/css) get integrity information
 * @property bool          $system_cache_enabled					Is the system cache enabled?
 * @property bool          $system_cache_loaded
 * @property bool          $testing_mode  							Is the current application running (PHPUnit) tests
 * @property string        $time_format  							Preferred PHP time format
 * @property string        $view         							Default viewtype (usually not set)
 * @property bool          $walled_garden							Is current site in walled garden mode?
 * @property string        $who_can_change_language					Who can change the language of a user
 * @property bool          $webp_enabled                            Are webp icons allowed
 * @property string        $wwwroot      							Site URL
 * @property string        $x_sendfile_type
 * @property string        $x_accel_mapping
 * @property bool          $_boot_cache_hit
 * @property bool          $_elgg_autofeed
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
	 * The following values can only be set once
	 *
	 * @var array
	 */
	protected $locked_values = [
		'assetroot',
		'cacheroot',
		'dataroot',
		'elgg_settings_file',
		'installed',
		'path',
		'plugins_path',
		'pluginspath',
		'site_guid',
		'url',
		'wwwroot',
	];

	/**
	 * @var array
	 */
	protected $deprecated = [
		'elgg_settings_file' => '4.3',
		'path' => '4.3',
		'pluginspath' => '4.3',
		'site_guid' => '4.3',
		'sitedescription' => '4.3',
		'sitename' => '4.3',
		'url' => '4.3',
	];
	
	/**
	 * Holds the set of default values
	 *
	 * @var array
	 */
	protected $config_defaults = [
		'allow_phpinfo' => false,
		'authentication_failures_lifetime' => 600,
		'authentication_failures_limit' => 5,
		'auto_disable_plugins' => true,
		'batch_run_time_in_secs' => 4,
		'boot_cache_ttl' => 3600,
		'bootdata_plugin_settings_limit' => 40,
		'can_change_username' => false,
		'class_loader_verify_file_existence' => true,
		'comment_box_collapses' => true,
		'comments_group_only' => true,
		'comments_latest_first' => true,
		'comments_max_depth' => 0,
		'comments_per_page' => 25,
		'db_query_cache_limit' => 50,
		'default_limit' => 10,
		'elgg_maintenance_mode' => false,
		'email_html_part' => true,
		'email_html_part_images' => 'no',
		'email_subject_limit' => 998,
		'enable_delayed_email' => true,
		'friendly_time_number_of_days' => 30,
		'icon_sizes' => [
			'topbar' => ['w' => 16, 'h' => 16, 'square' => true, 'upscale' => true],
			'tiny' => ['w' => 25, 'h' => 25, 'square' => true, 'upscale' => true],
			'small' => ['w' => 40, 'h' => 40, 'square' => true, 'upscale' => true],
			'medium' => ['w' => 100, 'h' => 100, 'square' => true, 'upscale' => true],
			'large' => ['w' => 200, 'h' => 200, 'square' => true, 'upscale' => true],
			'master' => ['w' => 10240, 'h' => 10240, 'square' => false, 'upscale' => false, 'crop' => false],
		],
		'language' => 'en',
		'language_detect_from_browser' => true,
		'lastcache' => 0,
		'message_delay' => 6,
		'min_password_length' => 6,
		'minusername' => 4,
		'notifications_max_runtime' => 45,
		'notifications_queue_delay' => 0,
		'pagination_behaviour' => 'ajax-replace',
		'security_email_require_confirmation' => true,
		'security_email_require_password' => true,
		'security_notify_admins' => true,
		'security_notify_user_password' => true,
		'security_protect_upgrade' => true,
		'session_bound_entity_icons' => false,
		'simplecache_enabled' => false,
		'site_guid' => 1, // deprecated
		'subresource_integrity_enabled' => false,
		'system_cache_enabled' => false,
		'testing_mode' => false,
		'webp_enabled' => true,
		'who_can_change_language' => 'everyone',
	];
	
	/**
	 * The path properties will be sanitized when set
	 *
	 * @var array
	 */
	protected $path_properties = [
		'dataroot',
		'cacheroot',
		'assetroot',
	];
	
	/**
	 * Core entity types
	 *
	 * @var array
	 */
	const ENTITY_TYPES = ['group', 'object', 'site', 'user'];

	/**
	 * Sensitive properties that should not be visible
	 *
	 * @var array
	 */
	const SENSITIVE_PROPERTIES = [
		'__site_secret__',
		'db',
		'dbhost',
		'dbport',
		'dbuser',
		'dbpass',
		'dbname',
	];
	
	/**
	 * Constructor
	 *
	 * @param array $values Initial config values from Env/settings file
	 */
	public function __construct(array $values = []) {
		$this->saveInitialValues($values);
		
		$this->values = array_merge($this->config_defaults, $values);
		
		// set cookie values for session and remember me
		$this->getCookieConfig();
	}
	
	/**
	 * Stores the inital values
	 *
	 * @param array $values The initial values
	 *
	 * @return void
	 */
	protected function saveInitialValues(array $values): void {
		// Don't keep copies of these in case config gets dumped
		foreach (self::SENSITIVE_PROPERTIES as $name) {
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
			throw new ConfigurationException(__METHOD__ . ": Reading configs failed: $reason1");
		}

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

		if (empty($GLOBALS['CONFIG']->dataroot)) {
			$reason = 'The Elgg settings file is missing $CONFIG->dataroot.';
			return false;
		}

		if (empty($GLOBALS['CONFIG']->wwwroot)) {
			$reason = 'The Elgg settings file is missing $CONFIG->wwwroot.';
			return false;
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
	 *
	 * @deprecated 4.3
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

		if (array_key_exists($name, $this->deprecated)) {
			elgg_deprecated_notice("Using '{$name}' from config has been deprecated", $this->deprecated[$name]);
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
		return $this->initial_values[$name] ?? null;
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
	 * @deprecated 4.3
	 */
	public function lock($name) {
		elgg_deprecated_notice(__METHOD__ . 'has been deprecated. It is no longer possible to lock config values.', '4.3');
	}

	/**
	 * Is this value locked?
	 *
	 * @param string $name Name
	 *
	 * @return bool
	 */
	public function isLocked($name) {
		$testing = $this->values['testing_mode'] ?? false;
		return !$testing && in_array($name, $this->locked_values) && $this->hasValue($name);
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
		
		if (in_array($name, $this->path_properties)) {
			$value = Paths::sanitize($value);
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
			$this->getLogger()->error("The name length for configuration variables cannot be greater than 255");
			return false;
		}

		if ($value === null) {
			// don't save null values
			return $this->remove($name);
		}
		
		$result = _elgg_services()->configTable->set($name, $value);

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

		$result = _elgg_services()->configTable->remove($name);

		unset($this->values[$name]);
	
		return $result;
	}

	/**
	 * Log a read-only warning if the name is read-only
	 *
	 * @param string $name Name
	 * @return bool
	 */
	protected function wasWarnedLocked($name): bool {
		if (!$this->isLocked($name)) {
			return false;
		}
		
		$this->getLogger()->warning("The property {$name} is read-only.");

		return true;
	}
}
