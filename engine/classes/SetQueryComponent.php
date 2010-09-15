<?php
/**
 * @class SetQueryComponent Set query.
 * Represents an update set query.
 * @author Curverider Ltd
 * @see Query
 */
class SetQueryComponent extends QueryComponent
{
	/**
	 * Construct a setting query
	 *
	 * @param string $table The table to modify
	 * @param string $field The field to modify
	 * @param mixed $value The value to set it to
	 */
	function __construct($table, $field, $value)
	{
		global $CONFIG;

		$this->table = $CONFIG->dbprefix . sanitise_string($table);
		$this->field = sanitise_string($field);
		if (is_numeric($value))
			$this->value = (int)$value;
		else
			$this->value = "'".sanitise_string($value)."'";
	}

	function __toString()
	{
		return "{$this->table}.{$this->field}={$this->value}";
	}
}
