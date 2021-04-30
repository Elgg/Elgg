<?php

namespace Elgg\Database;

use Elgg\Database;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Traits\TimeUsing;

/**
 * Manage the contents of the hmac_cache table
 *
 * @since 4.0
 * @internal
 */
class HMACCacheTable {
	
	use TimeUsing;
	
	/**
	 * @var Database
	 */
	protected $database;
	
	/**
	 * @var int
	 */
	protected $ttl = -1;
	
	/**
	 * @var string Table being managed, DON'T CHANGE
	 */
	protected $table = 'hmac_cache';
	
	/**
	 * Create a new table handler
	 *
	 * @param Database $database the Elgg database handler
	 *
	 * @return void
	 */
	public function __construct(Database $database) {
		$this->database = $database;
	}
	
	/**
	 * Cleanup expired HMAC keys
	 *
	 * @return void
	 */
	public function __destruct() {
		if ($this->getTTL() < 0) {
			return;
		}
		
		$expires = $this->getCurrentTime("-{$this->getTTL()} seconds");
		
		$delete = Delete::fromTable($this->table);
		$delete->where($delete->compare('ts', '<', $expires->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		$this->database->deleteData($delete);
	}
	
	/**
	 * Set the Time-To-Live of HMAC keys
	 *
	 * @param int $ttl the max TTL of the HMAC keys in seconds (-1 is endless)
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 */
	public function setTTL(int $ttl = 0) {
		if ($ttl < -1) {
			throw new InvalidArgumentException(__METHOD__ . ': TTL needs to be greater than or equal to -1');
		}
		
		$this->ttl = $ttl;
	}
	
	/**
	 * Get the configured Time-To-Live of the HMAC keys
	 *
	 * @return int
	 */
	public function getTTL() : int {
		return $this->ttl;
	}
	
	/**
	 * Store a HMAC key for later use
	 *
	 * @param string $hmac the HMAC key
	 *
	 * @return int|false
	 */
	public function storeHMAC(string $hmac) {
		$insert = Insert::intoTable($this->table);
		$insert->values([
			'hmac' => $insert->param($hmac, ELGG_VALUE_STRING),
			'ts' => $insert->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP),
		]);
		
		return $this->database->insertData($insert);
	}
	
	/**
	 * Load a HMAC key from the database
	 *
	 * @param string $hmac the HMAC key
	 *
	 * @return string|null
	 */
	public function loadHMAC(string $hmac) : ?string {
		$select = Select::fromTable($this->table);
		$select->select('*');
		$select->where($select->compare('hmac', '=', $hmac, ELGG_VALUE_STRING));
		
		$row = $this->database->getDataRow($select);
		if (empty($row)) {
			return null;
		}
		
		return $row->hmac;
	}
	
	/**
	 * Delete a HMAC key from the database
	 *
	 * @param string $hmac the HMAC key
	 *
	 * @return int
	 */
	public function deleteHMAC(string $hmac) : int {
		$delete = Delete::fromTable($this->table);
		$delete->where($delete->compare('hmac', '=', $hmac, ELGG_VALUE_STRING));
		
		return $this->database->deleteData($delete);
	}
}
