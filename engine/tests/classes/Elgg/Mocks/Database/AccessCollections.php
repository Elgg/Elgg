<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\AccessCollections as DbAccessCollections;
use Elgg\Database\Insert;

class AccessCollections extends DbAccessCollections {

	/**
	 * @var \stdClass[]
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
	 *
	 * @return void
	 */
	public function clearQuerySpecs(int $id): void {
		if (!empty($this->query_specs[$id])) {
			foreach ($this->query_specs[$id] as $spec) {
				$this->db->removeQuerySpec($spec);
			}
		}
	}

	/**
	 * Add query specs for a access collection data row
	 *
	 * @param \stdClass $row Data row
	 *
	 * @return void
	 */
	public function addQuerySpecs(\stdClass $row): void {

		$this->clearQuerySpecs($row->id);

		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'name' => $insert->param($row->name, ELGG_VALUE_STRING),
			'subtype' => $insert->param($row->subtype, ELGG_VALUE_STRING),
			'owner_guid' => $insert->param($row->owner_guid, ELGG_VALUE_GUID),
		]);
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $insert->getSQL(),
			'params' => $insert->getParameters(),
			'insert_id' => $row->id,
		]);
	}
}
