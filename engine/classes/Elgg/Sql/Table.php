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
interface Table {
	/**
	 * Query the table for rows.
	 * 
	 * @param TableRef $tableRef
	 * 
	 * @return WritableQuery
	 */
	public function fromSelf(TableRef &$tableRef);

	/**
	 * Inserts a new row into the table.
	 * 
	 * @param array $columnsToValues Keys are column names. Values are column values.
	 * 
	 * @return string The id of the new row, if applicable.
	 */
	public function insert(array $columnsToValues = array());
}