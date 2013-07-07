<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.9.0
 */
class Elgg_Database_MySqlDriver implements Elgg_Database_Driver {

	/** @var resource */
	protected $connection;

	/**
	 * {@inheritDoc}
	 */
	public function connect(array $config) {
		$requiredParameters = array('host', 'user', 'password', 'database');
		foreach ($requiredParameters as $param) {
			if (!isset($config[$param])) {
				throw new InvalidArgumentException("MySqlDriver::connect() requires '$param'.");
			}
		}

		$this->connection = mysql_connect($config['host'], $config['user'], $config['password'], true);
		if (!is_resource($this->connection)) {
			throw new DatabaseException();
		}

		if (!mysql_select_db($config['database'], $this->connection)) {
			throw new DatabaseException();
		}

		$this->query('SET NAMES utf8');
	}

	/**
	 * {@inheritDoc}
	 */
	public function disconnect() {
		mysql_close($this->connection);
	}

	/**
	 * {@inheritDoc}
	 */
	public function query($sql) {
		$results = mysql_query($sql, $this->connection);
		if (mysql_errno($this->connection)) {
			throw new DatabaseException(mysql_error($this->connection) . "\n\n QUERY: $sql");
		}

		return $results;
	}

	/**
	 * {@inheritDoc}
	 */
	public function fetchAll($results) {
		$return = array();
		while ($obj = mysql_fetch_object($results)) {
			$return[] = $obj;
		}

		return $return;
	}

	/**
	 * {@inheritDoc}
	 */
	public function freeResults($results) {
		mysql_free_result($results);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getInsertId() {
		return mysql_insert_id($this->connection);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRowCount() {
		return mysql_affected_rows($this->connection);
	}

	/**
	 * {@inheritDoc}
	 */
	public function escape($value, $type) {
		switch ($type) {
			case Elgg_Database_Driver::TEXT:
				$value = mysql_real_escape_string($value, $this->connection);
				break;
			case Elgg_Database_Driver::INT:
				$value = (int)$value;
				break;
			case Elgg_Database_Driver::UINT:
				$value = (int) $value;
				if ($value < 0) {
					$value = 0;
				}
				break;
			default:
				throw new InvalidArgumentException();
		}

		return $value;
	}
}
