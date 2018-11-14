<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\RelationshipsTable as DbRelationshipsTable;
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
	 *
	 * @var type@var array
	 */
	private $query_specs;

	/**
	 * {@inheritdoc}
	 */
	public function add($guid_one, $relationship, $guid_two, $return_id = false) {
		$rel = $this->check($guid_one, $relationship, $guid_two);
		if ($rel) {
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

		$dbprefix = _elgg_config()->dbprefix;

		// Insert a new relationship
		$sql = "
			INSERT INTO {$dbprefix}entity_relationships
			       (guid_one, relationship, guid_two, time_created)
			VALUES (:guid1, :relationship, :guid2, :time)
		";

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':guid1' => $row->guid_one,
				':guid2' => $row->guid_two,
				':relationship' => $row->relationship,
				':time' => $row->time_created
			],
			'insert_id' => $row->id,
		]);

		// Get relationship by its ID
		$sql = "
			SELECT * FROM {$dbprefix}entity_relationships
			WHERE id = :id
		";

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':id' => (int) $row->id,
			],
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				return [];
			},
		]);

		// Delete relationship by its ID
		$sql = "
			DELETE FROM {$dbprefix}entity_relationships
			WHERE id = :id
		";

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':id' => (int) $row->id,
			],
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
		$sql = "
			SELECT * FROM {$dbprefix}entity_relationships
			WHERE guid_one = :guid1
			  AND relationship = :relationship
			  AND guid_two = :guid2
			LIMIT 1
		";

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':guid1' => (int) $row->guid_one,
				':guid2' => (int) $row->guid_two,
				':relationship' => $row->relationship,
			],
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				return [];
			},
		]);
	}

}
