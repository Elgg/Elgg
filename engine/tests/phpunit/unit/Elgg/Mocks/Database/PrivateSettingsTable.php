<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\PrivateSettingsTable as DbPrivateSettingsTable;
use stdClass;

/**
 * This is a mock database table class and is intended to simplify testing of
 * entity APIs. Do not use this class to test the private settings table iteself
 */
class PrivateSettingsTable extends DbPrivateSettingsTable {

	/**
	 * @var stdClass[]
	 */
	public $rows = [];

	/**
	 * DB query query_specs
	 * @var array
	 */
	public $query_specs = [];

	/**
	 * @var int
	 */
	public $iterator = 100;

	/**
	 * {@inheritdoc}
	 */
	public function set($entity_guid, $name, $value) {
		$entity = get_entity((int) $entity_guid);
		if (!$entity) {
			return false;
		}

		if (!isset($value)) {
			return false;
		}

		$this->iterator++;
		$id = $this->iterator;

		$row = (object) [
			'id' => $id,
			'entity_guid' => $entity->guid,
			'name' => (string) $name,
			'value' => (string) $value,
		];

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::set($entity_guid, $name, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAll($entity_guid) {
		$rows = [];
		foreach ($this->rows as $id => $row) {
			if ($row->entity_guid == $entity_guid) {
				$rows[] = new ElggMetadata($row);
			}
		}
		return $rows;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeAllForEntity($entity_guid) {
		$deleted = false;
		foreach ($this->rows as $id => $row) {
			if ($row->entity_guid == $entity_guid) {
				$this->clearQuerySpecs($this->rows[$id]);
				$deleted = true;
				unset($this->rows[$id]);
			}
		}
		return $deleted;
	}

	/**
	 * Clear query specs
	 * 
	 * @param stdClass $row Data row
	 * @return void
	 */
	public function clearQuerySpecs(stdClass $row) {
		if (!isset($this->query_specs[$row->id])) {
			return;
		}
		foreach ($this->query_specs[$row->id] as $spec) {
			$this->db->removeQuerySpec($spec);
		}
	}

	/**
	 * Add query query_specs for a metadata object
	 * 
	 * @param stdClass $row Data row
	 * @return void
	 */
	public function addQuerySpecs(stdClass $row) {

		$this->clearQuerySpecs($row);

		// Set a new setting
		$query = "
			INSERT into {$this->table}
			(entity_guid, name, value) VALUES
			(:entity_guid, :name, :value)
			ON DUPLICATE KEY UPDATE value = :value
		";
		$params = [
			':entity_guid' => (int) $row->entity_guid,
			':name' => (string) $row->name,
			':value' => (string) $row->value,
		];
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $query,
			'params' => $params,
			'insert_id' => $row->id,
		]);

		// Get setting by its value
		$query = "
			SELECT value FROM {$this->table}
			WHERE name = :name
			AND entity_guid = :entity_guid
		";
		$params = [
			':entity_guid' => (int) $row->entity_guid,
			':name' => (string) $row->name,
		];
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $query,
			'params' => $params,
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				return [];
			},
		]);

		$query = "
			DELETE FROM {$this->table}
			WHERE name = :name
			AND entity_guid = :entity_guid
		";
		$params = [
			':entity_guid' => (int) $row->entity_guid,
			':name' => (string) $row->name,
		];

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $query,
			'params' => $params,
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					unset($this->rows[$row->id]);
					$this->clearQuerySpecs($row);
					return [$row->id];
				}
				return [];
			}
		]);

	}

	/**
	 * Iterate ID
	 * @return int
	 */
	public function iterate() {
		$this->iterator++;
		return $this->iterator;
	}

}
