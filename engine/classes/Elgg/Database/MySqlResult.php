<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * 
 * Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * http://framework.zend.com/license/new-bsd New BSD License
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.9.0
 */
class Elgg_Database_MySqlResult implements Elgg_Database_Result {

	/**
	 * @var resource Either a mysql_link or mysql_result resource
	 */
	protected $resource = null;

	/**
	 * Cursor position
	 * @var int
	 */
	protected $position = 0;

	/**
	 * Is the value available for the current position
	 * @var bool
	 */
	protected $currentAvailable = false;

	/**
	 * @var bool
	 */
	protected $currentData = false;

	/**
	 * @var mixed
	 */
	protected $generatedValue = null;

	/**
	 * Initialize
	 *
	 * @param resource $resource       results or link resource depending on query type
	 * @param int      $generatedValue The last insert id or 0
	 * @return Elgg_Database_MySqlResult
	 * @throws InvalidArgumentException
	 */
	public function initialize($resource, $generatedValue) {
		if (!is_resource($resource)) {
			throw new InvalidArgumentException('Invalid resource provided.');
		}

		$this->resource = $resource;
		$this->generatedValue = $generatedValue;
		return $this;
	}

	/**
	 * Return the resource
	 *
	 * @return resource
	 */
	public function getResource() {
		return $this->resource;
	}

	/**
	 * Get affected rows
	 *
	 * @return int
	 */
	public function getAffectedRows() {
		if (get_resource_type($this->resource) == "mysql result") {
			return mysql_num_rows($this->resource);
		} else {
			return mysql_affected_rows($this->resource);
		}
	}

	/**
	 * Current
	 *
	 * @return mixed
	 */
	public function current() {
		if ($this->currentAvailable) {
			return $this->currentData;
		}

		$this->loadFromMysqlResult();
		return $this->currentData;
	}

	/**
	 * Load from mysql result
	 *
	 * @return bool
	 */
	protected function loadFromMysqlResult() {
		$this->currentData = null;

		if (($data = mysql_fetch_object($this->resource)) === false) {
			return false;
		}

		$this->currentData = $data;
		$this->currentAvailable = true;
		return true;
	}

	/**
	 * Next
	 *
	 * @return void
	 */
	public function next() {
		if ($this->currentAvailable == false) {
			// skipping
			$this->loadFromMysqlResult();
		}
		$this->currentAvailable = false;
		$this->position++;
	}

	/**
	 * Key
	 *
	 * @return mixed
	 */
	public function key() {
		return $this->position;
	}

	/**
	 * Rewind
	 *
	 * @return void
	 * @throws RuntimeException
	 */
	public function rewind() {
		if ($this->position !== 0) {
			throw new RuntimeException('Results cannot be rewound for multiple iterations');
		}
		$this->currentAvailable = false;
		$this->position = 0;
	}

	/**
	 * Valid
	 *
	 * @return bool
	 */
	public function valid() {
		if ($this->currentAvailable) {
			return true;
		}

		return $this->loadFromMysqlResult();
	}

	/**
	 * Count
	 *
	 * @return int
	 */
	public function count() {
		if (is_resource($this->resource)) {
			return mysql_num_rows($this->resource);
		} else {
			return 0;
		}
	}

	/**
	 * Get generated value
	 *
	 * @return int
	 */
	public function getGeneratedValue() {
		return $this->generatedValue;
	}

}
