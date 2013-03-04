<?php
interface Elgg_Database_Statement /*extends Traversable*/ {

	function bindValue($param, $value, $type = null);
	function bindParam($column, &$variable, $type = null, $length = null);
	function errorCode();
	function errorInfo();
	function execute($params = null);
	function rowCount();
	
	function closeCursor();
	function columnCount();
	function setFetchMode($fetchMode, $arg2 = null, $arg3 = null);
	function fetch($fetchMode = null);
	function fetchAll($fetchMode = null);
	function fetchColumn($columnIndex = 0);
}
