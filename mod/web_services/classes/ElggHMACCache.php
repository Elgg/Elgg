<?php

use Elgg\Database\Delete;

/**
 * ElggHMACCache
 *
 * Store cached data in a temporary database, only used by the HMAC stuff.
 */
class ElggHMACCache extends ElggCache {
	
	/**
	 * Set the Elgg cache.
	 *
	 * @param int $max_age Maximum age in seconds, 0 if no limit.
	 */
	public function __construct($max_age = 0) {
		$this->setVariable('max_age', $max_age);
	}

	/**
	 * {@inheritDoc}
	 */
	public function save($key, $data, $expire_after = null) {
		$dbprefix = elgg()->db->prefix;
		
		$query = "INSERT into {$dbprefix}hmac_cache (hmac, ts)
			VALUES (:hmac, :time)";
		$params = [
			':hmac' => $key,
			':time' => time(),
		];
		
		return elgg()->db->insertData($query, $params) !== false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function load($key, $offset = 0, $limit = null) {
		$dbprefix = elgg()->db->prefix;
		
		$query = "SELECT *
			FROM {$dbprefix}hmac_cache
			WHERE hmac = :hmac";
		$params = [
			':hmac' => $key,
		];

		$row = elgg()->db->getDataRow($query, null, $params);
		if (!empty($row)) {
			return $row->hmac;
		}

		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($key) {
		$dbprefix = elgg()->db->prefix;
		
		$query = "DELETE FROM {$dbprefix}hmac_cache
			WHERE hmac = :hmac";
		$params = [
			':hmac' => $key,
		];

		return (bool) elgg()->db->deleteData($query, $params);
	}

	/**
	 * {@inheritDoc}
	 */
	public function clear() {
		$delete = Delete::fromTable('hmac_cache');
		
		elgg()->db->deleteData($delete);
		
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function purge() {
		$max_age = (int) $this->getVariable('max_age');
		
		$delete = Delete::fromTable('hmac_cache');
		$delete->where($delete->compare('ts', '<', time() - $max_age, ELGG_VALUE_INTEGER));
		
		elgg()->db->deleteData($delete);
		
		return true;
	}

	/**
	 * Currently not implemented in this cache type
	 *
	 * {@inheritDoc}
	 */
	public function invalidate() {
		return true;
	}

	/**
	 * Clean out old stuff
	 */
	public function __destruct() {
		$this->purge();
	}
}
