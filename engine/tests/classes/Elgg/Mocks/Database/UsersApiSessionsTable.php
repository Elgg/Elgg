<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\UsersApiSessionsTable as dbUsersApiSessionsTable;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database;
use Elgg\Database\Delete;

class UsersApiSessionsTable extends dbUsersApiSessionsTable {
	
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
	
	public function __construct(Database $database, \ElggCrypto $crypto) {
		parent::__construct($database, $crypto);
		
		$this->setCurrentTime();
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function createToken(int $user_guid, int $expires = 60) {
		$token = $this->crypto->getRandomString(32, \ElggCrypto::CHARS_HEX);
		$expires = $this->getCurrentTime("+{$expires} minutes");
		
		self::$iterator++;
		
		$row = (object) [
			'id' => self::$iterator,
			'user_guid' => $user_guid,
			'token' => $token,
			'expires' => $expires->getTimestamp(),
		];
		
		$this->addQuerySpecs($row);
		$this->rows[$row->id] = $row;
		
		return $token;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getUserTokens(int $user_guid) {
		$result = [];
		foreach ($this->rows as $row) {
			if (!$row->user_guid === $user_guid) {
				continue;
			}
			$result[] = $row;
		}
		
		if (!empty($result)) {
			return $result;
		}
		
		return parent::getUserTokens($user_guid);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function validateToken(string $token) {
		foreach ($this->rows as $row) {
			if ($token !== $row->token) {
				continue;
			}
			
			if ($row->expires < $this->getCurrentTime()->getTimestamp()) {
				continue;
			}
			
			return $row->user_guid;
		}
		
		return parent::validateToken($token);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function removeToken(string $token) {
		
		foreach ($this->rows as $row) {
			if ($row->token !== $token) {
				continue;
			}
			
			$this->clearQuerySpecs($row);
			unset($this->rows[$row->id]);
			
			return true;
		}
		
		return parent::removeToken($token);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function removeExpiresTokens() {
		$result = 0;
		foreach ($this->rows as $row) {
			if ($row->expires > $this->getCurrentTime()->getTimestamp()) {
				continue;
			}
			
			$this->clearQuerySpecs($row);
			unset($this->rows[$row->id]);
			$result++;
		}
		
		return $result;
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
			'user_guid' => $insert->param($row->user_guid, ELGG_VALUE_GUID),
			'token' => $insert->param($row->token, ELGG_VALUE_STRING),
			'expires' => $insert->param($row->expires, ELGG_VALUE_TIMESTAMP),
		]);
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $insert->getSQL(),
			'params' => $insert->getParameters(),
			'insert_id' => $row->id,
		]);
		
		$select = Select::fromTable($this->table);
		$select->select('*')
			->where($select->compare('user_guid', '=', $row->user_guid, ELGG_VALUE_GUID));
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'result' => function() use ($row) {
				$result = [];
				
				foreach ($this->rows as $db_row) {
					if ($row->user_guid !== $db_row->user_guid) {
						continue;
					}
					$result[] = $db_row;
				}
				
				return empty($result) ? false : $result;
			},
		]);
		
		$select = Select::fromTable($this->table);
		$select->select('*')
			->where($select->compare('token', '=', $row->token, ELGG_VALUE_STRING))
			->andWhere($select->compare('expires', '>', $this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'result' => function() use ($row) {
				foreach ($this->rows as $db_row) {
					if ($row->token !== $db_row->token) {
						continue;
					}
					
					if ($db_row->expires < $this->getCurrentTime()->getTimestamp()) {
						continue;
					}
					
					return $db_row->user_guid;
				}
				
				return false;
			},
		]);
		
		$delete = Delete::fromTable($this->table);
		$delete->where($delete->compare('token', '=', $row->token, ELGG_VALUE_STRING));
		
		$this->query_specs[$row->id][] = $this->database->addQuerySpec([
			'sql' => $delete->getSQL(),
			'params' => $delete->getParameters(),
			'result' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					$this->clearQuerySpecs($row);
					unset($this->rows[$row->id]);
					
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
