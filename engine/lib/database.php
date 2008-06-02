<?php

	/**
	 * Elgg database
	 * Contains database connection and transfer functionality
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * @class QueryComponent Query component superclass.
	 * Component of a query.
	 * @author Marcus Povey
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
	 * @author Marcus Povey
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
	 * @author Marcus Povey
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
	 * @author Marcus Povey
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
	 * @author Marcus Povey
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
	 * @author Marcus Povey
	 */
	class AccessControlQueryComponent extends QueryComponent
	{
		/**
		 * Construct the ACL.
		 * 
		 * @param string $acl_table The table where the access control field is.
		 * @param string $acl_field The field containing the access control.
		 * @param string $object_owner_table The table containing the owner information for the stuff you're retrieving.
		 * @param string $object_owner_id_field The field in $object_owner_table containing 
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
			$access = get_access_list();
			
			return "and ({$this->acl_table}.{$this->acl_field} in {$access} or ({$this->acl_table}.{$this->acl_field} = 0 and {$this->object_owner_table}.{$this->object_owner_id_field} = {$_SESSION['id']}))";
		}
	}
	
	/**
	 * @class JoinQueryComponent Join query.
	 * Represents a join query.
	 * @author Marcus Povey
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
	 * @author Marcus Povey
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
	 * @author Marcus Povey
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
	 * @author Marcus Povey
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
		
		public function toStringNoLink()
		{
			return "{$this->left_table }.{$this->left_field} {$this->operator} {$this->value}";
		}
	}
	
	/**
	 * @class WhereSetQueryComponent
	 * A where query that may contain other where queries (in brackets).
	 * @author Marcus Povey
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
					throw new DatabaseException("Where set contains non WhereQueryComponent");
				
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
	 * @author Marcus Povey
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
	 * @author Marcus Povey
	 */
	class SelectQueryTypeQueryComponent extends QueryTypeQueryComponent 
	{
		function __construct() 
		{
			$this->query_type = "select";	
		}
	}
	
	/**
	 * @class InsertQueryTypeQueryComponent
	 * An insert query.
	 * @author Marcus Povey
	 */
	class InsertQueryTypeQueryComponent extends QueryTypeQueryComponent 
	{
		function __construct() 
		{
			$this->query_type = "insert into";	
		}
	}
	
	/**
	 * @class DeleteQueryTypeQueryComponent
	 * A delete query.
	 * @author Marcus Povey
	 */
	class DeleteQueryTypeQueryComponent extends QueryTypeQueryComponent 
	{
		function __construct() 
		{
			$this->query_type = "delete from";	
		}
	}
	
	/**
	 * @class UpdateQueryTypeQueryComponent
	 * An update query.
	 * @author Marcus Povey
	 */
	class UpdateQueryTypeQueryComponent extends QueryTypeQueryComponent 
	{
		function __construct() 
		{
			$this->query_type = "update";	
		}
	}
	
	/**
	 * @class Query
	 * This class provides a framework to construct complex queries in a safe environment.
	 * 
	 * @author Marcus Povey
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
		
		public function setLimitAndOffset(LimitOffsetQueryComponent $component) { $this->limit_and_offset = $component; }
		
		public function setSelectField(SelectFieldQueryComponent $component) 
		{
			$this->fields = array();
			return $this->addSelectField($component);
		}
		
		public function addSelectField(SelectFieldQueryComponent $component) { $this->fields[] = $component; }
		
		public function addJoin(JoinQueryComponent $component) { $this->joins[] = $component; }
		
		public function addSet(SetQueryComponent $component) { $this->sets[] = $component; }
		
		public function setQueryType(QueryTypeQueryComponent $component) { $this->query_type = $component; }
		
		public function setOrder(OrderQueryComponent $component) { $this->order = $component; }
		
		public function addTable(TableQueryComponent $component) { $this->tables[] = $component; }
		
		public function addWhere(WhereQueryComponent $component) { $this->where[] = $component; }
		
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
						throw new DatabaseException("Fields missing on a select style query");
				}
				else
					throw new DatabaseException("Unrecognised or unspecified query type.");
						
				// Tables
				if (!empty($this->tables)) 
				{
					foreach($this->tables as $table) 
						$sql .= "$table, ";
						
					$sql = trim($sql, ", ");
				}
				else
					throw new DatabaseException("No tables specified for query.");
				
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
					throw new DatabaseException("No access control was provided on query");
					
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
	 * Connect to the database server and use the Elgg database for a particular database link
	 *
	 * @param string $dblinkname Default "readwrite"; you can change this to set up additional global database links, eg "read" and "write" 
	 */
		function establish_db_link($dblinkname = "readwrite") {
			
			// Get configuration, and globalise database link
		        global $CONFIG, $dblink;
		        
		        if (!isset($dblink)) {
		        	$dblink = array();
		        }
		        
		        if ($dblinkname != "readwrite" && isset($CONFIG->db[$dblinkname])) {
		        	if (is_array($CONFIG->db[$dblinkname])) {
		        		$index = rand(0,sizeof($CONFIG->db[$dblinkname]));
		        		$dbhost = $CONFIG->db[$dblinkname][$index]->dbhost;
						$dbuser = $CONFIG->db[$dblinkname][$index]->dbuser;
						$dbpass = $CONFIG->db[$dblinkname][$index]->dbpass;
						$dbname = $CONFIG->db[$dblinkname][$index]->dbname;
		        	} else {
						$dbhost = $CONFIG->db[$dblinkname]->dbhost;
						$dbuser = $CONFIG->db[$dblinkname]->dbuser;
						$dbpass = $CONFIG->db[$dblinkname]->dbpass;
						$dbname = $CONFIG->db[$dblinkname]->dbname;
		        	}
		        } else {
		        	$dbhost = $CONFIG->dbhost;
					$dbuser = $CONFIG->dbuser;
					$dbpass = $CONFIG->dbpass;
					$dbname = $CONFIG->dbname;
		        }
		        
		    // Connect to database
		        if (!$dblink[$dblinkname] = mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, true))
		        	throw new DatabaseException("Elgg couldn't connect to the database using the given credentials.");
		        if (!mysql_select_db($CONFIG->dbname, $dblink[$dblinkname]))
		        	throw new DatabaseException("Elgg couldn't select the database {$CONFIG->dbname}.");
			
		}
		
	/**
	 * Establish all database connections
	 * 
	 * If the configuration has been set up for multiple read/write databases, set those
	 * links up separately; otherwise just create the one database link
	 *
	 */
		
		function setup_db_connections() {
			
			// Get configuration and globalise database link
				global $CONFIG, $dblink;
				
				if (!empty($CONFIG->db->split)) {
					establish_db_link('read');
					establish_db_link('write');
				} else {
					establish_db_link('readwrite');
				}
			
		}
		
	/**
	 * Alias to setup_db_connections, for use in the event handler
	 *
	 * @param string $event The event type
	 * @param string $object_type The object type
	 * @param mixed $object Used for nothing in this context
	 */
		function init_db($event, $object_type, $object = null) {
			setup_db_connections();
			return true;
		}
		
	/**
	 * Gets the appropriate db link for the operation mode requested
	 *
	 * @param string $dblinktype The type of link we want - "read", "write" or "readwrite" (the default)
	 * @return object Database link
	 */
		function get_db_link($dblinktype) {
			
			global $dblink;
			
			if (isset($dblink[$dblinktype])) {
				return $dblink[$dblinktype];
			} else {
				return $dblink['readwrite'];
			}
			
		}
		
		/**
		 * Explain a given query, useful for debug.
		 */
		function explain_query($query, $link)
		{
			if ($result = mysql_query("explain " . $query, $link)) {
                return mysql_fetch_object($result);
            }
            
            return false;
		}
		
	/**
     * Use this function to get data from the database
     * @param mixed $query The query being passed.
     * @param string $call Optionally, the name of a function to call back to on each row (which takes $row as a single parameter)
     * @return array An array of database result objects
     */
    
        function get_data($query, $callback = "") {
            
            global $CONFIG, $dbcalls;
            
            if (!callpath_gatekeeper($CONFIG->path . "engine/", true, true))
            	throw new DatabaseException("Access to privileged function 'get_data()' is denied.");
            
            $dblink = get_db_link('read');
            
            $resultarray = array();
            $dbcalls++;
            
        	if ((isset($CONFIG->debug)) && ($CONFIG->debug==true))
            {
            	error_log("--- DB QUERY --- $query");
            	error_log("--- EXPLAINATION --- " . print_r(explain_query($query,$dblink), true));
            }
            
            if ($result = mysql_query("$query", $dblink)) {
                while ($row = mysql_fetch_object($result)) {
                	if (!empty($callback) && is_callable($callback)) {
                		$row = $callback($row);
                	}
                    $resultarray[] = $row;
                }
            }
            
            if (mysql_errno($dblink))
				throw new DatabaseException(mysql_error($dblink) . " QUERY: " . $query);
				
       		if (empty($resultarray)) {
       			if ((isset($CONFIG->debug)) && ($CONFIG->debug==true))
       				error_log("WARNING: DB query \"$query\" returned no results.");
       				
                return false;
            }
            return $resultarray;
        }
        
    /**
     * Use this function to get a single data row from the database
     * @param mixed $query The query to run.
     * @return object A single database result object
     */ 
    
        function get_data_row($query) {
            
            global $CONFIG, $dbcalls;
            
            if (!callpath_gatekeeper($CONFIG->path . "engine/", true, true))
            	throw new DatabaseException("Access to privileged function 'get_data_row()' is denied.");
            
            $dblink = get_db_link('read');
            
            $dbcalls++;
            
        	if ((isset($CONFIG->debug)) && ($CONFIG->debug==true))
            {
            	error_log("--- DB QUERY --- $query");
            	error_log("--- EXPLAINATION --- " . print_r(explain_query($query,$dblink), true));
            }
            
            if ($result = mysql_query("$query", $dblink)) {
                while ($row = mysql_fetch_object($result)) {
                    return $row;
                }
            }
            
            if (mysql_errno($dblink))
				throw new DatabaseException(mysql_error($dblink) . " QUERY: " . $query);
            
			if ((isset($CONFIG->debug)) && ($CONFIG->debug==true))
       				error_log("WARNING: DB query \"$query\" returned no results.");
       				
            return false;
        }
        
    /**
     * Use this function to insert database data; returns id or false
     * 
     * @param mixed $query The query to run.
     * @return int $id the database id of the inserted row.
     */ 
    
        function insert_data($query) {
            
            global $CONFIG, $dbcalls;
            
            if (!callpath_gatekeeper($CONFIG->path . "engine/", true, true))
            	throw new DatabaseException("Access to privileged function 'insert_data()' is denied.");
            
            $dblink = get_db_link('write');
            
            $dbcalls++;
            
            if (mysql_query("$query", $dblink)) 
                return mysql_insert_id($dblink);
                
			if (mysql_errno($dblink))
				throw new DatabaseException(mysql_error($dblink) . " QUERY: " . $query);
                
			return false;
        }
        
    /**
     * Update database data
     * 
     * @param mixed $query The query to run.
     * @return int|false Either the number of affected rows, or false on failure
     */ 
    
        function update_data($query) {
            
            global $dbcalls, $CONFIG;
            
            if (!callpath_gatekeeper($CONFIG->path . "engine/", true, true))
            	throw new DatabaseException("Access to privileged function 'update_data()' is denied.");
            
            $dblink = get_db_link('write');
            
            $dbcalls++;
            
            if (mysql_query("$query", $dblink))
            	return mysql_affected_rows();
            	
           	if (mysql_errno($dblink))
				throw new DatabaseException(mysql_error($dblink) . " QUERY: " . $query);
         
         	return false;   
            
        }

	/**
	 * Use this function to delete data
	 *
	 * @param mixed $query The SQL query to run
	 * @return int|false Either the number of affected rows, or false on failure
	 */
    
        function delete_data($query) {
            
            global $dbcalls, $CONFIG;
            
            if (!callpath_gatekeeper($CONFIG->path . "engine/", true, true))
            	throw new DatabaseException("Access to privileged function 'delete_data()' is denied.");
            
            $dblink = get_db_link('write');
            
            $dbcalls++;
            
            if (mysql_query("$query", $dblink)) 
                return mysql_affected_rows();
            
            if (mysql_errno($dblink))
				throw new DatabaseException(mysql_error($dblink) . " QUERY: " . $query);
                
			return false;      
        }
    
   /**
    * Returns the number of rows returned by the last select statement, without the need to re-execute the query.
    *
    * CANDIDATE FOR DELETION?
    * 
    * @return int The number of rows returned by the last statement
    */
		function count_last_select() {
        	$row = get_data_row("SELECT found_rows() as count");
        	if ($row)
        		return $row->count;
        	return 0;
        }
 
	/**
	 * Get the tables currently installed in the Elgg database
	 *
	 * @return array List of tables
	 */
        function get_db_tables() {
        	global $CONFIG;
        	$result = get_data("show tables");
        	        	 	
        	$tables = array();
        	
        	if (is_array($result) && !empty($result)) {
        		foreach($result as $row) {
        			$row = (array) $row;
        			if (is_array($row) && !empty($row))
	        			foreach($row as $element) {
	        				$tables[] = $element;
	        			}
        		}
        	}
        	else
        		return false;
        	
        	return $tables;
        }
        
	/**
	 * Get the last database error for a particular database link
	 *
	 * @param database link $dblink
	 * @return string Database error message
	 */
        function get_db_error($dblink) {
        	return mysql_error($dblink);
        }
        
	/**
	 * Runs a full database script from disk
	 *
	 * @uses $CONFIG
	 * @param string $scriptlocation The full path to the script
	 */
        function run_sql_script($scriptlocation) {
        	
        	if ($script = file_get_contents($scriptlocation)) {

        		global $CONFIG;
        		
        		$errors = array();
        		
        		$script = preg_replace('/\-\-.*\n/', '', $script);
        		$sql_statements =  preg_split('/;[\n\r]+/', $script);
        		foreach($sql_statements as $statement) {
        			$statement = trim($statement);
        			$statement = str_replace("prefix_",$CONFIG->dbprefix,$statement);
        			if (!empty($statement)) {
        				$result = update_data($statement);
        			}
        		}
        		if (!empty($errors)) {
        			$errortxt = "";
        			foreach($errors as $error)
        				$errortxt .= " {$error};";
        			throw new DatabaseException("There were a number of issues: " . $errortxt);
        		}
        		
        	} else {
        		throw new DatabaseException("Elgg couldn't find the requested database script at {$scriptlocation}.");
        	}
        	
        }
        
	/**
	 * Sanitise a string for database use
	 *
	 * @param string $string The string to sanitise
	 * @return string Sanitised string
	 */
        function sanitise_string($string) {
        	return mysql_real_escape_string(trim($string));
        }
        
	/**
	 * Wrapper function for Americans
	 *
	 * @param string $string The string to sanitise
	 * @return string Sanitised string
	 * @uses sanitise_string
	 */
        function sanitize_string($string) {
        	return sanitise_string($string);
        }
        
	/**
	 * Sanitises an integer for database use
	 *
	 * @param int $int
	 * @return int Sanitised integer
	 */
        function sanitise_int($int) {
        	return (int) $int;
        }
        
	/**
	 * Wrapper function for Americans
	 *
	 * @param int $int
	 * @return int Sanitised integer
	 * @uses sanitise_string
	 */
        function sanitize_int($int) {
        	return (int) $int;
        }
        
	// Stuff for initialisation

		register_event_handler('boot','system','init_db',0);

?>