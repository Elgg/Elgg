<?php
interface Elgg_Database_Connection {
	function prepare($prepareString);
	function query();
	function quote($input, $type=ElggDatabase::PARAM_STR);
	function exec($statement);
	function lastInsertId($name = null);
	function beginTransaction();
	function commit();
	function rollBack();
	function errorCode();
	function errorInfo();
}