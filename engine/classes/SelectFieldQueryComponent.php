<?php
/**
 * @class SelectFieldQueryComponent Class representing a select field.
 * This class represents a select field component.
 * @author Curverider Ltd
 * @see Query
 */
class SelectFieldQueryComponent extends QueryComponent
{
	/**
	 * Construct a select field component
	 *
	 * @param string $table The table containing the field.
	 * @param string $field The field or "*"
	 */
	function __construct($table, $field)
	{
		global $CONFIG;

		$this->table = $CONFIG->dbprefix . sanitise_string($table);
		$this->field = sanitise_string($field);
	}

	function __toString()
	{
		return "{$this->table}.{$this->field}";
	}
}
