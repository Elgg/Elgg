<?php

/**
 * Database Exception
 *
 * A generic parent class for database exceptions
 *
 * @package    Elgg.Core
 * @subpackage Exceptions.Stub
 */
class DatabaseException extends \Exception {

	/**
	 * @var string
	 */
	private $sql;

	/**
	 * @var array
	 */
	private $params;

	/**
	 * Set query
	 *
	 * @param string $sql SQL query
	 *
	 * @return void
	 */
	public function setQuery($sql) {
		$this->sql = $sql;
	}

	/**
	 * Get query
	 * @return string
	 */
	public function getQuery() {
		return $this->sql;
	}

	/**
	 * Set query parameters
	 *
	 * @param array $params Params
	 *
	 * @return void
	 */
	public function setParameters(array $params) {
		$this->params = $params;
	}

	/**
	 * Get params
	 * @return array
	 */
	public function getParameters() {
		return $this->params;
	}
}
