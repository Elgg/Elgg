<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\UsersRememberMeCookiesTable as dbUsersRememberMeCookiesTable;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Delete;

class UsersRememberMeCookiesTable extends dbUsersRememberMeCookiesTable {
	
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
	 * @var bool
	 */
	protected $inserting = false;
	
	/**
	 * {@inheritDoc}
	 */
	public function insertHash(\ElggUser $user, string $hash): int {
		// lock the time to prevent testing issues
		$this->setCurrentTime();
		
		$row = (object) [
			'code' => $hash,
			'guid' => $user->guid,
			'timestamp' => $this->getCurrentTime()->getTimestamp(),
		];
		
		$this->addQuerySpecs($row);
		
		$this->inserting = true;
		
		try {
			$result = parent::insertHash($user, $hash);
		} catch (\Throwable $t) {
			$this->inserting = false;
			$this->resetCurrentTime();
			throw $t;
		}
		
		$this->inserting = false;
		$this->resetCurrentTime();
		
		return $result;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function updateHash(\ElggUser $user, string $hash): bool {
		if (!isset($this->rows[$hash])) {
			return false;
		}
		
		if ($this->rows[$hash]->guid !== $user->guid) {
			return false;
		}
		
		$this->rows[$hash]->timestamp = $this->getCurrentTime()->getTimestamp();
		
		return true;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function deleteHash(string $hash): int {
		if ($this->inserting) {
			return isset($this->query_specs[$hash]) ? 1 : 0;
		}
		
		return parent::deleteHash($hash);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function deleteExpiredHashes(int $expiration): int {
		$count = 0;
		
		foreach ($this->rows as $row) {
			if ($row->timestamp >= $expiration) {
				continue;
			}
			
			$count++;
			$this->clearQuerySpecs($row);
		}
		
		return $count;
	}
	
	protected function addQuerySpecs($row): void {
		$this->clearQuerySpecs($row);
		
		$this->rows[$row->code] = $row;
		
		// insert
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'code' => $insert->param($row->code, ELGG_VALUE_STRING),
			'guid' => $insert->param($row->guid, ELGG_VALUE_GUID),
			'timestamp' => $insert->param($row->timestamp, ELGG_VALUE_TIMESTAMP),
		]);
		
		$this->query_specs[$row->code][] = $this->database->addQuerySpec([
			'sql' => $insert->getSQL(),
			'params' => $insert->getParameters(),
			'insert_id' => 0,
		]);
		
		// select
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('code', '=', $row->code, ELGG_VALUE_STRING));
		
		$this->query_specs[$row->code][] = $this->database->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => function() use ($row) {
				$result = [];
				
				foreach ($this->rows as $db_row) {
					if ($row->code !== $db_row->code) {
						continue;
					}
					$result[] = $db_row;
				}
				
				return $result;
			},
		]);
		
		// delete single
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('code', '=', $row->code, ELGG_VALUE_STRING));
		
		$this->query_specs[$row->code][] = $this->database->addQuerySpec([
			'sql' => $delete->getSQL(),
			'params' => $delete->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->code])) {
					$this->clearQuerySpecs($row);
					
					return [$row->code];
				}
				
				return [];
			},
		]);
		
		// delete all from user
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('guid', '=', $row->guid, ELGG_VALUE_GUID));
		
		$this->query_specs[$row->code][] = $this->database->addQuerySpec([
			'sql' => $delete->getSQL(),
			'params' => $delete->getParameters(),
			'results' => function() use ($row) {
				$result = [];
				
				foreach ($this->rows as $db_row) {
					if ($row->guid !== $db_row->guid) {
						continue;
					}
					
					$result[] = $row->code;
					$this->clearQuerySpecs($db_row);
				}
				
				return $result;
			},
		]);
	}
	
	protected function clearQuerySpecs($row): void {
		if (!isset($this->query_specs[$row->code])) {
			return;
		}
		
		foreach ($this->query_specs[$row->code] as $spec) {
			$this->database->removeQuerySpec($spec);
		}
		
		unset($this->query_specs[$row->code]);
		unset($this->rows[$row->code]);
	}
}
