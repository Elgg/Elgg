<?php

namespace Elgg\Database;

use Elgg\Database;
use Elgg\Exceptions\RangeException;
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
	 * @var string name of the hmac cache database table
	 */
	public const TABLE_NAME = 'hmac_cache';
	
	/**
	 * @var int HMAC lifetime is 25 hours (this should be related to the time drift allowed in header validation)
	 */
	protected int $ttl = 90000;
	
	/**
	 * Create a new table handler
	 *
	 * @param Database $database the Elgg database handler
	 */
	public function __construct(protected Database $database) {
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
		
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('ts', '<', $expires->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		$this->database->deleteData($delete);
	}
	
	/**
	 * Set the Time-To-Live of HMAC keys
	 *
	 * @param int $ttl the max TTL of the HMAC keys in seconds (-1 is endless)
	 *
	 * @return void
	 * @throws RangeException
	 */
	public function setTTL(int $ttl = 0): void {
		if ($ttl < -1) {
			throw new RangeException(__METHOD__ . ': TTL needs to be greater than or equal to -1');
		}
		
		$this->ttl = $ttl;
	}
	
	/**
	 * Get the configured Time-To-Live of the HMAC keys
	 *
	 * @return int
	 */
	public function getTTL(): int {
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
		$insert = Insert::intoTable(self::TABLE_NAME);
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
	public function loadHMAC(string $hmac): ?string {
		$select = Select::fromTable(self::TABLE_NAME);
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
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('hmac', '=', $hmac, ELGG_VALUE_STRING));
		
		return $this->database->deleteData($delete);
	}
}
