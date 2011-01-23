<?php
/**
 * Elgg database
 * Contains database connection and transfer functionality
 *
 * @package Elgg
 * @subpackage Core
 */

global $DB_QUERY_CACHE;
$DB_QUERY_CACHE = array();

global $DB_DELAYED_QUERIES;
$DB_DELAYED_QUERIES = array();

/**
 * Connect to the database server and use the Elgg database for a particular database link
 *
 * @param string $dblinkname Default "readwrite"; you can change this to set up additional global database links, eg "read" and "write"
 */
function establish_db_link($dblinkname = "readwrite") {
	// Get configuration, and globalise database link
	global $CONFIG, $dblink, $DB_QUERY_CACHE, $dbcalls;

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
	if (!$dblink[$dblinkname] = mysql_connect($dbhost, $dbuser, $dbpass, true)) {
		$msg = sprintf(elgg_echo('DatabaseException:WrongCredentials'),
				$dbuser, $dbhost, "****");
		throw new DatabaseException($msg);
	}
	if (!mysql_select_db($dbname, $dblink[$dblinkname])) {
		$msg = sprintf(elgg_echo('DatabaseException:NoConnect'), $dbname);
		throw new DatabaseException($msg);
	}

	// Set DB for UTF8
	mysql_query("SET NAMES utf8");

	$db_cache_off = FALSE;
	if (isset($CONFIG->db_disable_query_cache)) {
		$db_cache_off = $CONFIG->db_disable_query_cache;
	}

	// Set up cache if global not initialized and query cache not turned off
	if ((!$DB_QUERY_CACHE) && (!$db_cache_off)) {
		$DB_QUERY_CACHE = new ElggStaticVariableCache('db_query_cache'); //array();
		//$DB_QUERY_CACHE = select_default_memcache('db_query_cache'); //array();
	}
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
 * Shutdown hook to display profiling information about db (debug mode)
 */
function db_profiling_shutdown_hook() {
	global $dbcalls;

	// demoted to NOTICE as it corrupts javasript at DEBUG
	elgg_log("DB Queries for this page: $dbcalls", 'NOTICE');
}

/**
 * Execute any delayed queries.
 */
function db_delayedexecution_shutdown_hook() {
	global $DB_DELAYED_QUERIES;
	
	foreach ($DB_DELAYED_QUERIES as $query_details) {
		// use one of our db functions so it is included in profiling.
		$result = execute_query($query_details['q'], $query_details['l']);

		try {
			if ((isset($query_details['h'])) && (is_callable($query_details['h']))) {
				$query_details['h']($result);
			}
		} catch (Exception $e) { // Suppress all errors since these can't be dealt with here
			elgg_log($e, 'WARNING');
		}
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
	register_shutdown_function('db_delayedexecution_shutdown_hook');
	register_shutdown_function('db_profiling_shutdown_hook');
	// [Marcus Povey 20090213: Db connection moved to first db connection attempt]
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
	} else if (isset($dblink['readwrite'])) {
		return $dblink['readwrite'];
	}
	else {
		setup_db_connections();
		return get_db_link($dblinktype);
	}
}

/**
 * Explain a given query, useful for debug.
 */
function explain_query($query, $link) {
	if ($result = execute_query("explain " . $query, $link)) {
		return mysql_fetch_object($result);
	}

	return false;
}

/**
 * Execute a query.
 *
 * @param string $query The query
 * @param link $dblink the DB link
 * @return Returns a the result of mysql_query
 */
function execute_query($query, $dblink) {
	global $CONFIG, $dbcalls, $DB_QUERY_CACHE;

	$dbcalls++;

	$result = mysql_query($query, $dblink);
	if ($DB_QUERY_CACHE) {
		$DB_QUERY_CACHE[$query] = -1; // Set initial cache to -1
	}

	if (mysql_errno($dblink)) {
		throw new DatabaseException(mysql_error($dblink) . "\n\n QUERY: " . $query);
	}

	return $result;
}

/**
 * Queue a query for execution after all output has been sent to the user.
 *
 * You can specify a handler function if you care about the result. This function will accept
 * the raw result from mysql_query();
 *
 * @param string $query The query to execute
 * @param resource $dblink The database link to use
 * @param string $handler The handler
 */
function execute_delayed_query($query, $dblink, $handler = "") {
	global $DB_DELAYED_QUERIES;

	if (!isset($DB_DELAYED_QUERIES)) {
		$DB_DELAYED_QUERIES = array();
	}

	// Construct delayed query
	$delayed_query = array();
	$delayed_query['q'] = $query;
	$delayed_query['l'] = $dblink;
	$delayed_query['h'] = $handler;

	$DB_DELAYED_QUERIES[] = $delayed_query;

	return true;
}

/**
 * Write wrapper for execute_delayed_query()
 *
 * @param string $query The query to execute
 * @param string $handler The handler if you care about the result.
 */
function execute_delayed_write_query($query, $handler = "") {
	return execute_delayed_query($query, get_db_link('write'), $handler);
}

/**
 * Read wrapper for execute_delayed_query()
 *
 * @param string $query The query to execute
 * @param string $handler The handler if you care about the result.
 */
function execute_delayed_read_query($query, $handler = "") {
	return execute_delayed_query($query, get_db_link('read'), $handler);
}

/**
 * Use this function to get data from the database
 * @param mixed $query The query being passed.
 * @param string $call Optionally, the name of a function to call back to on each row (which takes $row as a single parameter)
 * @return array An array of database result objects
 */
function get_data($query, $callback = "") {
	global $CONFIG, $DB_QUERY_CACHE;

	// Is cached?
	if ($DB_QUERY_CACHE) {
		$cached_query = $DB_QUERY_CACHE[$query];
	}

	if ((isset($cached_query)) && ($cached_query)) {
		elgg_log("$query results returned from cache");

		if ($cached_query === -1) {
			// Last time this query returned nothing, so return an empty array
			return array();
		}

		return $cached_query;
	}

	$dblink = get_db_link('read');
	$resultarray = array();

	if ($result = execute_query("$query", $dblink)) {
		while ($row = mysql_fetch_object($result)) {
			if (!empty($callback) && is_callable($callback)) {
				$row = $callback($row);
			}
			if ($row) {
				$resultarray[] = $row;
			}
		}
	}

	if (empty($resultarray)) {
		elgg_log("DB query \"$query\" returned no results.");
		// @todo consider changing this to return empty array #1242
		return false;
	}

	// Cache result
	if ($DB_QUERY_CACHE) {
		$DB_QUERY_CACHE[$query] = $resultarray;
		elgg_log("$query results cached");
	}

	return $resultarray;
}

/**
 * Use this function to get a single data row from the database
 * @param mixed $query The query to run.
 * @return object A single database result object
 */

function get_data_row($query, $callback = "") {
	global $CONFIG, $DB_QUERY_CACHE;

	// Is cached
	if ($DB_QUERY_CACHE) {
		$cached_query = $DB_QUERY_CACHE[$query];
	}

	if ((isset($cached_query)) && ($cached_query)) {
		elgg_log("$query results returned from cache");

		if ($cached_query === -1) {
			// Last time this query returned nothing, so return false
			//@todo fix me this should return array().
			return false;
		}

		return $cached_query;
	}

	$dblink = get_db_link('read');

	if ($result = execute_query("$query", $dblink)) {
		$row = mysql_fetch_object($result);

		// Cache result (even if query returned no data)
		if ($DB_QUERY_CACHE) {
			$DB_QUERY_CACHE[$query] = $row;
			elgg_log("$query results cached");
		}

		if (!empty($callback) && is_callable($callback)) {
				$row = $callback($row);
		}

		if ($row) {
			return $row;
		}
	}

	elgg_log("$query returned no results.");
	return FALSE;
}

/**
 * Use this function to insert database data; returns id or false
 *
 * @param mixed $query The query to run.
 * @return int $id the database id of the inserted row.
 */
function insert_data($query) {
	global $CONFIG, $DB_QUERY_CACHE;

	$dblink = get_db_link('write');

	// Invalidate query cache
	if ($DB_QUERY_CACHE) {
		$DB_QUERY_CACHE->clear();
	}

	elgg_log("Query cache invalidated");

	if (execute_query("$query", $dblink)) {
		return mysql_insert_id($dblink);
	}

	return false;
}

/**
 * Update database data
 *
 * @param mixed $query The query to run.
 * @return Bool on success
 */
function update_data($query) {
	global $CONFIG, $DB_QUERY_CACHE;

	$dblink = get_db_link('write');

	// Invalidate query cache
	if ($DB_QUERY_CACHE) {
		$DB_QUERY_CACHE->clear();
		elgg_log("Query cache invalidated");
	}

	if (execute_query("$query", $dblink)) {
		// @todo why is this comment out?
		//return mysql_affected_rows();
		return true;
	}

	return false;
}

/**
 * Use this function to delete data
 *
 * @param mixed $query The SQL query to run
 * @return int|false Either the number of affected rows, or false on failure
 */
function delete_data($query) {
	global $CONFIG, $DB_QUERY_CACHE;

	$dblink = get_db_link('write');

	// Invalidate query cache
	if ($DB_QUERY_CACHE) {
		$DB_QUERY_CACHE->clear();
		elgg_log("Query cache invalidated");
	}

	if (execute_query("$query", $dblink)) {
		return mysql_affected_rows($dblink);
	}

	return false;
}


/**
 * Get the tables currently installed in the Elgg database
 *
 * @return array List of tables
 */
function get_db_tables() {
	global $CONFIG;
	static $tables;

	if (isset($tables)) {
		return $tables;
	}

	try{
		$result = get_data("show tables like '" . $CONFIG->dbprefix . "%'");
	} catch (DatabaseException $d) {
		// Likely we can't handle an exception here, so just return false.
		return false;
	}

	$tables = array();

	if (is_array($result) && !empty($result)) {
		foreach($result as $row) {
			$row = (array) $row;
			if (is_array($row) && !empty($row))
				foreach($row as $element) {
					$tables[] = $element;
				}
		}
	} else {
		return false;
	}

	return $tables;
}

/**
 * Run an optimize query on a mysql tables. Useful for executing after major data changes.
 *
 */
function optimize_table($table) {
	$table = sanitise_string($table);
	return update_data("optimize table $table");
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
				try {
					$result = update_data($statement);
				} catch (DatabaseException $e) {
					$errors[] = $e->getMessage();
				}
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
 * Upgrade the database schema in an ordered sequence.
 *
 * Makes use of schema upgrade files
 *
 * This is a about as core as it comes, so don't start running this from your plugins!
 *
 * @param int $version The version you are upgrading from (usually given in the Elgg version format of YYYYMMDDXX - see version.php for example)
 * @param string $fromdir Optional directory to load upgrades from (default: engine/schema/upgrades/)
 * @param bool $quiet If true, will suppress all error messages.  Don't use this.
 * @return bool
 */
function db_upgrade($version, $fromdir = "", $quiet = FALSE) {
	global $CONFIG;

	// Elgg and its database must be installed to upgrade it!
	if (!is_db_installed() || !is_installed()) {
		return false;
	}

	$version = (int) $version;

	if (!$fromdir) {
		$fromdir = $CONFIG->path . 'engine/schema/upgrades/';
	}

	if ($handle = opendir($fromdir)) {
		$sqlupgrades = array();

		while ($sqlfile = readdir($handle)) {
			if (!is_dir($fromdir . $sqlfile)) {
				if (preg_match('/^([0-9]{10})\.(sql)$/', $sqlfile, $matches)) {
					$sql_version = (int) $matches[1];
					if ($sql_version > $version) {
						$sqlupgrades[] = $sqlfile;
					}
				}
			}
		}

		asort($sqlupgrades);

		if (sizeof($sqlupgrades) > 0) {
			foreach($sqlupgrades as $sqlfile) {

				// hide all errors.
				if ($quiet) {
					try {
						run_sql_script($fromdir . $sqlfile);
					} catch (DatabaseException $e) {
						error_log($e->getmessage());
					}
				} else {
					run_sql_script($fromdir . $sqlfile);
				}
			}
		}
	}

	return TRUE;
}

/**
 * This function, called by validate_platform(), will check whether the installed version of
 * MySQL meets the minimum required.
 *
 * @todo If multiple dbs are supported check which db is supported and use the appropriate code to validate
 * the appropriate version.
 *
 * @return bool
 */
function db_check_version() {
	$version = mysql_get_server_info();
	$points = explode('.', $version);

	if ($points[0] < 5) {
		return false;
	}

	return true;
}

/**
 * Sanitise a string for database use, but with the option of escaping extra characters.
 */
function sanitise_string_special($string, $extra_escapeable = '') {
	$string = sanitise_string($string);

	for ($n = 0; $n < strlen($extra_escapeable); $n++) {
		$string = str_replace($extra_escapeable[$n], "\\" . $extra_escapeable[$n], $string);
	}

	return $string;
}

/**
 * Sanitise a string for database use
 *
 * @param string $string The string to sanitise
 * @return string Sanitised string
 */
function sanitise_string($string) {
	// @todo does this really need the trim?
	// there are times when you might want trailing / preceeding white space.
	return mysql_real_escape_string(trim($string));
}

/**
 * Wrapper function for alternate English spelling
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
 * Wrapper function for alternate English spelling
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