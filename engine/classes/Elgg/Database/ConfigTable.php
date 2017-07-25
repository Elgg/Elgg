<?php
namespace Elgg\Database;

/**
 * Manipulates values in the dbprefix_config table. Do not use to read/write $CONFIG.
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * @since  1.10.0
 */
class ConfigTable {
		
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
	
		$query = "
			DELETE FROM {$this->db->prefix}config
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
	 * Plugin authors should use elgg_save_config().
	 *
	 * If the config name already exists, it will be updated to the new value.
	 *
	 * @note Internal: These settings are stored in the dbprefix_config table and read
	 * during system boot into the config service.
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
	
		$sql = "
			INSERT INTO {$this->db->prefix}config
			SET name = :name,
				value = :value
			ON DUPLICATE KEY UPDATE value = :value
		";
		
		$params = [
			':name' => $name,
			':value' => serialize($value),
		];
		
		$version = (int) elgg_get_config('version');
		
		if (!empty($version) && $version < 2016102500) {
			// need to do this the old way as long as site_guid columns have not been dropped
			$sql = "
				INSERT INTO {$this->db->prefix}config
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
	 * during system boot into the config service.
	 *
	 * @param string $name The name of the config value
	 *
	 * @return mixed|null
	 */
	function get($name) {
		$name = trim($name);
	
		$sql = "
			SELECT value
			FROM {$this->db->prefix}config
			WHERE name = :name
		";
			
		$params[':name'] = $name;
		
		$result = $this->db->getDataRow($sql, null, $params);
		if ($result) {
			return unserialize($result->value);
		}
	
		return null;
	}
}
