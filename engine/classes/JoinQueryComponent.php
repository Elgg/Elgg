<?php
/**
 * @class JoinQueryComponent Join query.
 * Represents a join query.
 * @author Curverider Ltd
 * @see Query
 */
class JoinQueryComponent extends QueryComponent
{
	/**
	 * Construct a join query.
	 * @param string $table Table one to join...
	 * @param string $field Field 1 with...
	 * @param string $table2 Table 2 ...
	 * @param string $field2 Field...
	 * @param string $operator Using this operator
	 */
	function __construct($table1, $field1, $table2, $field2, $operator = "=")
	{
		global $CONFIG;

		$this->table1 = $CONFIG->dbprefix . sanitise_string($table1);
		$this->field1 = sanitise_string($field1);
		$this->table2 = $CONFIG->dbprefix . sanitise_string($table2);
		$this->field2 = sanitise_string($field2);
		$this->operator = sanitise_string($operator);
	}

	function __toString()
	{
		return "join {$this->table2} on {$this->$table}.{$this->$field} {$this->$operator} {$this->$table2}.{$this->$field2}";
	}
}
