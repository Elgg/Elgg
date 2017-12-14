<?php
namespace Elgg\Database;

use Elgg\Database;
use Elgg\Database\EntityTable;
use Elgg\Cache\PluginSettingsCache;

/**
 * Private settings for entities
 *
 * Private settings provide metadata like storage of settings for plugins
 * and users.
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * @since 2.0.0
 */
class PrivateSettingsTable {

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var EntityTable
	 */
	protected $entities;

	/**
	 * @var string Name of the database table
	 */
	protected $table;

	/**
	 * @var PluginSettingsCache cache for settings
	 */
	protected $cache;

	/**
	 * Constructor
	 *
	 * @param Database            $db       The database
	 * @param EntityTable         $entities Entities table
	 * @param PluginSettingsCache $cache    Settings cache
	 */
	public function __construct(Database $db, EntityTable $entities, PluginSettingsCache $cache) {
		$this->db = $db;
		$this->entities = $entities;
		$this->cache = $cache;
		$this->table = $this->db->prefix . 'private_settings';
	}

	/**
	 * Gets a private setting for an entity
	 *
	 * Plugin authors can set private data on entities. By default private
	 * data will not be searched or exported.
	 *
	 * @param int    $entity_guid The entity GUID
	 * @param string $name        The name of the setting
	 *
	 * @return mixed The setting value, or null if does not exist
	 */
	public function get($entity_guid, $name) {

		$values = $this->cache->getAll($entity_guid);
		if (isset($values[$name])) {
			return $values[$name];
		}

		if (!$this->entities->exists($entity_guid)) {
			return false;
		}

		$query = "
			SELECT value FROM {$this->table}
			WHERE name = :name
			AND entity_guid = :entity_guid
		";
		$params = [
			':entity_guid' => (int) $entity_guid,
			':name' => (string) $name,
		];

		$setting = $this->db->getDataRow($query, null, $params);

		if ($setting) {
			return $setting->value;
		}

		return null;
	}

	/**
	 * Return an array of all private settings.
	 *
	 * @param int $entity_guid The entity GUID
	 *
	 * @return string[] empty array if no settings
	 */
	public function getAll($entity_guid) {
		if (!$this->entities->exists($entity_guid)) {
			return [];
		}

		$query = "
			SELECT * FROM {$this->table}
			WHERE entity_guid = :entity_guid
		";
		$params = [
			':entity_guid' => (int) $entity_guid,
		];

		$result = $this->db->getData($query, null, $params);

		$return = [];

		if ($result) {
			foreach ($result as $r) {
				$return[$r->name] = $r->value;
			}
		}

		return $return;
	}

	/**
	 * Sets a private setting for an entity.
	 *
	 * @param int    $entity_guid The entity GUID
	 * @param string $name        The name of the setting
	 * @param string $value       The value of the setting
	 * @return bool
	 */
	public function set($entity_guid, $name, $value) {
		$this->cache->clear($entity_guid);
		_elgg_services()->boot->invalidateCache();

		if (!$this->entities->exists($entity_guid)) {
			return false;
		}

		$query = "
			INSERT into {$this->table}
			(entity_guid, name, value) VALUES
			(:entity_guid, :name, :value)
			ON DUPLICATE KEY UPDATE value = :value
		";
		$params = [
			':entity_guid' => (int) $entity_guid,
			':name' => (string) $name,
			':value' => (string) $value,
		];

		$result = $this->db->insertData($query, $params);

		return $result !== false;
	}

	/**
	 * Deletes a private setting for an entity.
	 *
	 * @param int    $entity_guid The Entity GUID
	 * @param string $name        The name of the setting
	 * @return bool
	 * @throws \DatabaseException
	 */
	public function remove($entity_guid, $name) {
		$this->cache->clear($entity_guid);
		_elgg_services()->boot->invalidateCache();

		$qb = Delete::fromTable('private_settings');
		$qb->where($qb->compare('name', '=', $name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '=', $entity_guid, ELGG_VALUE_INTEGER));

		return $this->db->deleteData($qb);
	}

	/**
	 * Deletes all private settings for an entity
	 *
	 * @param int $entity_guid The Entity GUID
	 * @return bool
	 */
	public function removeAllForEntity($entity_guid) {
		$this->cache->clear($entity_guid);
		_elgg_services()->boot->invalidateCache();

		$query = "
			DELETE FROM {$this->table}
			WHERE entity_guid = :entity_guid
		";
		$params = [
			':entity_guid' => (int) $entity_guid,
		];

		return $this->db->deleteData($query, $params);
	}

}
