<?php

namespace Elgg\Database;

use DatabaseException;
use Elgg\Database;
use ElggCache;
use ElggEntity;

/**
 * Private settings for entities
 *
 * Private settings provide metadata like storage of settings for plugins
 * and users.
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * @since  2.0.0
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
	}

	/**
	 * Gets a private setting for an entity
	 *
	 * Plugin authors can set private data on entities. By default private
	 * data will not be searched or exported.
	 *
	 * @param ElggEntity $entity The entity GUID
	 * @param string     $name   The name of the setting
	 *
	 * @return mixed The setting value, or null if does not exist
	 * @throws DatabaseException
	 */
	public function get(ElggEntity $entity, $name) {
		$values = $this->cache->load($entity->guid);

		if (isset($values[$name])) {
			return $values[$name];
		}

		$qb = Select::fromTable('private_settings');
		$qb->select('name')
			->addSelect('value')
			->where($qb->compare('name', '=', $name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '=', $entity->guid, ELGG_VALUE_INTEGER));

		$setting = $this->db->getDataRow($qb);
		if ($setting) {
			return $setting->value;
		}

		return null;
	}

	/**
	 * Return an array of all private settings
	 *
	 * @param ElggEntity $entity Entity
	 *
	 * @return string[] empty array if no settings
	 * @throws DatabaseException
	 */
	public function getAllForEntity(ElggEntity $entity) {
		$values = $this->cache->load($entity->guid);
		if (isset($values)) {
			return $values;
		}

		$qb = Select::fromTable('private_settings');
		$qb->select('name')
			->addSelect('value')
			->where($qb->compare('entity_guid', '=', $entity->guid, ELGG_VALUE_INTEGER));

		$result = $this->db->getData($qb);

		$return = [];

		if ($result) {
			foreach ($result as $r) {
				$return[$r->name] = $r->value;
			}
		}

		$this->cache->save($entity->guid, $return);

		return $return;
	}

	/**
	 * Sets a private setting for an entity.
	 *
	 * @param ElggEntity $entity Entity
	 * @param string     $name   The name of the setting
	 * @param string     $value  The value of the setting
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function set(ElggEntity $entity, $name, $value) {
		$entity->invalidateCache();

		$value_type = is_int($value) ? ELGG_VALUE_INTEGER : ELGG_VALUE_STRING;

		$qb = Select::fromTable('private_settings');
		$qb->select('id')
			->where($qb->compare('name', '=', $name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '=', $entity->guid, ELGG_VALUE_INTEGER));

		$row = $this->db->getDataRow($qb);

		if ($row) {
			$qb = Update::table('private_settings');
			$qb->set('value', $qb->param($value, $value_type))
				->where($qb->compare('id', '=', $row->id, ELGG_VALUE_INTEGER));

			$result = $this->db->updateData($qb);
		} else {
			$qb = Insert::intoTable('private_settings');
			$qb->values([
				'entity_guid' => $qb->param($entity->guid, ELGG_VALUE_INTEGER),
				'name' => $qb->param($name, ELGG_VALUE_STRING),
				'value' => $qb->param($value, $value_type),
			]);

			$result = $this->db->insertData($qb);
		}

		return $result !== false;
	}

	/**
	 * Deletes a private setting for an entity.
	 *
	 * @param ElggEntity $entity Entity
	 * @param string     $name   The name of the setting
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function remove(ElggEntity $entity, $name) {
		$entity->invalidateCache();

		$qb = Delete::fromTable('private_settings');
		$qb->where($qb->compare('name', '=', $name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '=', $entity->guid, ELGG_VALUE_INTEGER));

		return $this->db->deleteData($qb);
	}

	/**
	 * Deletes all private settings for an entity
	 *
	 * @param ElggEntity $entity Entity
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function removeAllForEntity(ElggEntity $entity) {
		$entity->invalidateCache();

		$qb = Delete::fromTable('private_settings');
		$qb->where($qb->compare('entity_guid', '=', $entity->guid, ELGG_VALUE_INTEGER));

		return $this->db->deleteData($qb);
	}

}
