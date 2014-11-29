<?php

/**
 * A reference to a particular table in a query.
 */
interface TableRef {
	/**
	 * @param string $name
	 * 
	 * @return ColumnRef
	 */
	public function column($name);
}