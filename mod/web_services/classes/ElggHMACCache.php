<?php

/**
 * ElggHMACCache
 * Store cached data in a temporary database, only used by the HMAC stuff.
 *
 * @package    Elgg.Core
 * @subpackage HMAC
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
	 * Save a key
	 *
	 * @param string $key          Name
	 * @param string $data         Value
	 * @param int    $expire_after Number of seconds to expire cache after
	 *
	 * @return bool
	 */
	public function save($key, $data, $expire_after = null) {
		$dbprefix = elgg()->db->prefix;
		
		$query = "INSERT into {$dbprefix}hmac_cache (hmac, ts)
			VALUES (:hmac, :time)";
		$params = [
			':hmac' => $key,
			':time' => time(),
		];
		
		return (bool) elgg()->db->insertData($query, $params);
	}

	/**
	 * Load a key
	 *
	 * @param string $key    Name
	 * @param int    $offset Offset
	 * @param int    $limit  Limit
	 *
	 * @return mixed|null The stored data or null if it's a miss
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
	 * Invalidate a given key.
	 *
	 * @param string $key Name
	 *
	 * @return bool
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
	 * Clear out all the contents of the cache.
	 *
	 * Not currently implemented in this cache type.
	 *
	 * @return true
	 */
	public function clear() {
		return true;
	}

	/**
	 * Clean out old stuff.
	 *
	 */
	public function __destruct() {
		$dbprefix = elgg()->db->prefix;
		$age = (int) $this->getVariable('max_age');

		$query = "DELETE FROM {$dbprefix}hmac_cache
			WHERE ts < :expires";
		$params = [
			':expires' => (time() - $age),
		];

		elgg()->db->deleteData($query, $params);
	}
}
