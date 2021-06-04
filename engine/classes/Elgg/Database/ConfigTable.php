<?php

namespace Elgg\Database;

use Elgg\BootService;
use Elgg\Database;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Traits\Loggable;

/**
 * Manipulates values in the dbprefix_config table. Do not use to read/write $CONFIG.
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
 * @since 1.10.0
 */
class ConfigTable {
	
	use Loggable;
	
	/**
	 * @var string name of the config database table
	 */
	const TABLE_NAME = 'config';
	
	/**
	 * @var Database
	 */
	protected $db;
	
	/**
	 * @var BootService
	 */
	protected $boot;
	
	/**
	 * Constructor
	 *
	 * @param Database    $db   Database
	 * @param BootService $boot BootService
	 */
	public function __construct(
		Database $db,
		BootService $boot
	) {
		$this->db = $db;
		$this->boot = $boot;
	}

	/**
	 * Removes a config setting
	 *
	 * @param string $name The name of the field.
	 *
	 * @return bool
	 */
	public function remove(string $name): bool {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('name', '=', $name, ELGG_VALUE_STRING));
		
		$this->boot->clearCache();
		
		return $this->db->deleteData($delete) !== false;
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
	 * @param string $name  The name of the configuration value (cannot be greater than 255 characters)
	 * @param mixed  $value Its value
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function set(string $name, $value): bool {
		if ($value === null) {
			// don't save null values to the DB
			throw new InvalidArgumentException(__METHOD__ . ' $value needs to be set');
		}
		
		if ($this->get($name) === null) {
			// $name doesn't exist yet
			$insert = Insert::intoTable(self::TABLE_NAME);
			$insert->values([
				'name' => $insert->param($name, ELGG_VALUE_STRING),
				'value' => $insert->param(serialize($value), ELGG_VALUE_STRING),
			]);
			
			$result = $this->db->insertData($insert);
		} else {
			// $name already exist, so update
			$update = Update::table(self::TABLE_NAME);
			$update->set('value', $update->param(serialize($value), ELGG_VALUE_STRING))
				->where($update->compare('name', '=', $name, ELGG_VALUE_STRING));
			
			$result = $this->db->updateData($update);
		}
		
		$this->boot->clearCache();
		
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
	public function get(string $name) {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('name', '=', $name, ELGG_VALUE_STRING));
		
		$result = $this->db->getDataRow($select);
		if (!empty($result)) {
			return unserialize($result->value);
		}
		
		return null;
	}

	/**
	 * Load all config values from the config table
	 *
	 * @return array
	 */
	public function getAll(): array {
		$values = [];
		
		$qb = Select::fromTable(self::TABLE_NAME);
		$qb->select('*');
		
		$data = $this->db->getData($qb);
		foreach ($data as $row) {
			$values[$row->name] = unserialize($row->value);
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
