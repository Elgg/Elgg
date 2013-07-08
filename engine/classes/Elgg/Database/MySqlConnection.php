<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
class Elgg_Database_MySqlConnection implements Elgg_Database_Connection {

	/**
	 * @var Elgg_Database_MySqlDriver
	 */
	protected $driver = null;

	/**
	 * Connection parameters
	 *
	 * @var array
	 */
	protected $connectionParameters = array();

	/**
	 * @var resource
	 */
	protected $resource = null;

	/**
	 * Constructor
	 *
	 * @param array $connectionInfo
	 */
	public function __construct(array $connectionInfo) {
		$this->setConnectionParameters($connectionInfo);
	}

	/**
	 * Set driver
	 * 
	 * @param Elgg_Database_MySqlDriver $driver
	 * @return Elgg_Database_MySqlConnection
	 */
	public function setDriver(Elgg_Database_MySqlDriver $driver) {
		$this->driver = $driver;
		return $this;
	}

	/**
	 * Set connection parameters
	 *
	 * @param array $connectionParameters
	 * @return Elgg_Database_MySqlConnection
	 */
	public function setConnectionParameters(array $connectionParameters) {
		$this->connectionParameters = $connectionParameters;
		return $this;
	}

	/**
	 * Get connection parameters
	 *
	 * @return array
	 */
	public function getConnectionParameters() {
		return $this->connectionParameters;
	}

	/**
	 * Connect
	 *
	 * @return Elgg_Database_MySqlConnection
	 * @throws InvalidArgumentException
	 */
	public function connect() {
		if (is_resource($this->resource)) {
			return $this;
		}

		$p = $this->connectionParameters;

		$requiredParameters = array('host', 'user', 'password', 'database');
		foreach ($requiredParameters as $param) {
			if (!isset($p[$param])) {
				throw new InvalidArgumentException("MySqlConnection::connect() requires '$param'.");
			}
		}

		$this->resource = mysql_connect($p['host'], $p['user'], $p['password'], true);
		if (!is_resource($this->resource)) {
			throw new DatabaseException();
		}

		if (!mysql_select_db($p['database'], $this->resource)) {
			throw new DatabaseException();
		}

		// @todo figure out how Zend expects this to be done
		mysql_query("SET NAMES utf8");

		return $this;
	}

	/**
	 * Is connected
	 *
	 * @return bool
	 */
	public function isConnected() {
		return (is_resource($this->resource));
	}

	/**
	 * Disconnect
	 *
	 * @return void
	 */
	public function disconnect() {
		if (is_resource($this->resource)) {
			mysql_close($this->resource);
		}
		unset($this->resource);
	}

	/**
	 * Execute
	 *
	 * @param string $sql
	 * @return Elgg_Database_MySqlResult
	 */
	public function execute($sql) {
		if (!$this->isConnected()) {
			$this->connect();
		}

		$results = mysql_query($sql, $this->resource);
		if ($results === false) {
			throw new DatabaseException(mysql_error($this->resource) . "\n\n QUERY: $sql");
		}

		return $this->driver->createResult(($results === true) ? $this->resource : $results);
	}

	/**
	 * Get last generated id
	 *
	 * @return int
	 */
	public function getLastGeneratedValue() {
		return mysql_insert_id($this->resource);
	}

}
