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
		        	throw new DatabaseException(sprintf(elgg_echo('DatabaseException:WrongCredentials'), $CONFIG->dbuser, $CONFIG->dbhost, $CONFIG->debug ? $CONFIG->dbpass : "****"));
		        if (!mysql_select_db($CONFIG->dbname, $dblink[$dblinkname]))
		        	throw new DatabaseException(sprintf(elgg_echo('DatabaseException:NoConnect'), $CONFIG->dbname));
			
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
            
            //if (!callpath_gatekeeper($CONFIG->path . "engine/", true, true))
            //	throw new SecurityException(sprintf(elgg_echo('SecurityException:FunctionDenied'), 'get_data()'));
            
            $dblink = get_db_link('read');
            
            $resultarray = array();
            $dbcalls++;
            
        	if ((isset($CONFIG->debug)) && ($CONFIG->debug==true))
            {
            	error_log("--- DB QUERY --- $query");
            	error_log("--- EXPLANATION --- " . print_r(explain_query($query,$dblink), true));
            }
            
            if ($result = mysql_query("$query", $dblink)) {
                while ($row = mysql_fetch_object($result)) {
                	if (!empty($callback) && is_callable($callback)) {
                		$row = $callback($row);
                	}
                    if ($row) $resultarray[] = $row;
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
            
           // if (!callpath_gatekeeper($CONFIG->path . "engine/", true, true))
           // 	throw new SecurityException(sprintf(elgg_echo('SecurityException:FunctionDenied'), 'get_data_row()'));
            
            $dblink = get_db_link('read');
            
            $dbcalls++;
            
        	if ((isset($CONFIG->debug)) && ($CONFIG->debug==true))
            {
            	error_log("--- DB QUERY --- $query");
            	error_log("--- EXPLANATION --- " . print_r(explain_query($query,$dblink), true));
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
            
            //if (!callpath_gatekeeper($CONFIG->path . "engine/", true, true))
            //	throw new SecurityException(sprintf(elgg_echo('SecurityException:FunctionDenied'), 'insert_data()'));
            
            $dblink = get_db_link('write');
            
            $dbcalls++;
            
        	if ((isset($CONFIG->debug)) && ($CONFIG->debug==true))
            {
            	error_log("--- DB QUERY --- $query");
            }
            
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
            
            //if (!callpath_gatekeeper($CONFIG->path . "engine/", true, true))
            //	throw new SecurityException(sprintf(elgg_echo('SecurityException:FunctionDenied'), 'update_data()'));
            
            $dblink = get_db_link('write');
            
            $dbcalls++;
            
       		if ((isset($CONFIG->debug)) && ($CONFIG->debug==true))
            {
            	error_log("--- DB QUERY --- $query");
            }
            
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
            
            //if (!callpath_gatekeeper($CONFIG->path . "engine/", true, true))
            //	throw new SecurityException(sprintf(elgg_echo('SecurityException:FunctionDenied'), 'delete_data()'));
            
            $dblink = get_db_link('write');
            
            $dbcalls++;
            
        	if ((isset($CONFIG->debug)) && ($CONFIG->debug==true))
            {
            	error_log("--- DB QUERY --- $query");
            }
            
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
        			throw new DatabaseException(elgg_echo('DatabaseException:DBSetupIssues') . $errortxt);
        		}
        		
        	} else {
        		throw new DatabaseException(sprintf(elgg_echo('DatabaseException:ScriptNotFound'), $scriptlocation));
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

		register_elgg_event_handler('boot','system','init_db',0);

?>