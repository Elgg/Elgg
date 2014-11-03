<?php
namespace Elgg\Database;

/**
 * These settings are stored in the dbprefix_config table and read 
 * during system boot into $CONFIG.
 * 
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class ConfigTable {
		
	/**
	 * Removes a config setting.
	 *
	 * @param string $name      The name of the field.
	 * @param int    $site_guid Optionally, the GUID of the site (default: current site).
	 *
	 * @return bool Success or failure
	 */
	function remove($name, $site_guid = 0) {
		global $CONFIG;
	
		$name = trim($name);
	
		$site_guid = (int) $site_guid;
		if ($site_guid == 0) {
			$site_guid = (int) $CONFIG->site_guid;
		}
	
		if ($site_guid == $CONFIG->site_guid && isset($CONFIG->$name)) {
			unset($CONFIG->$name);
		}
	
		$escaped_name = sanitize_string($name);
		$query = "DELETE FROM {$CONFIG->dbprefix}config WHERE name = '$escaped_name' AND site_guid = $site_guid";
	
		return delete_data($query) !== false;
	}
	
	/**
	 * Add or update a config setting.
	 * 
	 * Plugin authors should use elgg_set_config().
	 *
	 * If the config name already exists, it will be updated to the new value.
	 *
	 * @warning Names should be selected so as not to collide with the names for the
	 * datalist (application configuration)
	 * 
	 * @internal These settings are stored in the dbprefix_config table and read 
	 * during system boot into $CONFIG.
	 * 
	 * @internal The value is serialized so we maintain type information.
	 *
	 * @param string $name      The name of the configuration value
	 * @param mixed  $value     Its value
	 * @param int    $site_guid Optionally, the GUID of the site (current site is assumed by default)
	 *
	 * @return bool
	 */
	function set($name, $value, $site_guid = 0) {
		global $CONFIG;
	
		$name = trim($name);
	
		// cannot store anything longer than 255 characters in db, so catch before we set
		if (elgg_strlen($name) > 255) {
			elgg_log("The name length for configuration variables cannot be greater than 255", "ERROR");
			return false;
		}
	
		$site_guid = (int) $site_guid;
		if ($site_guid == 0) {
			$site_guid = (int) $CONFIG->site_guid;
		}
	
		if ($site_guid == $CONFIG->site_guid) {
			$CONFIG->$name = $value;
		}
	
		$escaped_name = sanitize_string($name);
		$escaped_value = sanitize_string(serialize($value));
		$result = insert_data("INSERT INTO {$CONFIG->dbprefix}config
			SET name = '$escaped_name', value = '$escaped_value', site_guid = $site_guid
			ON DUPLICATE KEY UPDATE value = '$escaped_value'");
	
		return $result !== false;
	}
	
	/**
	 * Gets a configuration value
	 * 
	 * Plugin authors should use elgg_get_config().
	 *
	 * @internal These settings are stored in the dbprefix_config table and read 
	 * during system boot into $CONFIG.
	 *
	 * @param string $name      The name of the config value
	 * @param int    $site_guid Optionally, the GUID of the site (default: current site)
	 *
	 * @return mixed|null
	 */
	function get($name, $site_guid = 0) {
		global $CONFIG;
	
		$name = trim($name);
	
		$site_guid = (int) $site_guid;
	
		// check for deprecated values.
		// @todo might be a better spot to define this?
		$new_name = false;
		switch($name) {
			case 'viewpath':
				$new_name = 'view_path';
				break;
	
			case 'pluginspath':
				$new_name = 'plugins_path';
				break;
	
			case 'sitename':
				$new_name = 'site_name';
				break;
		}
	
		// @todo these haven't really been implemented in Elgg 1.8. Complete in 1.9.
		// show dep message
		if ($new_name) {
			//	$msg = "Config value $name has been renamed as $new_name";
			$name = $new_name;
			//	elgg_deprecated_notice($msg, $dep_version);
		}
	
		if ($site_guid == 0) {
			$site_guid = (int) $CONFIG->site_guid;
		}
	
		// decide from where to return the value
		if ($site_guid == $CONFIG->site_guid && isset($CONFIG->$name)) {
			return $CONFIG->$name;
		}
	
		$escaped_name = sanitize_string($name);
		$result = get_data_row("SELECT value FROM {$CONFIG->dbprefix}config
			WHERE name = '$escaped_name' AND site_guid = $site_guid");
	
		if ($result) {
			$result = unserialize($result->value);
	
			if ($site_guid == $CONFIG->site_guid) {
				$CONFIG->$name = $result;
			}
	
			return $result;
		}
	
		return null;
	}
	
	/**
	 * Loads all configuration values from the dbprefix_config table into $CONFIG.
	 *
	 * @param int $site_guid Optionally, the GUID of the site (current site is assumed by default)
	 *
	 * @return bool
	 */
	function loadAll($site_guid = 0) {
		global $CONFIG;
	
		$site_guid = (int) $site_guid;
	
		if ($site_guid == 0) {
			$site_guid = (int) $CONFIG->site_guid;
		}
	
		if ($result = get_data("SELECT * FROM {$CONFIG->dbprefix}config WHERE site_guid = $site_guid")) {
			foreach ($result as $r) {
				$name = $r->name;
				$value = $r->value;
				$CONFIG->$name = unserialize($value);
			}
	
			return true;
		}
		return false;
	}
}