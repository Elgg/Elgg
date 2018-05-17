<?php
namespace Elgg\Database;

use Elgg\BootService;
use Elgg\Database;
use Psr\Log\LoggerInterface;

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
	 * @var Database
	 */
	protected $db;
	
	/**
	 * @var BootService
	 */
	protected $boot;
	
	/**
	 * @var \Elgg\Logger
	 */
	protected $logger;

	/**
	 * Constructor
	 *
	 * @param Database        $db     Database
	 * @param BootService     $boot   BootService
	 * @param LoggerInterface $logger Logger
	 */
	public function __construct(
		Database $db,
		BootService $boot,
		LoggerInterface $logger
	) {
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

	/**
	 * Load all config values from the config table (and datalists if not yet
	 * upgraded to 3.0)
	 *
	 * @todo move into BootData once we no longer have to support the 2.x schema
	 *
	 * @return array
	 */
	public function getAll() {
		$values = [];

		$sql = "
			SELECT *
			FROM {$this->db->prefix}config
		";
		foreach ($this->db->getData($sql) as $row) {
			$values[$row->name] = unserialize($row->value);
		}

		if (!array_key_exists('installed', $values)) {
			// try to fetch from old pre 3.0 datalists table
			// need to do this to be able to perform an upgrade from 2.x to 3.0
			try {
				$sql = "
					SELECT *
					FROM {$this->db->prefix}datalists
				";
				foreach ($this->db->getData($sql) as $row) {
					$value = $row->value;
					if ($row->name == 'processed_upgrades') {
						// config table already serializes data so no need to double serialize
						$value = unserialize($value);
					}
					$values[$row->name] = $value;
				}
			} catch (\Exception $e) {
			}
		}

		// don't pull in old config values
		/**
		 * @see \Elgg\Config::__construct sets this
		 */
		unset($values['path']);
		unset($values['dataroot']);
		unset($values['default_site']);

		return $values;
	}
}
