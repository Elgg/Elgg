<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\Delete;
use Elgg\Database\Insert;
use Elgg\Database\PrivateSettingsTable as DbPrivateSettingsTable;
use Elgg\Database\Select;
use Elgg\Database\Update;
use ElggEntity;
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
	static $iterator = 100;

	/**
	 * {@inheritdoc}
	 */
	public function set(ElggEntity $entity, $name, $value) {
		if (!isset($value)) {
			return false;
		}

		static::$iterator++;
		$id = static::$iterator;

		$row = (object) [
			'id' => $id,
			'entity_guid' => $entity->guid,
			'name' => $name,
			'value' => $value,
		];

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::set($entity, $name, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAllForEntity(ElggEntity $entity) {
		$rows = [];
		foreach ($this->rows as $id => $row) {
			if ($row->entity_guid == $entity->guid) {
				$rows[$row->name] = $row->value;
			}
		}

		return $rows;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeAllForEntity(ElggEntity $entity) {
		$deleted = false;
		foreach ($this->rows as $id => $row) {
			if ($row->entity_guid == $entity->guid) {
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
	 *
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
	 *
	 * @return void
	 */
	public function addQuerySpecs(stdClass $row) {

		$this->clearQuerySpecs($row);

		$qb = Update::table('private_settings');
		$qb->set('value', $qb->param($row->value, ELGG_VALUE_STRING))
			->where($qb->compare('id', '=', $row->id, ELGG_VALUE_INTEGER));

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => true,
		]);

		$qb = Insert::intoTable('private_settings');
		$qb->values([
			'entity_guid' => $qb->param($row->entity_guid, ELGG_VALUE_INTEGER),
			'name' => $qb->param($row->name, ELGG_VALUE_STRING),
			'value' => $qb->param($row->value, ELGG_VALUE_STRING),
		]);

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'insert_id' => $row->id,
		]);

		$qb = Select::fromTable('private_settings');
		$qb->select('name')
			->addSelect('value')
			->where($qb->compare('name', '=', $row->name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '=', $row->entity_guid, ELGG_VALUE_INTEGER));

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function () use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}

				return [];
			},
		]);

		$qb = Delete::fromTable('private_settings');
		$qb->where($qb->compare('name', '=', $row->name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '=', $row->entity_guid, ELGG_VALUE_INTEGER));

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function () use ($row) {
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
		static::$iterator++;

		return static::$iterator;
	}

}
