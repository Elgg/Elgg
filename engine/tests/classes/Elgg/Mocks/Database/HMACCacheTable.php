<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\Delete;
use Elgg\Database\HMACCacheTable as dbHMACCacheTable;
use Elgg\Database\Insert;
use Elgg\Database\Select;

class HMACCacheTable extends dbHMACCacheTable {

	/**
	 * @var \stdClass[]
	 */
	protected $rows = [];
	
	/**
	 * DB query query_specs
	 * @var array
	 */
	protected $query_specs = [];
	
	/**
	 * {@inheritDoc}
	 */
	public function __destruct() {
		$this->query_specs = [];
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function storeHMAC(string $hmac) {
		$row = (object) [
			'hmac' => $hmac,
			'ts' => $this->getCurrentTime()->getTimestamp(),
		];
		
		$this->rows[$row->hmac] = $row;
		$this->addQuerySpecs($row);
		
		return parent::storeHMAC($hmac);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function loadHMAC(string $hmac) : ?string {
		if (isset($this->rows[$hmac])) {
			return $this->rows[$hmac]->hmac;
		}
		
		return parent::loadHMAC($hmac);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function deleteHMAC(string $hmac) : int {
		parent::deleteHMAC($hmac);
		
		if (!isset($this->rows[$hmac])) {
			return 0;
		}
		
		$row = $this->rows[$hmac];
		$this->clearQuerySpecs($row);
		
		unset($this->rows[$hmac]);
		
		return 1;
	}
	
	/**
	 * Add query query_specs
	 *
	 * @param \stdClass $row Data row
	 *
	 * @return void
	 */
	protected function addQuerySpecs(\stdClass $row) {
		
		$this->clearQuerySpecs($row);
		
		$insert = Insert::intoTable($this->table);
		$insert->values([
			'hmac' => $insert->param($row->hmac, ELGG_VALUE_STRING),
			'ts' => $insert->param($row->ts, ELGG_VALUE_TIMESTAMP),
		]);
		
		$this->query_specs[$row->hmac][] = $this->database->addQuerySpec([
			'sql' => $insert->getSQL(),
			'params' => $insert->getParameters(),
		]);
		
		$select = Select::fromTable($this->table);
		$select->select('*');
		$select->where($select->compare('hmac', '=', $row->hmac, ELGG_VALUE_STRING));
		
		$this->query_specs[$row->hmac][] = $this->database->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'result' => function() use ($row) {
				if (isset($this->rows[$row->hmac])) {
					return $this->rows[$row->hmac]->hmac;
				}
			},
		]);
		
		$delete = Delete::fromTable($this->table);
		$delete->where($delete->compare('hmac', '=', $row->hmac, ELGG_VALUE_STRING));
		
		$this->query_specs[$row->hmac][] = $this->database->addQuerySpec([
			'sql' => $delete->getSQL(),
			'params' => $delete->getParameters(),
			'result' => function() use ($row) {
				if (isset($this->rows[$row->hmac])) {
					unset($this->rows[$row->hamc]);
					$this->clearQuerySpecs($row);
					
					return 1;
				}
				
				return 0;
			},
		]);
	}
	
	/**
	 * Clear query specs
	 *
	 * @param \stdClass $row Data row
	 * @return void
	 */
	protected function clearQuerySpecs(\stdClass $row) {
		if (!isset($this->query_specs[$row->hmac])) {
			return;
		}
		
		foreach ($this->query_specs[$row->hmac] as $spec) {
			$this->database->removeQuerySpec($spec);
		}
		
		unset($this->query_specs[$row->hmac]);
	}
}
