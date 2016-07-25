<?php
namespace Elgg;

use Elgg\Filesystem\Directory;

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
			 * for running Elgg as defined in the settings.php file.
			 *
			 * Plugin authors are encouraged to use elgg_get_config() instead of accessing
			 * the global directly.
			 *
			 * @see elgg_get_config()
			 * @see engine/settings.php
			 * @global \stdClass $CONFIG
			 */
			global $CONFIG;
			$CONFIG = $config;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSiteUrl($site_guid = 0) {
		if ($site_guid == 0) {
			return $this->config->wwwroot;
		}
	
		$site = get_entity($site_guid);
	
		if (!$site instanceof \ElggSite) {
			return false;
		}
		/* @var \ElggSite $site */
	
		return $site->url;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPluginsPath() {
		return $this->config->pluginspath;
	}

	/**
	 * Set up and return the cookie configuration array resolved from settings.php
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
			$c->cookies = array();
		}
		if (!isset($c->cookies['session'])) {
			$c->cookies['session'] = array();
		}
		$session_defaults = session_get_cookie_params();
		$session_defaults['name'] = 'Elgg';
		$c->cookies['session'] = array_merge($session_defaults, $c->cookies['session']);
		if (!isset($c->cookies['remember_me'])) {
			$c->cookies['remember_me'] = array();
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
		if (!isset($this->config->dataroot)) {
			\Elgg\Application::getDataPath();
		}

		return $this->config->dataroot;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCachePath() {
		$this->loadSettingsFile();

		if (!isset($this->config->cacheroot)) {
			$this->config->cacheroot = $this->getDataPath();
		}

		return $this->config->cacheroot;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($name, $site_guid = 0) {
		$name = trim($name);
	
		// do not return $config value if asking for non-current site
		if (($site_guid === 0 || $site_guid === null || $site_guid == $this->config->site_guid) && isset($this->config->$name)) {
			return $this->config->$name;
		}
	
		if ($site_guid === null) {
			// installation wide setting
			$value = _elgg_services()->datalist->get($name);
		} else {
			if ($site_guid == 0) {
				$site_guid = (int) $this->config->site_guid;
			}
	
			// hit DB only if we're not sure if value isn't already loaded
			if (!isset($this->config->site_config_loaded) || $site_guid != $this->config->site_guid) {
				// site specific setting
				$value = _elgg_services()->configTable->get($name, $site_guid);
			} else {
				$value = null;
			}
		}
	
		// @todo document why we don't cache false
		if ($value === false) {
			return null;
		}
	
		if ($site_guid == $this->config->site_guid || $site_guid === null) {
			$this->config->$name = $value;
		}
	
		return $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getVolatile($name) {
		return isset($this->config->{$name}) ? $this->config->{$name} : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set($name, $value) {
		$name = trim($name);
		$this->config->$name = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save($name, $value, $site_guid = 0) {
		$name = trim($name);
	
		if (strlen($name) > 255) {
			_elgg_services()->logger->error("The name length for configuration variables cannot be greater than 255");
			return false;
		}
	
		if ($site_guid === null) {
			if (is_array($value) || is_object($value)) {
				return false;
			}
			$result = _elgg_services()->datalist->set($name, $value);
		} else {
			if ($site_guid == 0) {
				$site_guid = (int) $this->config->site_guid;
			}
			$result = _elgg_services()->configTable->set($name, $value, $site_guid);
		}
	
		if ($site_guid === null || $site_guid == $this->config->site_guid) {
			$this->set($name, $value);
		}
	
		return $result;
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
			$path = Directory\Local::root()->getPath('engine/settings.php');
			if (!is_file($path)) {
				$path = Directory\Local::root()->getPath('elgg-config/settings.php');
			}
		}

		// No settings means a fresh install
		if (!is_file($path)) {
			if ($this->getVolatile('installer_running')) {
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
		$global_is_bound = (isset($CONFIG) && $CONFIG === $this->config);

		require_once $path;

		// normalize commonly needed values
		if (isset($CONFIG->dataroot)) {
			$CONFIG->dataroot = rtrim($CONFIG->dataroot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
			$GLOBALS['_ELGG']->dataroot_in_settings = true;
		} else {
			$GLOBALS['_ELGG']->dataroot_in_settings = false;
		}

		$GLOBALS['_ELGG']->simplecache_enabled_in_settings = isset($CONFIG->simplecache_enabled);

		if (!empty($CONFIG->cacheroot)) {
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
	 * Get the raw \stdClass object used for storage.
	 *
	 * We need this, for now, to construct some services.
	 *
	 * @internal Do not use this plugins or new core code!
	 * @todo Get rid of this.
	 *
	 * @return \stdClass
	 * @access private
	 */
	public function getStorageObject() {
		return $this->config;
	}
}