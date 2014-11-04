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
	 * Get the URL for the current (or specified) site
	 *
	 * @param int $site_guid The GUID of the site whose URL we want to grab
	 * @return string
	 */
	function getSiteUrl($site_guid = 0) {
		if ($site_guid == 0) {
			global $CONFIG;
			return $CONFIG->wwwroot;
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
		global $CONFIG;
		return $CONFIG->pluginspath;
	}
	
	/**
	 * Get the data directory path for this installation
	 *
	 * @return string
	 */
	function getDataPath() {
		global $CONFIG;
		return $CONFIG->dataroot;
	}
	
	/**
	 * Get the root directory path for this installation
	 *
	 * @return string
	 */
	function getRootPath() {
		global $CONFIG;
		return $CONFIG->path;
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
		global $CONFIG;
	
		$name = trim($name);
	
		// do not return $CONFIG value if asking for non-current site
		if (($site_guid === 0 || $site_guid === null || $site_guid == $CONFIG->site_guid) && isset($CONFIG->$name)) {
			return $CONFIG->$name;
		}
	
		if ($site_guid === null) {
			// installation wide setting
			$value = datalist_get($name);
		} else {
			if ($site_guid == 0) {
				$site_guid = (int) $CONFIG->site_guid;
			}
	
			// hit DB only if we're not sure if value isn't already loaded
			if (!isset($CONFIG->site_config_loaded) || $site_guid != $CONFIG->site_guid) {
				// site specific setting
				$value = get_config($name, $site_guid);
			} else {
				$value = null;
			}
		}
	
		// @todo document why we don't cache false
		if ($value === false) {
			return null;
		}
	
		if ($site_guid == $CONFIG->site_guid || $site_guid === null) {
			$CONFIG->$name = $value;
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
		global $CONFIG;
	
		$name = trim($name);
	
		$CONFIG->$name = $value;
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
		global $CONFIG;
	
		$name = trim($name);
	
		if (strlen($name) > 255) {
			elgg_log("The name length for configuration variables cannot be greater than 255", "ERROR");
			return false;
		}
	
		if ($site_guid === null) {
			if (is_array($value) || is_object($value)) {
				return false;
			}
			$result = datalist_set($name, $value);
		} else {
			if ($site_guid == 0) {
				$site_guid = (int) $CONFIG->site_guid;
			}
			$result = set_config($name, $value, $site_guid);
		}
	
		if ($site_guid === null || $site_guid == $CONFIG->site_guid) {
			elgg_set_config($name, $value);
		}
	
		return $result;
	}
}