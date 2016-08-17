<?php

namespace Elgg\Mocks\Database;

use Elgg\Database;
use Elgg\Database\EntityTable as DbEntityTable;
use Elgg\Database\MetadataTable as DbMetadataTable;
use Elgg\Database\RelationshipsTable as DbRelationshipsTable;
use Elgg\EventsService;
use ElggMetadata;
use ElggRelationship;

class RelationshipsTable extends DbRelationshipsTable {

	/**
	 * @var ElggMetadata
	 */
	public $mocks = [];

	/**
	 * @var int
	 */
	private $iterator = 100;

	/**
	 *
	 * @var type@var array
	 */
	private $query_specs;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(Database $db, DbEntityTable $entities, DbMetadataTable $metadata, EventsService $events) {
		parent::__construct($db, $entities, $metadata, $events);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($id, $call_event = true) {
		if ($result = parent::delete($id, $call_event)) {
			unset($this->mocks[$id]);
			if (!empty($this->query_specs[$id])) {
				foreach ($this->query_specs[$id] as $spec) {
					$this->db->removeQuerySpec($spec);
				}
			}
		}
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function add($guid_one, $relationship, $guid_two) {
		$rel = $this->check($guid_one, $relationship, $guid_two);
		if ($rel) {
			return false;
		}

		$this->setCurrentTime();

		$this->iterator++;
		$id = $this->iterator;

		$row = (object) [
			'id' => $id,
			'guid_one' => $guid_one,
			'guid_two' => $guid_two,
			'relationship' => $relationship,
			'time_created' => $this->getCurrentTime()->getTimestamp(),
		];

		// Insert a new relationship
		$sql = "
			INSERT INTO {$this->db->prefix}entity_relationships
			       (guid_one, relationship, guid_two, time_created)
			VALUES (:guid1, :relationship, :guid2, :time)
				ON DUPLICATE KEY UPDATE time_created = :time
		";
		$params = [
			':guid1' => (int) $row->guid_one,
			':guid2' => (int) $row->guid_two,
			':relationship' => $row->relationship,
			':time' => $row->time_created
		];
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => $params,
			'insert_id' => $row->id,
		]);

		if ($result = parent::add($guid_one, $relationship, $guid_two)) {
			$this->addQuerySpecs($row);

			$rel = new ElggRelationship($row);
			$this->mocks[$id] = $rel;
		} else {
			$this->iterator--;
		}

		return $result;
	}

	public function addQuerySpecs(\stdClass $row) {

		// Get relationship by its ID
		$sql = "SELECT * FROM {$this->db->prefix}entity_relationships WHERE id = :id";
		$params = [
			':id' => (int) $row->id,
		];
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => $params,
			'results' => function() use ($row) {
				return [$row];
			},
		]);

		// Delete relationship by its ID
		$sql = "DELETE FROM {$this->db->prefix}entity_relationships WHERE id = :id";
		$params = [
			':id' => $row->id,
		];
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => $params,
			'row_count' => 1,
			'times' => 1,
		]);

		// Check relationship between two GUIDs
		$sql = "
			SELECT * FROM {$this->db->prefix}entity_relationships
			WHERE guid_one = :guid1
			  AND relationship = :relationship
			  AND guid_two = :guid2
			LIMIT 1
		";
		$params = [
			':guid1' => (int) $row->guid_one,
			':guid2' => (int) $row->guid_two,
			':relationship' => $row->relationship,
		];
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => $params,
			'results' => function() use ($row) {
				return [$row];
			},
		]);
	}

}
