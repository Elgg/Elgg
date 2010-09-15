<?php
/**
 * @class WhereQueryComponent
 * A component of a where query.
 * @author Curverider Ltd
 * @see Query
 */
class WhereQueryComponent extends QueryComponent
{
	/**
	 * A where query.
	 *
	 * @param string $left_table The table on the left of the operator
	 * @param string $left_field The left field
	 * @param string $operator The operator eg "=" or "<"
	 * @param string $right_table The table on the right of the operator
	 * @param string $right_field The right field
	 * @param string $link_operator How this where clause links with the previous clause, eg. "and" "or"
	 */
	function __construct($left_table, $left_field, $operator, $right_table, $right_field, $link_operator = "and")
	{
		global $CONFIG;

		$this->link_operator = sanitise_string($link_operator);
		$this->left_table = $CONFIG->dbprefix . sanitise_string($left_table);
		$this->left_field = sanitise_string($left_field);
		$this->operator = sanitise_string($operator);
		$this->right_table = $CONFIG->dbprefix . sanitise_string($right_table);
		$this->right_field = sanitise_string($right_field);
	}

	/**
	 * Return the SQL without the link operator.
	 */
	public function toStringNoLink()
	{
		return "{$this->left_table }.{$this->left_field} {$this->operator} {$this->right_table}.{$this->right_field}";
	}

	function __toString()
	{
		return "{$this->link_operator} " . $this->toStringNoLink();
	}
}
