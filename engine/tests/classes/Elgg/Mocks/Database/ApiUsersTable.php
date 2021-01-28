<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\ApiUsersTable as dbApiUsersTable;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Delete;
use Elgg\Database\Update;

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
	public function getApiUser(string $public_api_key, bool $only_active = true) {
		
		foreach ($this->rows as $row) {
			if ($row->api_key !== $public_api_key) {
				continue;
			}
			
			if ($only_active && !$row->active) {
				return false;
			}
			
			return $row;
		}
		
		return parent::getApiUser($public_api_key, $only_active);
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
		
		// create
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
		
		// select (only active)
		$select = Select::fromTable($this->table);
		$select->select('*')
			->where($select->compare('api_key', '=', $row->api_key, ELGG_VALUE_STRING))
			->andWhere($select->compare('active', '=', $row->active, ELGG_VALUE_INTEGER));
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return $this->rows[$row->id];
				}
				
				return [];
			},
		]);
		
		// select (all)
		$select_all = Select::fromTable($this->table);
		$select_all->select('*')
			->where($select->compare('api_key', '=', $row->api_key, ELGG_VALUE_STRING));
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $select_all->getSQL(),
			'params' => $select_all->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return $this->rows[$row->id];
				}
				
				return [];
			},
		]);
		
		// delete
		$delete = Delete::fromTable($this->table);
		$delete->where($delete->compare('id', '=', $row->id, ELGG_VALUE_ID));
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $delete->getSQL(),
			'params' => $delete->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					unset($this->rows[$row->id]);
					$this->clearQuerySpecs($row);
					
					return [$row->id];
				}
				
				return [];
			},
		]);
		
		// enable
		$enable = Update::table($this->table);
		$enable = Update::table($this->table);
		$enable->set('active', $enable->param(1, ELGG_VALUE_INTEGER))
			->where($enable->compare('api_key', '=', $row->api_key, ELGG_VALUE_STRING));
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $enable->getSQL(),
			'params' => $enable->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					$this->rows[$row->id]->active = 1;
					
					return [$row->id];
				}
				
				return [];
			},
		]);
		
		// disable
		$disable = Update::table($this->table);
		$disable->set('active', $disable->param(0, ELGG_VALUE_INTEGER))
			->where($disable->compare('api_key', '=', $row->api_key, ELGG_VALUE_STRING));
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $disable->getSQL(),
			'params' => $disable->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					$this->rows[$row->id]->active = 0;
					
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
