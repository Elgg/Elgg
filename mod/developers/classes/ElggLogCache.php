<?php
/**
 * Cache logging information for later display
 *
 */

class ElggLogCache {
	protected $cache;

	public function __construct() {
		$this->cache = array();
	}

	/**
	 * Insert into cache
	 * 
	 * @param mixed $data The log data to cache
	 */
	public function insert($data) {
		$this->cache[] = $data;
	}

	/**
	 * Insert into cache from plugin hook
	 * 
	 * @param string $hook
	 * @param string $type
	 * @param bool   $result 
	 * @param array  $params Must have the data at $params['msg']
	 */
	public function insertDump($hook, $type, $result, $params) {
		$this->insert($params['msg']);
		return false;
	}

	/**
	 * Get the cache
	 *
	 * @return array
	 */
	public function get() {
		return $this->cache;
	}
}
