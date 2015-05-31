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
	 * Removes a config setting.
	 *
	 * @param string $name      The name of the field.
	 * @param int    $site_guid Optionally, the GUID of the site (default: current site).
	 *
	 * @return bool Success or failure
	 */
	function remove($name, $site_guid = 0) {
		
	
		$name = trim($name);
	
		$site_guid = (int) $site_guid;
		if ($site_guid == 0) {
			$site_guid = (int) $this->CONFIG->site_guid;
		}
	
		if ($site_guid == $this->CONFIG->site_guid && isset($this->CONFIG->$name)) {
			unset($this->CONFIG->$name);
		}
	
		$escaped_name = sanitize_string($name);
		$query = "DELETE FROM {$this->CONFIG->dbprefix}config WHERE name = '$escaped_name' AND site_guid = $site_guid";
	
		return _elgg_services()->db->deleteData($query) !== false;
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
	 * @note Internal: These settings are stored in the dbprefix_config table and read
	 * during system boot into $CONFIG.
	 * 
	 * @note Internal: The value is serialized so we maintain type information.
	 *
	 * @param string $name      The name of the configuration value
	 * @param mixed  $value     Its value
	 * @param int    $site_guid Optionally, the GUID of the site (current site is assumed by default)
	 *
	 * @return bool
	 */
	function set($name, $value, $site_guid = 0) {
		
	
		$name = trim($name);
	
		// cannot store anything longer than 255 characters in db, so catch before we set
		if (elgg_strlen($name) > 255) {
			_elgg_services()->logger->error("The name length for configuration variables cannot be greater than 255");
			return false;
		}
	
		$site_guid = (int) $site_guid;
		if ($site_guid == 0) {
			$site_guid = (int) $this->CONFIG->site_guid;
		}
	
		if ($site_guid == $this->CONFIG->site_guid) {
			$this->CONFIG->$name = $value;
		}
	
		$escaped_name = sanitize_string($name);
		$escaped_value = sanitize_string(serialize($value));
		$result = _elgg_services()->db->insertData("INSERT INTO {$this->CONFIG->dbprefix}config
			SET name = '$escaped_name', value = '$escaped_value', site_guid = $site_guid
			ON DUPLICATE KEY UPDATE value = '$escaped_value'");
	
		return $result !== false;
	}
	
	/**
	 * Gets a configuration value
	 * 
	 * Plugin authors should use elgg_get_config().
	 *
	 * @note Internal: These settings are stored in the dbprefix_config table and read
	 * during system boot into $CONFIG.
	 *
	 * @param string $name      The name of the config value
	 * @param int    $site_guid Optionally, the GUID of the site (default: current site)
	 *
	 * @return mixed|null
	 */
	function get($name, $site_guid = 0) {
		
	
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
			$site_guid = (int) $this->CONFIG->site_guid;
		}
	
		// decide from where to return the value
		if ($site_guid == $this->CONFIG->site_guid && isset($this->CONFIG->$name)) {
			return $this->CONFIG->$name;
		}
	
		$escaped_name = sanitize_string($name);
		$result = _elgg_services()->db->getDataRow("SELECT value FROM {$this->CONFIG->dbprefix}config
			WHERE name = '$escaped_name' AND site_guid = $site_guid");
	
		if ($result) {
			$result = unserialize($result->value);
	
			if ($site_guid == $this->CONFIG->site_guid) {
				$this->CONFIG->$name = $result;
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
		
	
		$site_guid = (int) $site_guid;
	
		if ($site_guid == 0) {
			$site_guid = (int) $this->CONFIG->site_guid;
		}
	
		if ($result = _elgg_services()->db->getData("SELECT * FROM {$this->CONFIG->dbprefix}config WHERE site_guid = $site_guid")) {
			foreach ($result as $r) {
				$name = $r->name;
				$value = $r->value;
				$this->CONFIG->$name = unserialize($value);
			}
	
			return true;
		}
		return false;
	}
}