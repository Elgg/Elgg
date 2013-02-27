<?php
class Elgg_Database_Driver_Default_Connection implements Elgg_Database_Connection {
	
	/**
	 * @var resource
	 */
	private $dblink;
	
	function __construct($dblink) {
		$this->dblink = $dblink;
	}
	
	function prepare($prepareString) {
		throw new NotImplementedException("Not implemented");
	}
	
	function query() {
		$query = func_get_arg(0);
		return new Elgg_Database_Driver_Default_Statement(mysql_query($query, $this->dblink), $this->dblink);
	}
	
	function quote($input, $type=\PDO::PARAM_STR) {
		throw new NotImplementedException("Not implemented");
	}
	
	function exec($statement) {
		throw new NotImplementedException("Not implemented");
	}
	
	function lastInsertId($name = null) {
		return mysql_insert_id($this->dblink);
	}
	
	function beginTransaction() {
		throw new NotImplementedException("Not implemented");
	}
	
	function commit() {
		throw new NotImplementedException("Not implemented");
	}
	
	function rollBack() {
		throw new NotImplementedException("Not implemented");
	}
	
	function errorCode() {
		return mysql_errno($this->dblink);
	}
	
	function errorInfo() {
		return mysql_error($this->dblink);
	}
}