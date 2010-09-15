<?php
/**
 * @class TableQueryComponent
 * List of tables to select from or insert into.
 * @author Curverider Ltd
 * @see Query
 */
class TableQueryComponent extends QueryComponent
{
	function __construct($table)
	{
		global $CONFIG;

		$this->table = $CONFIG->dbprefix . sanitise_string($table);
	}

	function __toString()
	{
		return $this->table;
	}
}
