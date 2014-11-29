<?php
/**
 * 
 */
interface WriteableTable {
	/**
	 * Inserts a new row into the table.
	 * 
	 * @param array $columnsToValues Keys are column names. Values are column values.
	 * 
	 * @return string The id of the new row, if applicable.
	 */
	public function insert(array $columnsToValues = array());
	
	/**
	 * @inheritDoc
	 * @return WriteableQuery
	 */
	public function fromSelf(TableRef &$tableRef);
}