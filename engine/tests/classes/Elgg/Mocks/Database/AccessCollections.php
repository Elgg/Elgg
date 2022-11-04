<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\AccessCollections as DbAccessCollections;
use Elgg\Database\Insert;

class AccessCollections extends DbAccessCollections {

	/**
	 * @var \stdClass[]
	 */
	protected $rows = [];
	
	/**
	 * @var int
	 */
	protected static $iterator = 100;
	
	/**
	 * @var array
	 */
	protected $query_specs;
	
	/**
	 * {@inheritDoc}
	 */
	public function create(\ElggAccessCollection $acl): bool {

		static::$iterator++;
		$id = static::$iterator;
		
		$row = (object) [
			'id' => $id,
			'name' => $acl->name,
			'owner_guid' => $acl->owner_guid,
			'subtype' => $acl->subtype,
		];

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::create($acl);
	}
	
	/**
	 * Clear query specs
	 *
	 * @param int $id access collection ID
	 *
	 * @return void
	 */
	protected function clearQuerySpecs(int $id): void {
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
	protected function addQuerySpecs(\stdClass $row): void {

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
