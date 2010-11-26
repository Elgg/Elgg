<?php
	/**
	 * Elgg database query
	 * Contains a wrapper for performing database queries in a structured way.
	 *
	 * @package Elgg
	 * @subpackage Core
	 */


	/**
	 * @class QueryComponent Query component superclass.
	 * Component of a query.
	 * @see Query
	 */
	abstract class QueryComponent
	{
		/**
		 * Associative array of fields and values
		 */
		private $fields;

		function __construct()
		{
			$this->fields = array();
		}

		/**
		 * Class member get overloading
		 *
		 * @param string $name
		 * @return mixed
		 */
		function __get($name) {
			return $this->fields[$name];
		}

		/**
		 * Class member set overloading
		 *
		 * @param string $name
		 * @param mixed $value
		 * @return void
		 */
		function __set($name, $value) {
			$this->fields[$name] = $value;

			return true;
		}
	}

	/**
	 * @class SelectFieldQueryComponent Class representing a select field.
	 * This class represents a select field component.
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

	/**
	 * @class LimitOffsetQueryComponent
	 * Limit and offset clauses of a query.
	 * @see Query
	 */
	class LimitOffsetQueryComponent extends QueryComponent
	{
		/**
		 * Specify a limit and an offset.
		 *
		 * @param int $limit The limit.
		 * @param int $offset The offset.
		 */
		function __construct($limit = 25, $offset = 0)
		{
			$this->limit = (int)$limit;
			$this->offset = (int)$offset;
		}

		function __toString()
		{
			return "limit {$this->offset}, {$this->limit}";
		}
	}

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

	/**
	 * @class TableQueryComponent
	 * List of tables to select from or insert into.
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

	/**
	 * @class AccessControlQueryComponent
	 * Access control component.
	 * @see Query
	 */
	class AccessControlQueryComponent extends QueryComponent
	{
		/**
		 * Construct the ACL.
		 *
		 * @param string $acl_table The table where the access control field is.
		 * @param string $acl_field The field containing the access control.
		 * @param string $object_owner_table The table containing the owner information for the stuff you're retrieving.
		 * @param string $object_owner_id_field The field in $object_owner_table containing the owner information
		 */
		function __construct($acl_table = "entities", $acl_field = "access_id", $object_owner_table = "entities", $object_owner_id_field = "owner_guid")
		{
			global $CONFIG;

			$this->acl_table = $CONFIG->dbprefix . sanitise_string($acl_table);
			$this->acl_field = sanitise_string($acl_field);
			$this->object_owner_table = $CONFIG->dbprefix . sanitise_string($object_owner_table);
			$this->object_owner_id_field = sanitise_string($object_owner_id_field);
		}

		function __toString()
		{
			//$access = get_access_list();
			// KJ - changed to use get_access_sql_suffix
			// Note: currently get_access_sql_suffix is hardwired to use
			// $acl_field = "access_id", $object_owner_table = $acl_table, and
			// $object_owner_id_field = "owner_guid"
			// @todo recode get_access_sql_suffix to make it possible to specify alternate field names
			return "and ".get_access_sql_suffix($this->acl_table); // Add access controls

			//return "and ({$this->acl_table}.{$this->acl_field} in {$access} or ({$this->acl_table}.{$this->acl_field} = 0 and {$this->object_owner_table}.{$this->object_owner_id_field} = {$_SESSION['id']}))";
		}
	}

	/**
	 * @class JoinQueryComponent Join query.
	 * Represents a join query.
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

	/**
	 * @class SetQueryComponent Set query.
	 * Represents an update set query.
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

	/**
	 * @class WhereQueryComponent
	 * A component of a where query.
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

	/**
	 * @class WhereSetQueryComponent
	 * A where query that may contain other where queries (in brackets).
	 * @see Query
	 */
	class WhereSetQueryComponent extends WhereQueryComponent
	{
		/**
		 * Construct a subset of wheres.
		 *
		 * @param array $wheres An array of WhereQueryComponent
		 * @param string $link_operator How this where clause links with the previous clause, eg. "and" "or"
		 */
		function __construct(array $wheres, $link_operator = "and")
		{
			$this->link_operator = sanitise_string($link_operator);
			$this->wheres = $wheres;
		}

		public function toStringNoLink()
		{
			$cnt = 0;
			$string = " (";
			foreach ($this->wheres as $where) {
				if (!($where instanceof WhereQueryComponent))
					throw new DatabaseException(elgg_echo('DatabaseException:WhereSetNonQuery'));

				if (!$cnt)
					$string.= $where->toStringNoLink();
				else
					$string.=" $where ";

				$cnt ++;
			}
			$string .= ")";

			return $string;
		}
	}

	/**
	 * @class QueryTypeQueryComponent
	 * What type of query is this?
	 * @see Query
	 */
	abstract class QueryTypeQueryComponent extends QueryComponent
	{
		function __toString()
		{
			return $this->query_type;
		}
	}

	/**
	 * @class SelectQueryTypeQueryComponent
	 * A select query.
	 * @see Query
	 */
	class SelectQueryTypeQueryComponent extends QueryTypeQueryComponent
	{
		function __construct()
		{
			$this->query_type = "SELECT";
		}
	}

	/**
	 * @class InsertQueryTypeQueryComponent
	 * An insert query.
	 * @see Query
	 */
	class InsertQueryTypeQueryComponent extends QueryTypeQueryComponent
	{
		function __construct()
		{
			$this->query_type = "INSERT INTO";
		}
	}

	/**
	 * @class DeleteQueryTypeQueryComponent
	 * A delete query.
	 * @see Query
	 */
	class DeleteQueryTypeQueryComponent extends QueryTypeQueryComponent
	{
		function __construct()
		{
			$this->query_type = "DELETE FROM";
		}
	}

	/**
	 * @class UpdateQueryTypeQueryComponent
	 * An update query.
	 * @see Query
	 */
	class UpdateQueryTypeQueryComponent extends QueryTypeQueryComponent
	{
		function __construct()
		{
			$this->query_type = "UPDATE";
		}
	}

	/**
	 * @class Query Provides a framework to construct complex queries in a safer environment.
	 *
	 * The usage of this class depends on the type of query you are executing, but the basic idea is to
	 * construct a query out of pluggable classes.
	 *
	 * Once constructed SQL can be generated using the toString method, this should happen automatically
	 * if you pass the Query object to get_data or similar.
	 *
	 * To construct a query, create a new Query() object and begin populating it with the various classes
	 * that define the various aspects of the query.
	 *
	 * Notes:
	 * 	- You do not have to specify things in any particular order, provided you specify all required
	 * 	  components.
	 *  - With database tables you do not have to specify your db prefix, this will be added automatically.
	 *  - When constructing your query keep an eye on the error log - any problems will get spit out here.
	 * 	  Note also that __toString won't let you throw Exceptions (!!!) so these are caught and echoed to
	 *    the log instead.
	 *
	 * Here is an example of a select query which requests some data out of the entities table with an
	 * order and limit that uses a subset where and some normal where queries:
	 *
	 * <blockquote>
	 * 		// Construct the query
	 * 		$query = new Query();
	 *
	 * 		// Say which table we're interested in
	 * 		$query->addTable(new TableQueryComponent("entities"));
	 *
	 * 		// What fields are we interested in
	 * 		$query->addSelectField(new SelectFieldQueryComponent("entities","*"));
	 *
	 * 		// Add access control (Default access control uses default fields on entities table.
	 * 		// Note that it will error without something specified here!
	 * 		$query->setAccessControl(new AccessControlQueryComponent());
	 *
	 * 		// Set a limit and offset, may be omitted.
	 * 		$query->setLimitAndOffset(new LimitOffsetQueryComponent(10,0));
	 *
	 * 		// Specify the order, may be omitted
	 * 		$query->setOrder(new OrderQueryComponent("entities", "subtype", "desc"));
	 *
	 * 		// Construct a where query
	 * 		//
	 * 		// This demonstrates a WhereSet which lets you have sub wheres, a
	 * 		// WhereStatic which lets you compare a table field against a value and a
	 * 		// Where which lets you compare a table/field with another table/field.
	 * 		$query->addWhere(
	 * 			new WhereSetQueryComponent(
	 * 				array(
	 * 					new WhereStaticQueryComponent("entities", "subtype","=", 1),
	 * 					new WhereQueryComponent("entities","subtype","=", "entities", "subtype")
	 * 				)
	 * 			)
	 * 		);
	 *
	 * 		get_data($query);
	 * </blockquote>
	 *
	 */
	class Query
	{

		/// The limit of the query
		private $limit_and_offset;

		/// Fields to return on a query
		private $fields;

		/// Tables to use in a from query
		private $tables;

		/// Join tables
		private $joins;

		/// Set values
		private $sets;

		/// Where query
		private $where;

		/// Order by
		private $order;

		/// The query type
		private $query_type;

		/// ACL
		private $access_control;

		/**
		 * Construct query & initialise variables
		 */
		function __construct()
		{
			$this->fields = array();
			$this->tables = array();
			$this->joins = array();
			$this->where = array();
			$this->sets = array();

			$this->setQueryType(new SelectQueryTypeQueryComponent());
		}

		/**
		 * Add limits and offsets to the query.
		 *
		 * @param LimitOffsetQueryComponent $component The limit and offset.
		 */
		public function setLimitAndOffset(LimitOffsetQueryComponent $component) { $this->limit_and_offset = $component; }

		/**
		 * Reset and set the field to the select statement.
		 *
		 * @param SelectFieldQueryComponent $component Table and field component.
		 */
		public function setSelectField(SelectFieldQueryComponent $component)
		{
			$this->fields = array();
			return $this->addSelectField($component);
		}

		/**
		 * Add a select field.
		 *
		 * @param SelectFieldQueryComponent $component Add a component.
		 */
		public function addSelectField(SelectFieldQueryComponent $component) { $this->fields[] = $component; }

		/**
		 * Add a join to the component.
		 *
		 * @param JoinQueryComponent $component The join.
		 */
		public function addJoin(JoinQueryComponent $component) { $this->joins[] = $component; }

		/**
		 * Set a field value in an update or insert statement.
		 *
		 * @param SetQueryComponent $component Fields to set.
		 */
		public function addSet(SetQueryComponent $component) { $this->sets[] = $component; }

		/**
		 * Set the query type, i.e. "select", "update", "insert" & "delete".
		 *
		 * @param QueryTypeQueryComponent $component The query type.
		 */
		public function setQueryType(QueryTypeQueryComponent $component) { $this->query_type = $component; }

		/**
		 * Attach an order component.
		 *
		 * @param OrderQueryComponent $component The order component.
		 */
		public function setOrder(OrderQueryComponent $component) { $this->order = $component; }

		/**
		 * Add a table to the query.
		 *
		 * @param TableQueryComponent $component Table to add.
		 */
		public function addTable(TableQueryComponent $component) { $this->tables[] = $component; }

		/**
		 * Add a where clause to the query.
		 *
		 * @param WhereQueryComponent $component The where component
		 */
		public function addWhere(WhereQueryComponent $component) { $this->where[] = $component; }

		/**
		 * Set access control.
		 *
		 * @param AccessControlQueryComponent $component Access control.
		 */
		public function setAccessControl(AccessControlQueryComponent $component) { $this->access_control = $component; }

		public function __toString()
		{
			global $CONFIG;

			$sql = "";

			try
			{
				// Query prefix & fields
				if (!empty($this->query_type))
				{
					$sql .= "{$this->query_type} ";

					if (!empty($this->fields))
					{
						$fields = "";

						foreach ($this->fields as $field)
							$fields .= "$field";

						$sql .= " $fields from ";
					}
					else
						throw new DatabaseException(elgg_echo('DatabaseException:SelectFieldsMissing'));
				}
				else
					throw new DatabaseException(elgg_echo('DatabaseException:UnspecifiedQueryType'));

				// Tables
				if (!empty($this->tables))
				{
					foreach($this->tables as $table)
						$sql .= "$table, ";

					$sql = trim($sql, ", ");
				}
				else
					throw new DatabaseException(elgg_echo('DatabaseException:NoTablesSpecified'));

				// Joins on select queries
				if ($this->query_type->query_type == 'select')
				{
					if (!empty($this->joins))
					{
						foreach($this->joins as $join)
							$sql .= "$join ";
					}
				}

				// Setting values
				if (
					($this->query_type->query_type == 'update') ||
					($this->query_type->query_type == 'insert')
				)
				{
					$sql .= "set ";

					foreach ($this->sets as $set)
						$sql .= "$set, ";

					$sql = trim($sql, ", ") . " ";
				}

				// Where
				if (!empty($this->where))
				{
					$sql .= " where 1 ";

					foreach ($this->where as $where)
						$sql .= "$where ";
				}

				// Access control
				if (!empty($this->access_control))
				{

					// Catch missing Where
					if (empty($this->where))
						$sql .= " where 1 ";

					$sql .= "{$this->access_control} ";
				}
				else
					throw new DatabaseException(elgg_echo('DatabaseException:NoACL'));

				// Order by
				if (!empty($this->order))
					$sql .= "{$this->order} ";

				// Limits
				if (!empty($this->limit_and_offset))
					$sql .= "{$this->limit_and_offset} ";



			} catch (Exception $e) {
				trigger_error($e, E_USER_WARNING);
			}


			return $sql;
		}

	}

	/**
	 * @class SimpleQuery A wrapper for Query which provides simple interface for common functions.
	 *
	 * This class provides simple interface functions for constructing a (reasonably) standard database
	 * query.
	 *
	 * The constructor for this class sets a number of defaults, for example sets default access controls
	 * and a limit and offset - to change this then set it manually.
	 *
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
