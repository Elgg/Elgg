<?php
namespace Elgg\Sql;

/**
 * API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * @package    Elgg.Core
 * @subpackage Sql
 * @since      1.11
 * 
 * @access private
 */
interface Database {
	/**
	 * Insert the values into the given table.
	 * 
	 * Column names are trusted
	 * 
	 * 
	 * @param string $tableName The unprefixed table name to insert values into.
	 * @param array  $values    columnName => value map. Will auto-escape raw input.
	 */
	public function insertInto($tableName, array $values);
}