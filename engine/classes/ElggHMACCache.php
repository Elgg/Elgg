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
	function __construct($max_age = 0) {
		$this->setVariable("max_age", $max_age);
	}
	

	/**
	 * Provides a pointer to the database object. Use this instead of
	 * to make mocking possible for unit tests.
	 *
	 * @return ElggDatabase The database where this data is (will be) stored.
	 */
	protected function getDatabase() {
		return elgg_get_database();	
	}

	/**
	 * Save a key
	 *
	 * @param string $key  Name
	 * @param string $data Value
	 *
	 * @return boolean
	 */
	public function save($key, $data) {
		global $CONFIG;

		$key = sanitise_string($key);
		$time = time();

		$query = "INSERT into {$CONFIG->dbprefix}hmac_cache (hmac, ts) VALUES ('$key', '$time')";
		return $this->getDatabase()->insertData($query);
	}

	/**
	 * Load a key
	 *
	 * @param string $key    Name
	 * @param int    $offset Offset
	 * @param int    $limit  Limit
	 *
	 * @return string
	 */
	public function load($key, $offset = 0, $limit = null) {
		global $CONFIG;

		$key = sanitise_string($key);

		$row = $this->getDatabase()->getDataRow("SELECT * from {$CONFIG->dbprefix}hmac_cache where hmac='$key'");
		if ($row) {
			return $row->hmac;
		}

		return false;
	}

	/**
	 * Invalidate a given key.
	 *
	 * @param string $key Name
	 *
	 * @return bool
	 */
	public function delete($key) {
		global $CONFIG;

		$key = sanitise_string($key);

		return $this->getDatabase()->deleteData("DELETE from {$CONFIG->dbprefix}hmac_cache where hmac='$key'");
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
		global $CONFIG;

		$time = time();
		$age = (int)$this->getVariable("max_age");

		$expires = $time - $age;

		$this->getDatabase()->deleteData("DELETE from {$CONFIG->dbprefix}hmac_cache where ts<$expires");
	}
}
