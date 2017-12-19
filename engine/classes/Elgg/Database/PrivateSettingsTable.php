<?php

namespace Elgg\Database;

use DatabaseException;
use Elgg\Database;
use ElggCache;

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
	 * @var string
	 */
	protected $table;

	/**
	 * @var ElggCache
	 */
	protected $cache;

	/**
	 * Constructor
	 *
	 * @param Database    $db       The database
	 * @param EntityTable $entities Entities table
	 * @param ElggCache   $cache    Settings cache
	 */
	public function __construct(Database $db, EntityTable $entities, ElggCache $cache) {
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
	 * @throws DatabaseException
	 */
	public function get($entity_guid, $name) {
		$values = $this->cache->load($entity_guid);

		if (isset($values[$name])) {
			return $values[$name];
		}

		if (!$this->entities->exists($entity_guid)) {
			return false;
		}

		$qb = Select::fromTable('private_settings');
		$qb->select('name')
			->addSelect('value')
			->where($qb->compare('name', '=', $name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '=', $entity_guid, ELGG_VALUE_INTEGER));

		$setting = $this->db->getDataRow($qb);
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
	 * @throws DatabaseException
	 */
	public function getAll($entity_guid) {
		if (!$this->entities->exists($entity_guid)) {
			return [];
		}

		$values = $this->cache->load($entity_guid);
		if (isset($values)) {
			return $values;
		}

		$qb = Select::fromTable('private_settings');
		$qb->select('name')
			->addSelect('value')
			->where($qb->compare('entity_guid', '=', $entity_guid, ELGG_VALUE_INTEGER));

		$result = $this->db->getData($qb);

		$return = [];

		if ($result) {
			foreach ($result as $r) {
				$return[$r->name] = $r->value;
			}
		}

		$this->cache->save($entity_guid, $return);

		return $return;
	}

	/**
	 * Sets a private setting for an entity.
	 *
	 * @param int    $entity_guid The entity GUID
	 * @param string $name        The name of the setting
	 * @param string $value       The value of the setting
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function set($entity_guid, $name, $value) {
		if (!$this->entities->exists($entity_guid)) {
			return false;
		}

		$this->entities->invalidateCache($entity_guid);

		$qb = Select::fromTable('private_settings');
		$qb->select('id')
			->where($qb->compare('name', '=', $name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '=', $entity_guid, ELGG_VALUE_INTEGER));

		$row = $this->db->getDataRow($qb);

		if ($row) {
			$qb = Update::table('private_settings');
			$qb->set('value', $qb->param($value, ELGG_VALUE_STRING))
				->where($qb->compare('id', '=', $row->id, ELGG_VALUE_INTEGER));

			$result = $this->db->updateData($qb);
		} else {
			$qb = Insert::intoTable('private_settings');
			$qb->values([
				'entity_guid' => $qb->param($entity_guid, ELGG_VALUE_INTEGER),
				'name' => $qb->param($name, ELGG_VALUE_STRING),
				'value' => $qb->param($value, ELGG_VALUE_STRING),
			]);

			$result = $this->db->insertData($qb);
		}

		return $result !== false;
	}

	/**
	 * Deletes a private setting for an entity.
	 *
	 * @param int    $entity_guid The Entity GUID
	 * @param string $name        The name of the setting
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function remove($entity_guid, $name) {
		$this->entities->invalidateCache($entity_guid);

		$qb = Delete::fromTable('private_settings');
		$qb->where($qb->compare('name', '=', $name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '=', $entity_guid, ELGG_VALUE_INTEGER));

		return $this->db->deleteData($qb);
	}

	/**
	 * Deletes all private settings for an entity
	 *
	 * @param int $entity_guid The Entity GUID
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function removeAllForEntity($entity_guid) {
		$this->entities->invalidateCache($entity_guid);

		$qb = Delete::fromTable('private_settings');
		$qb->where($qb->compare('entity_guid', '=', $entity_guid, ELGG_VALUE_INTEGER));

		return $this->db->deleteData($qb);
	}

}
