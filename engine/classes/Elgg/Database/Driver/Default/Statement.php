<?php
class Elgg_Database_Driver_Default_Statement implements Elgg_Database_Statement {
	
	/**
	 * @var resource
	 */
	private $result;
	private $dblink;
	
	function __construct($result, $dblink) {
		$this->result = $result;
		$this->dblink = $dblink;
	}
	
	function bindValue($param, $value, $type = null) {
		throw new NotImplementedException("Not implemented");
	}
	
	function bindParam($column, &$variable, $type = null, $length = null) {
		throw new NotImplementedException("Not implemented");
	}
	
	function errorCode() {
		throw new NotImplementedException("Not implemented");
	}
	
	function errorInfo() {
		throw new NotImplementedException("Not implemented");
	}
	
	function execute($params = null) {
		throw new NotImplementedException("Not implemented");
	}
	
	function rowCount() {
		return mysql_affected_rows($this->dblink);
	}
	
	function closeCursor() {
		throw new NotImplementedException("Not implemented");
	}
	
	function columnCount() {
		throw new NotImplementedException("Not implemented");
	}
	
	function setFetchMode($fetchMode, $arg2 = null, $arg3 = null) {
		throw new NotImplementedException("Not implemented");
	}
	
	function fetch($fetchMode = null) {
		return mysql_fetch_object($this->result);
	}
	
	function fetchAll($fetchMode = null) {
		throw new NotImplementedException("Not implemented");
	}
	
	function fetchColumn($columnIndex = 0) {
		throw new NotImplementedException("Not implemented");
	}
}