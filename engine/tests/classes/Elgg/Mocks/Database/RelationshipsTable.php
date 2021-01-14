<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\RelationshipsTable as DbRelationshipsTable;
use stdClass;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Delete;

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
	 *
	 * @var array
	 */
	private $query_specs;

	/**
	 * {@inheritdoc}
	 */
	public function add($guid_one, $relationship, $guid_two, $return_id = false) {
		// Check for duplicates
		// note: escape $relationship after this call, we don't want to double-escape
		if ($this->check($guid_one, $relationship, $guid_two)) {
			return false;
		}
		
		// Check if the related entities exist
		if (!$this->entities->exists($guid_one) || !$this->entities->exists($guid_two)) {
			// one or both of the guids doesn't exist
			return false;
		}

		static::$iterator++;
		$id = static::$iterator;

		$row = (object) [
			'id' => $id,
			'guid_one' => (int) $guid_one,
			'guid_two' => (int) $guid_two,
			'relationship' => $relationship,
			'time_created' => $this->getCurrentTime()->getTimestamp(),
		];

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::add($row->guid_one, $row->relationship, $row->guid_two, $return_id);
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

		// Insert a new relationship
		$insert = Insert::intoTable('entity_relationships');
		$insert->values([
			'guid_one' => $insert->param($row->guid_one, ELGG_VALUE_GUID),
			'relationship' => $insert->param($row->relationship, ELGG_VALUE_STRING),
			'guid_two' => $insert->param($row->guid_two, ELGG_VALUE_GUID),
			'time_created' => $insert->param($row->time_created, ELGG_VALUE_TIMESTAMP),
		]);

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $insert->getSQL(),
			'params' => $insert->getParameters(),
			'insert_id' => $row->id,
		]);

		// Get relationship by its ID
		$select = Select::fromTable('entity_relationships');
		$select->select('*')
			->where($select->compare('id', '=', $row->id, ELGG_VALUE_ID));
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				return [];
			},
		]);

		// Delete relationship by its ID
		$delete = Delete::fromTable('entity_relationships');
		$delete->where($delete->compare('id', '=', $row->id, ELGG_VALUE_ID));
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $delete->getSQL(),
			'params' => $delete->getParameters(),
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
		$select = Select::fromTable('entity_relationships');
		$select->select('*')
			->where($select->compare('guid_one', '=', $row->guid_one, ELGG_VALUE_GUID))
			->andWhere($select->compare('relationship', '=', $row->relationship, ELGG_VALUE_STRING))
			->andWhere($select->compare('guid_two', '=', $row->guid_two, ELGG_VALUE_GUID))
			->setMaxResults(1);
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				return [];
			},
		]);
	}

}
