<?php
/**
 * Tables are read-only by default.
 * 
 * Use WritableTable if you want insert/update/delete access.
 */
interface Table {
	/**
	 * Query the table for rows.
	 * 
	 * @param TableRef $tableRef
	 * 
	 * @return Query
	 */
	public function fromSelf(TableRef &$tableRef);
}