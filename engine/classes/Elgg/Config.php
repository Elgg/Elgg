<?php
namespace Elgg;


/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Config
 * @since      1.10.0
 */
class Config {
	/**
	 * Global Elgg configuration
	 * 
	 * @var \stdClass
	 */
	private $CONFIG;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $CONFIG;
		$this->CONFIG = $CONFIG;
	}

	/**
	 * Get the URL for the current (or specified) site
	 *
	 * @param int $site_guid The GUID of the site whose URL we want to grab
	 * @return string
	 */
	function getSiteUrl($site_guid = 0) {
		if ($site_guid == 0) {
			
			return $this->CONFIG->wwwroot;
		}
	
		$site = get_entity($site_guid);
	
		if (!$site instanceof \ElggSite) {
			return false;
		}
		/* @var \ElggSite $site */
	
		return $site->url;
	}
	
	/**
	 * Get the plugin path for this installation
	 *
	 * @return string
	 */
	function getPluginsPath() {
		
		return $this->CONFIG->pluginspath;
	}
	
	/**
	 * Get the data directory path for this installation
	 *
	 * @return string
	 */
	function getDataPath() {
		
		return $this->CONFIG->dataroot;
	}
	
	/**
	 * Get the root directory path for this installation
	 *
	 * @return string
	 */
	function getRootPath() {
		
		return $this->CONFIG->path;
	}
	
	/**
	 * Get an Elgg configuration value
	 *
	 * @param string $name      Name of the configuration value
	 * @param int    $site_guid null for installation setting, 0 for default site
	 *
	 * @return mixed Configuration value or null if it does not exist
	 */
	function get($name, $site_guid = 0) {
		
	
		$name = trim($name);
	
		// do not return $CONFIG value if asking for non-current site
		if (($site_guid === 0 || $site_guid === null || $site_guid == $this->CONFIG->site_guid) && isset($this->CONFIG->$name)) {
			return $this->CONFIG->$name;
		}
	
		if ($site_guid === null) {
			// installation wide setting
			$value = _elgg_services()->datalist->get($name);
		} else {
			if ($site_guid == 0) {
				$site_guid = (int) $this->CONFIG->site_guid;
			}
	
			// hit DB only if we're not sure if value isn't already loaded
			if (!isset($this->CONFIG->site_config_loaded) || $site_guid != $this->CONFIG->site_guid) {
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
	
		if ($site_guid == $this->CONFIG->site_guid || $site_guid === null) {
			$this->CONFIG->$name = $value;
		}
	
		return $value;
	}
	
	/**
	 * Set an Elgg configuration value
	 *
	 * @warning This does not persist the configuration setting. Use elgg_save_config()
	 *
	 * @param string $name  Name of the configuration value
	 * @param mixed  $value Value
	 *
	 * @return void
	 */
	function set($name, $value) {
		
	
		$name = trim($name);
	
		$this->CONFIG->$name = $value;
	}
	
	/**
	 * Save a configuration setting
	 *
	 * @param string $name      Configuration name (cannot be greater than 255 characters)
	 * @param mixed  $value     Configuration value. Should be string for installation setting
	 * @param int    $site_guid null for installation setting, 0 for default site
	 *
	 * @return bool
	 */
	function save($name, $value, $site_guid = 0) {
		
	
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
				$site_guid = (int) $this->CONFIG->site_guid;
			}
			$result = _elgg_services()->configTable->set($name, $value, $site_guid);
		}
	
		if ($site_guid === null || $site_guid == $this->CONFIG->site_guid) {
			_elgg_services()->config->set($name, $value);
		}
	
		return $result;
	}
}