<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\Delete;
use Elgg\Database\Insert;
use Elgg\Database\RelationshipsTable as DbRelationshipsTable;
use Elgg\Database\Select;
use stdClass;

/**
 * This mock table is designed to simplify testing of DB-dependent services.
 * It populates the mock database with query specifications for predictable results
 * when relationship are requested or deleted.
 *
 * Note that this mock is not designed for testing the relationships table itself.
 * When testing the relationships table, you should define query specs individually for the
 * method being tested.
 */
class RelationshipsTable extends DbRelationshipsTable {

	/**
	 * @var stdClass[]
	 */
	public $rows = [];

	/**
	 * @var int
	 */
	static $iterator = 100;

	/**
	 * @var array
	 */
	private $query_specs;

	/**
	 * {@inheritdoc}
	 */
	public function add(\ElggRelationship $relationship) {
		$guid_one = $relationship->guid_one;
		$name = $relationship->relationship;
		$guid_two = $relationship->guid_two;

		$rel = $this->check($guid_one, $name, $guid_two);
		if ($rel) {
			return false;
		}

		static::$iterator++;
		$id = static::$iterator;

		$row = (object) [
			'id' => $id,
			'guid_one' => (int) $guid_one,
			'guid_two' => (int) $guid_two,
			'relationship' => $name,
			'time_created' => $this->getCurrentTime()->getTimestamp(),
		];

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::add($relationship);
	}

	/**
	 * Clear query specs
	 *
	 * @param int $id Relationship ID
	 * @return void
	 */
	public function clearQuerySpecs($id) {
		if (!empty($this->query_specs[$id])) {
			foreach ($this->query_specs[$id] as $spec) {
				$this->db->removeQuerySpec($spec);
			}
		}
	}

	/**
	 * Add query specs for a relationship data row
	 *
	 * @param stdClass $row Data row
	 * @return void
	 */
	public function addQuerySpecs(stdClass $row) {

		$this->clearQuerySpecs($row->id);

		$dbprefix = _elgg_config()->dbprefix;

		// Insert a new relationship
		$qb = Insert::intoTable('entity_relationships');
		$qb->values([
			'guid_one' => $qb->param($row->guid_one, ELGG_VALUE_INTEGER),
			'relationship' => $qb->param($row->relationship, ELGG_VALUE_STRING),
			'guid_two' => $qb->param($row->guid_two, ELGG_VALUE_INTEGER),
			'time_created' => $qb->param($row->time_created, ELGG_VALUE_INTEGER),
		]);

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'insert_id' => $row->id,
		]);

		// Get relationship by its ID
		$qb = Select::fromTable('entity_relationships');
		$qb->select('*')
			->where($qb->compare('id', '=', $row->id, ELGG_VALUE_INTEGER));

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				return [];
			},
		]);

		// Delete relationship by its ID
		$qb = Delete::fromTable('entity_relationships');
		$qb->where($qb->compare('id', '=', $row->id, ELGG_VALUE_INTEGER));

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					$this->clearQuerySpecs($row->id);
					unset($this->rows[$row->id]);
					return [$row->id];
				}
				return [];
			},
			'times' => 1,
		]);

		// Check relationship between two GUIDs
		$qb = Select::fromTable('entity_relationships');
		$qb->select('*')
			->where($qb->compare('guid_one', '=', $row->guid_one, ELGG_VALUE_INTEGER))
			->andWhere($qb->compare('guid_two', '=', $row->guid_two, ELGG_VALUE_INTEGER))
			->andWhere($qb->compare('relationship', '=', $row->relationship, ELGG_VALUE_STRING))
			->setMaxResults(1);


		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				return [];
			},
		]);
	}

}
