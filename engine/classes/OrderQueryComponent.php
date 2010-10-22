<?php
/**
 * @class OrderQueryComponent
 * Order the query results.
 * @see Query
 */
class OrderQueryComponent extends QueryComponent
{
	function __construct($table, $field, $order = "asc")
	{
		global $CONFIG;

		$this->table = $CONFIG->dbprefix . sanitise_string($table);
		$this->field = sanitise_string($field);
		$this->order = sanitise_string($order);
	}

	function __toString()
	{
		return "order by {$this->table}.{$this->field} {$this->order}";
	}
}
