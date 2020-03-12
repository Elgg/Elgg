<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\ApiUsersTable as dbApiUsersTable;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Delete;

class ApiUsersTable extends dbApiUsersTable {

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
	 * @var int
	 */
	protected static $iterator = 100;
	
	/**
	 * {@inheritDoc}
	 */
	public function createApiUser() {
		$public = $this->crypto->getRandomString(40, \ElggCrypto::CHARS_HEX);
		$secret = $this->crypto->getRandomString(40, \ElggCrypto::CHARS_HEX);
		
		self::$iterator++;
		
		$row = (object) [
			'id' => self::$iterator,
			'api_key' => $public,
			'secret' => $secret,
			'active' => 1,
		];
		
		$this->addQuerySpecs($row);
		$this->rows[$row->id] = $row;
		
		return $this->getApiUser($public);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getApiUser(string $public_api_key) {
		
		foreach ($this->rows as $row) {
			if ($row->api_key !== $public_api_key) {
				continue;
			}
			
			return $row;
		}
		
		return parent::getApiUser($public_api_key);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function removeApiUser(string $public_api_key) {
		$row = $this->getApiUser($public_api_key);
		
		parent::removeApiUser($public_api_key);
		
		if (!isset($this->rows[$row->id])) {
			return false;
		}
		
		$this->clearQuerySpecs($row);
		unset($this->rows[$row->id]);
		
		return true;
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
			'api_key' => $insert->param($row->api_key, ELGG_VALUE_STRING),
			'secret' => $insert->param($row->secret, ELGG_VALUE_STRING),
		]);
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $insert->getSQL(),
			'params' => $insert->getParameters(),
			'insert_id' => $row->id,
		]);
		
		$select = Select::fromTable($this->table);
		$select->select('*')
			->where($select->compare('api_key', '=', $row->api_key, ELGG_VALUE_STRING))
			->andWhere($select->compare('active', '=', $row->active, ELGG_VALUE_INTEGER));
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'result' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return $this->rows[$row->id];
				}
				
				return [];
			},
		]);
		
		$delete = Delete::fromTable($this->table);
		$delete->where($delete->compare('id', '=', $row->id, ELGG_VALUE_ID));
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $delete->getSQL(),
			'params' => $delete->getParameters(),
			'result' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					unset($this->rows[$row->id]);
					$this->clearQuerySpecs($row);
					
					return [$row->id];
				}
				
				return [];
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
		if (!isset($this->query_specs[$row->id])) {
			return;
		}
		
		foreach ($this->query_specs[$row->id] as $spec) {
			$this->database->removeQuerySpec($spec);
		}
		
		unset($this->query_specs[$row->id]);
	}
}
