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
	 * @var \Elgg\Database
	 */
	protected $db;
	
	/**
	 * @var \Elgg\BootService
	 */
	protected $boot;
	
	/**
	 * @var \Elgg\Logger
	 */
	protected $logger;

	/**
	 * Constructor
	 *
	 * @param \Elgg\Database    $db     Database
	 * @param \Elgg\BootService $boot   BootService
	 * @param \Elgg\Logger      $logger Logger
	 */
	public function __construct(\Elgg\Database $db, \Elgg\BootService $boot, \Elgg\Logger $logger) {
		global $CONFIG;
		$this->CONFIG = $CONFIG;
		
		$this->db = $db;
		$this->boot = $boot;
		$this->logger = $logger;
	}

	/**
	 * Removes a config setting.
	 *
	 * @param string $name The name of the field.
	 *
	 * @return bool Success or failure
	 */
	function remove($name) {
		$name = trim($name);
	
		if (isset($this->CONFIG->$name)) {
			unset($this->CONFIG->$name);
		}
	
		$query = "
			DELETE FROM {$this->CONFIG->dbprefix}config
			WHERE name = :name
		";

		$params = [
			':name' => $name,
		];
		
		$this->boot->invalidateCache();
	
		return $this->db->deleteData($query, $params) !== false;
	}
	
	/**
	 * Add or update a config setting.
	 *
	 * Plugin authors should use elgg_set_config().
	 *
	 * If the config name already exists, it will be updated to the new value.
	 *
	 * @note Internal: These settings are stored in the dbprefix_config table and read
	 * during system boot into $CONFIG.
	 *
	 * @note Internal: The value is serialized so we maintain type information.
	 *
	 * @param string $name  The name of the configuration value
	 * @param mixed  $value Its value
	 *
	 * @return bool
	 */
	function set($name, $value) {
		$name = trim($name);
	
		// cannot store anything longer than 255 characters in db, so catch before we set
		if (elgg_strlen($name) > 255) {
			$this->logger->error("The name length for configuration variables cannot be greater than 255");
			return false;
		}
		
		$this->CONFIG->$name = $value;
	
		$dbprefix = $this->CONFIG->dbprefix;
		
		$sql = "
			INSERT INTO {$dbprefix}config
			SET name = :name,
				value = :value
			ON DUPLICATE KEY UPDATE value = :value
		";
		
		$params = [
			':name' => $name,
			':value' => serialize($value),
		];
		
		$version = (int) $this->CONFIG->version;
		
		if (!empty($version) && $version < 2016102500) {
			// need to do this the old way as long as site_guid columns have not been dropped
			$sql = "
				INSERT INTO {$dbprefix}config
				SET name = :name,
					value = :value,
					site_guid = :site_guid
				ON DUPLICATE KEY UPDATE value = :value
			";
			
			$params[':site_guid'] = 1;
		}
				
		$result = $this->db->insertData($sql, $params);

		$this->boot->invalidateCache();
	
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
	 * @param string $name The name of the config value
	 *
	 * @return mixed|null
	 */
	function get($name) {
		$name = trim($name);
	
		// check for deprecated values.
		// @todo might be a better spot to define this?
		$new_name = false;
		switch($name) {
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
	
		// decide from where to return the value
		if (isset($this->CONFIG->$name)) {
			return $this->CONFIG->$name;
		}
		
		$sql = "
			SELECT value
			FROM {$this->CONFIG->dbprefix}config
			WHERE name = :name
		";
			
		$params[':name'] = $name;
		
		$result = $this->db->getDataRow($sql, null, $params);
	
		if ($result) {
			$result = unserialize($result->value);
	
			$this->CONFIG->$name = $result;
	
			return $result;
		}
	
		return null;
	}
}
