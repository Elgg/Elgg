<?php
/**
 * @class WhereStaticQueryComponent
 * A component of a where query where there is no right hand table, rather a static value.
 * @see Query
 */
class WhereStaticQueryComponent extends WhereQueryComponent
{
	/**
	 * A where query.
	 *
	 * @param string $left_table The table on the left of the operator
	 * @param string $left_field The left field
	 * @param string $operator The operator eg "=" or "<"
	 * @param string $value The value
	 * @param string $link_operator How this where clause links with the previous clause, eg. "and" "or"
	 */
	function __construct($left_table, $left_field, $operator, $value, $link_operator = "and")
	{
		global $CONFIG;

		$this->link_operator = sanitise_string($link_operator);
		$this->left_table = $CONFIG->dbprefix . sanitise_string($left_table);
		$this->left_field = sanitise_string($left_field);
		$this->operator = sanitise_string($operator);
		if (is_numeric($value))
			$this->value = (int)$value;
		else
			$this->value = "'".sanitise_string($value)."'";
	}

	/**
	 * Return the SQL without the link operator.
	 */
	public function toStringNoLink()
	{
		return "{$this->left_table }.{$this->left_field} {$this->operator} {$this->value}";
	}
}
