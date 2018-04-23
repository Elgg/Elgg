<?php
/**
 * Cache logging information for later display
 *
 */

class ElggLogCache {
	protected $cache;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->cache = [];
	}

	/**
	 * Insert into cache
	 *
	 * @param mixed $data The log data to cache
	 *
	 * @return void
	 */
	public function insert($data) {
		$this->cache[] = $data;
	}

	/**
	 * Insert into cache from plugin hook
	 *
	 * @param string $hook   'debug'
	 * @param string $type   'log'
	 * @param bool   $result current return value
	 * @param array  $params Must have the data at $params['msg']
	 *
	 * @return void
	 */
	public function insertDump($hook, $type, $result, $params) {
		$this->insert($params['msg']);
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
