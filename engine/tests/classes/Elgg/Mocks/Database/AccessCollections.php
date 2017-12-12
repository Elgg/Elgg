<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\AccessCollections as DbAccessCollections;
use stdClass;

class AccessCollections extends DbAccessCollections {

	/**
	 * @var stdClass[]
	 */
	public $rows = [];
	
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
	 * {@inheritDoc}
	 */
	public function create($name, $owner_guid = 0, $subtype = null) {

		$this->iterator++;
		$id = $this->iterator;
		
		$row = (object) [
			'id' => $id,
			'name' => $name,
			'owner_guid' => $owner_guid,
			'subtype' => $subtype,
		];

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::create($name, $owner_guid, $subtype);
	}
	
	/**
	 * Clear query specs
	 *
	 * @param int $id access collection ID
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
	 * Add query specs for a access collection data row
	 *
	 * @param stdClass $row Data row
	 * @return void
	 */
	public function addQuerySpecs(stdClass $row) {

		$this->clearQuerySpecs($row->id);

		$query = "
			INSERT INTO {$this->table}
			SET name = :name,
				subtype = :subtype,
				owner_guid = :owner_guid
		";

		$params = [
			':name' => $row->name,
			':subtype' => $row->subtype,
			':owner_guid' => (int) $row->owner_guid,
		];
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $query,
			'params' => $params,
			'insert_id' => $row->id,
		]);
	}
}
