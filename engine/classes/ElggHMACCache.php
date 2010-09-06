<?php
/**
 * ElggHMACCache
 * Store cached data in a temporary database, only used by the HMAC stuff.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage API
 */
class ElggHMACCache extends ElggCache {
	/**
	 * Set the Elgg cache.
	 *
	 * @param int $max_age Maximum age in seconds, 0 if no limit.
	 */
	function __construct($max_age = 0) {
		$this->set_variable("max_age", $max_age);
	}

	/**
	 * Save a key
	 *
	 * @param string $key
	 * @param string $data
	 * @return boolean
	 */
	public function save($key, $data) {
		global $CONFIG;

		$key = sanitise_string($key);
		$time = time();

		return insert_data("INSERT into {$CONFIG->dbprefix}hmac_cache (hmac, ts) VALUES ('$key', '$time')");
	}

	/**
	 * Load a key
	 *
	 * @param string $key
	 * @param int $offset
	 * @param int $limit
	 * @return string
	 */
	public function load($key, $offset = 0, $limit = null) {
		global $CONFIG;

		$key = sanitise_string($key);

		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}hmac_cache where hmac='$key'");
		if ($row) {
			return $row->hmac;
		}

		return false;
	}

	/**
	 * Invalidate a given key.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function delete($key) {
		global $CONFIG;

		$key = sanitise_string($key);

		return delete_data("DELETE from {$CONFIG->dbprefix}hmac_cache where hmac='$key'");
	}

	/**
	 * Clear out all the contents of the cache.
	 *
	 * Not currently implemented in this cache type.
	 */
	public function clear() {
		return true;
	}

	/**
	 * Clean out old stuff.
	 *
	 */
	public function __destruct() {
		global $CONFIG;

		$time = time();
		$age = (int)$this->get_variable("max_age");

		$expires = $time-$age;

		delete_data("DELETE from {$CONFIG->dbprefix}hmac_cache where ts<$expires");
	}
}