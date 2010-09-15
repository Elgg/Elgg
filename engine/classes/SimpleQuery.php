<?php
/**
 * @class SimpleQuery A wrapper for Query which provides simple interface for common functions.
 *
 * This class provides simple interface functions for constructing a (reasonably) standard database
 * query.
 *
 * The constructor for this class sets a number of defaults, for example sets default access controls
 * and a limit and offset - to change this then set it manually.
 *
 * @author Curverider Ltd
 * @see Query
 */
class SimpleQuery extends Query
{
	function __construct()
	{
		parent::__construct();

		// Set a default query type (select)
		$this->simpleQueryType();

		// Set a default access control
		$this->simpleAccessControl();

		// Set default limit and offset
		$this->simpleLimitAndOffset();
	}

	/**
	 * Set the query type.
	 *
	 * @param string $type The type of search - available are "select", "update", "delete", "insert".
	 */
	public function simpleQueryType($type = "select")
	{
		$type = strtolower(sanitise_string($type));

		switch ($type)
		{
			case "insert" :
				return $this->setQueryType(InsertQueryTypeQueryComponent());
			break;
			case "delete" :
				return $this->setQueryType(DeleteQueryTypeQueryComponent());
			break;
			case "update" :
				return $this->setQueryType(UpdateQueryTypeQueryComponent());
			break;
			default: return $this->setQueryType(SelectQueryTypeQueryComponent());
		}
	}

	/**
	 * Set a field to query in a select statement.
	 *
	 * @param string $table Table to query.
	 * @param string $field Field in that table.
	 */
	public function simpleSelectField($table, $field) { return $this->setSelectField(new SelectFieldQueryComponent($table, $field)); }

	/**
	 * Add a select field to query in a select statement.
	 *
	 * @param string $table Table to query.
	 * @param string $field Field in that table.
	 */
	public function simpleAddSelectField($table, $field) { return $this->addSelectField(new SelectFieldQueryComponent($table, $field)); }

	/**
	 * Add a set value to an update query.
	 *
	 * @param string $table The table to update.
	 * @param string $field The field in the table.
	 * @param mixed $value The value to set it to.
	 */
	public function simpleSet($table, $field, $value) { return $this->addSet(new SetQueryComponent($table, $field, $value)); }

	/**
	 * Add a join to the table.
	 *
	 * @param string $table Table one to join...
	 * @param string $field Field 1 with...
	 * @param string $table2 Table 2 ...
	 * @param string $field2 Field...
	 * @param string $operator Using this operator
	 */
	public function simpleJoin($table1, $field1, $table2, $field2, $operator = "=") { return $this->addJoin(new JoinQueryComponent($table1, $field1, $table2, $field2, $operator)); }

	/**
	 * Add a table to the query.
	 *
	 * @param string $table The table.
	 */
	public function simpleTable($table) { return $this->addTable(new TableQueryComponent($table)); }

	/**
	 * Compare one table/field to another table/field.
	 *
	 * @param string $left_table The table on the left of the operator
	 * @param string $left_field The left field
	 * @param string $operator The operator eg "=" or "<"
	 * @param string $right_table The table on the right of the operator
	 * @param string $right_field The right field
	 * @param string $link_operator How this where clause links with the previous clause, eg. "and" "or"
	 */
	public function simpleWhereOnTable($left_table, $left_field, $operator, $right_table, $right_field, $link_operator = "and") { return $this->addWhere(new WhereQueryComponent($left_table, $left_field, $operator, $right_table, $right_field, $link_operator)); }

	/**
	 * Compare one table/field to a value.
	 *
	 * @param string $left_table The table on the left of the operator
	 * @param string $left_field The left field
	 * @param string $operator The operator eg "=" or "<"
	 * @param string $value The value
	 * @param string $link_operator How this where clause links with the previous clause, eg. "and" "or"
	 */
	public function simpleWhereOnValue($left_table, $left_field, $operator, $value, $link_operator = "and") { return $this->addWhere(new WhereStaticQueryComponent($left_table, $left_field, $operator, $value, $link_operator)); }

	/**
	 * Set access control.
	 *
	 * @param string $acl_table The table where the access control field is.
	 * @param string $acl_field The field containing the access control.
	 * @param string $object_owner_id_field The field in $object_owner_table containing the owner information.
	 */
	public function simpleAccessControl($acl_table = "entities", $acl_field = "access_id", $object_owner_id_field = "owner_guid") { return $this->setAccessControl(new AccessControlQueryComponent($acl_table, $acl_field, $acl_table, $object_owner_id_field)); }

	/**
	 * Set the limit and offset.
	 *
	 * @param int $limit The limit.
	 * @param int $offset The offset.
	 */
	public function simpleLimitAndOffset($limit = 25, $offset = 0) { return $this->setLimitAndOffset(new LimitOffsetQueryComponent($limit, $offset)); }

	/**
	 * Set the order query.
	 *
	 * @param string $table The table to query
	 * @param string $field The field to query
	 * @param string $order Order the query
	 */
	public function simpleOrder($table, $field, $order = "desc")
	{
		$table = sanitise_string($table);
		$field = sanitise_string($field);
		$order = strtolower(sanitise_string($order));

		return $this->setOrder(new OrderQueryComponent($table, $field, $order)); break;
	}
}
