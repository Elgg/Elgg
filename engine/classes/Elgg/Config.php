<?php
namespace Elgg;

use Elgg\Filesystem\Directory;
use Elgg\Database\ConfigTable;
use Dotenv\Dotenv;

/**
 * Access to configuration values
 *
 * @since 1.10.0
 */
class Config implements Services\Config {
	/**
	 * Configuration storage. Is usually reference to global $CONFIG
	 *
	 * @var \stdClass
	 */
	private $config;

	/**
	 * @var bool
	 */
	private $settings_loaded = false;

	/**
	 * @var bool
	 */
	private $cookies_configured = false;

	/**
	 * @var ConfigTable Do not use directly. Use getConfigTable().
	 */
	private $config_table;

	/**
	 * Constructor
	 *
	 * @internal Access this object via Elgg\Application::$config
	 *
	 * @param \stdClass $config     Elgg's $CONFIG object
	 * @param bool      $set_global Copy the config object to global $CONFIG
	 */
	public function __construct(\stdClass $config = null, $set_global = true) {
		if (!$config) {
			$config = new \stdClass();
		}
		$this->config = $config;
		$this->config->path = Directory\Local::root()->getPath('/');

		if ($set_global) {
			/**
			 * Configuration values.
			 *
			 * The $CONFIG global contains configuration values required
			 * for running Elgg as defined in the .env.php file.
			 *
			 * Plugin authors are encouraged to use elgg_get_config() instead of accessing
			 * the global directly.
			 *
			 * @see elgg_get_config()
			 * @see engine/.env.php
			 * @global \stdClass $CONFIG
			 */
			global $CONFIG;
			$CONFIG = $config;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSiteUrl() {
		return $this->config->wwwroot;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPluginsPath() {
		return $this->config->pluginspath;
	}

	/**
	 * Set up and return the cookie configuration array resolved from settings
	 *
	 * @return array
	 */
	public function getCookieConfig() {
		$c = $this->config;

		if ($this->cookies_configured) {
			return $c->cookies;
		}

		$this->loadSettingsFile();

		// set cookie values for session and remember me
		if (!isset($c->cookies)) {
			$c->cookies = [];
		}
		if (!isset($c->cookies['session'])) {
			$c->cookies['session'] = [];
		}
		$session_defaults = session_get_cookie_params();
		$session_defaults['name'] = 'Elgg';
		$c->cookies['session'] = array_merge($session_defaults, $c->cookies['session']);
		if (!isset($c->cookies['remember_me'])) {
			$c->cookies['remember_me'] = [];
		}
		$session_defaults['name'] = 'elggperm';
		$session_defaults['expire'] = strtotime("+30 days");
		$c->cookies['remember_me'] = array_merge($session_defaults, $c->cookies['remember_me']);

		$this->cookies_configured = true;

		return $c->cookies;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDataPath() {
		$this->loadSettingsFile();
		return $this->config->dataroot;
	}

	/**
	 * Get the cache directory path for this installation
	 *
	 * If not set in settings, the data path will be returned.
	 *
	 * @return string
	 */
	public function getCachePath() {
		$this->loadSettingsFile();
		return $this->config->cacheroot;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($name, $default = null) {
		return isset($this->config->{$name}) ? $this->config->{$name} : $default;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set($name, $value) {
		$this->config->$name = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save($name, $value) {
		if (strlen($name) > 255) {
			_elgg_services()->logger->error("The name length for configuration variables cannot be greater than 255");
			return false;
		}

		$result = $this->getConfigTable()->set($name, $value);

		$this->set($name, $value);
	
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function remove($name) {
		$result = $this->getConfigTable()->remove($name);

		unset($this->config->$name);
	
		return $result;
	}

	/**
	 * Get settings file path
	 *
	 * @return string
	 * @array private
	 */
	public function getSettingsPath() {
		return Directory\Local::root()->getPath('elgg-config/.env.php');
	}

	/**
	 * {@inheritdoc}
	 */
	public function loadSettingsFile() {
		if ($this->settings_loaded) {
			return;
		}

		if (isset($this->config->Config_file)) {
			if ($this->config->Config_file === false) {
				$this->settings_loaded = true;
				return;
			}
			$path = $this->config->Config_file;
		} else {
			$path = Directory\Local::root()->getPath('elgg-config/.env.php');
		}

		// No settings means a fresh install
		if (!is_file($path)) {
			if ($this->get('installer_running')) {
				$this->settings_loaded = true;
				return;
			}

			header("Location: install.php");
			exit;
		}

		if (!is_readable($path)) {
			echo "The Elgg settings file exists but the web server doesn't have read permission to it.";
			exit;
		}

		// we assume settings is going to write to CONFIG, but we may need to copy its values
		// into our local config
		global $CONFIG;

		$global_is_bound = false;
		if (isset($CONFIG)) {
			if ($CONFIG === $this->config) {
				$global_is_bound = true;
			}
		} else {
			$CONFIG = new \stdClass();
		}

		$dotenv = new Dotenv(dirname($path), basename($path));
		$dotenv->load();

		$dotenv->required([
			'ELGG_DATAROOT',
			'ELGG_DBPREFIX',
			'ELGG_DBUSER',
			'ELGG_DBPASS',
			'ELGG_DBNAME',
			'ELGG_DBHOST',
		]);

		foreach ($this->readEnv() as $key => $value) {
			$CONFIG->{$key} = $value;
		}

		if (php_sapi_name() === 'cli-server' && !empty($CONFIG->wwwroot_cli_server)) {
			// override wwwroot from settings file
			$CONFIG->wwwroot = $CONFIG->wwwroot_cli_server;
		}

		if (empty($CONFIG->dataroot)) {
			echo 'The Elgg settings file is missing $CONFIG->dataroot.';
			exit;
		}

		// normalize commonly needed values
		$CONFIG->dataroot = rtrim($CONFIG->dataroot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

		$CONFIG->_simplecache_enabled_in_settings = isset($CONFIG->simplecache_enabled);

		if (empty($CONFIG->cacheroot)) {
			$CONFIG->cacheroot = $CONFIG->dataroot;
		} else {
			$CONFIG->cacheroot = rtrim($CONFIG->cacheroot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		}

		if (!$global_is_bound) {
			// must manually copy settings into our storage
			foreach ($CONFIG as $key => $value) {
				$this->config->{$key} = $value;
			}
		}

		$this->settings_loaded = true;
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
	 * Read config values from $_ENV
	 *
	 * @return array
	 */
	public function readEnv() {

		$config = [];

		$casters = [
			'memcache' => 'boolval',
			'simplecache_enabled' => 'boolval',
			'broken_mta' => 'boolval',
			'db_disable_query_cache' => 'boolval',
			'auto_disable_plugins' => 'boolval',
			'enable_profiling' => 'boolval',
			'profiling_sql' => 'boolval',
			'boot_cache_ttl' => 'intval',
			'min_password_length' => 'intval',
			'action_time_limit' => 'intval',
		];

		// ELGG_SOMETHING becomes $config['something']
		foreach ($_ENV as $key => $value) {
			if (0 !== strpos($key, 'ELGG_')) {
				continue;
			}

			$new_key = strtolower(substr($key, 5));

			if (isset($casters[$new_key])) {
				$new_val = $casters[$new_key]($value);
			} else {
				$new_val = $value;
			}

			$config[$new_key] = $new_val;
		}

		// must come before cookies because we use strtotime()
		if (isset($config['default_tz'])) {
			date_default_timezone_set($config['default_tz']);
		} else {
			date_default_timezone_set('UTC');
		}
		unset($config['default_tz']);

		// cookies
		foreach (['', 'remember_'] as $prefix) {
			$key = ($prefix === '') ? 'cookies' : 'remember_me';

			if (isset($config["{$prefix}cookie_defaults_source"])
				&& ($config["{$prefix}cookie_defaults_source"] === 'session_get_cookie_params'))
			{
				$config[$key]['session'] = session_get_cookie_params();
			}
			if (isset($config["{$prefix}cookie_name"])) {
				$config[$key]['session']['name'] = $config["{$prefix}cookie_name"];
			}
			if (isset($config["{$prefix}cookie_domain"])) {
				$config[$key]['session']['domain'] = $config["{$prefix}cookie_domain"];
			}
			if (isset($config["{$prefix}cookie_path"])) {
				$config[$key]['session']['path'] = $config["{$prefix}cookie_path"];
			}
			if (isset($config["{$prefix}cookie_secure"])) {
				$config[$key]['session']['secure'] = (bool)$config["{$prefix}cookie_secure"];
			}
			if (isset($config["{$prefix}cookie_httponly"])) {
				$config[$key]['session']['httponly'] = (bool)$config["{$prefix}cookie_httponly"];
			}
			if (isset($config["{$prefix}cookie_expire"])) {
				$time = strtotime($config["{$prefix}cookie_expire"]);
				$config[$key]['session']['expire'] = $time;
			}

			unset($config["{$prefix}cookie_defaults_source"]);
			unset($config["{$prefix}cookie_name"]);
			unset($config["{$prefix}cookie_domain"]);
			unset($config["{$prefix}cookie_secure"]);
			unset($config["{$prefix}cookie_httponly"]);
			unset($config["{$prefix}cookie_expire"]);
		}

		// split DBs
		if (isset($config['read1_dbuser'])) {
			// allow multi-DB
			$config['db']['split'] = true;
			foreach (['dbuser', 'dbpass', 'dbname', 'dbhost'] as $key) {
				$config['db']['write'][$key] = $config[$key];
			}

			$i = 1;
			while (isset($config["read{$i}_dbuser"])) {
				foreach (['dbuser', 'dbpass', 'dbname', 'dbhost'] as $key) {
					$config_key = "read{$i}_{$key}";
					$config['db']['read'][$i - 1] = $config[$config_key];
					unset($config[$config_key]);
				}
				$i++;
			}
		}

		// memcache
		$i = 1;
		while (isset($config["memcache{$i}_host"])) {
			$config['memcache_servers'][] = [
				$config["memcache{$i}_host"],
				$config["memcache{$i}_port"],
			];
			unset($config["memcache{$i}_host"]);
			unset($config["memcache{$i}_port"]);
			$i++;
		}

		return $config;
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
