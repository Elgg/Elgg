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
interface Elgg_Database_Driver {
	const TEXT = 'text';
	const INT = 'int';
	const UINT = 'uint';
	
	public function connect(array $config);
	public function disconnect();
	public function query($sql);
	public function fetchAll($results);
	public function freeResults($results);
	public function getInsertId();
	public function getRowCount();
	public function escape($value, $type);
}
