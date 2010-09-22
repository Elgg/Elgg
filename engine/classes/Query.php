<?php
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
 * @author Curverider Ltd
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

