<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\AccessCollections as DbAccessCollections;
use Elgg\Database\Insert;
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
	 * @var array
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

		$qb = Insert::intoTable($this->table);
		$qb->values([
			'name' => $qb->param($row->name, ELGG_VALUE_STRING),
			'subtype' => $qb->param($row->subtype, ELGG_VALUE_STRING),
			'owner_guid' => $qb->param((int) $row->owner_guid, ELGG_VALUE_INTEGER),
		]);
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'insert_id' => $row->id,
		]);
	}
}
