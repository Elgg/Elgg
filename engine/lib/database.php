<?php
/**
 * Elgg database procedural code.
 *
 * Includes functions for establishing and retrieving a database link,
 * reading data, writing data, upgrading DB schemas, and sanitizing input.
 *
 * @package Elgg.Core
 * @subpackage Database
 */

/**
 * Query cache for all queries.
 *
 * Each query and its results are stored in this array as:
 * <code>
 * $DB_QUERY_CACHE[$query] => array(result1, result2, ... resultN)
 * </code>
 *
 * @global array $DB_QUERY_CACHE
 */
$DB_QUERY_CACHE = array();

/**
 * Queries to be executed upon shutdown.
 *
 * These queries are saved to an array and executed using
 * a function registered by register_shutdown_function().
 *
 * Queries are saved as an array in the format:
 * <code>
 * $DB_DELAYED_QUERIES[] = array(
 * 	'q' => str $query,
 * 	'l' => resource $dblink,
 * 	'h' => str $handler // a callback function
 * );
 * </code>
 *
 * @see db_delayedexecution_shutdown_hook()
 * @see execute_delayed_query()
 * @see init_db()
 * @see register_shutdown_function()
 * @global array $DB_DELAYED_QUERIES
 */
$DB_DELAYED_QUERIES = array();

/**
 * Database connection resources.
 *
 * Each database link created with establish_db_link($name) is stored in
 * $dblink as $dblink[$name] => resource.  Use get_db_link($name) to retrieve it.
 *
 * @global array $dblink
 */
$dblink = array();

/**
 * Database call count
 *
 * Each call to the database increments this counter.
 *
 * @global integer $dbcalls
 */
$dbcalls = 0;

/**
 * Establish a connection to the database servser
 *
 * Connect to the database server and use the Elgg database for a particular database link
 *
 * @param string $dblinkname The type of database connection. Used to identify the resource. eg "read", "write", or "readwrite".
 * @uses $CONFIG
 * @uses $dblink
 * @uses $DB_QUERY_CACHE
 * @uses $dbcalls;
 */
function establish_db_link($dblinkname = "readwrite") {
	// Get configuration, and globalise database link
	global $CONFIG, $dblink, $DB_QUERY_CACHE, $dbcalls;

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
	if (!$dblink[$dblinkname] = mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, true)) {
		$msg = sprintf(elgg_echo('DatabaseException:WrongCredentials'),
				$CONFIG->dbuser, $CONFIG->dbhost, "****");
		throw new DatabaseException($msg);
	}

	if (!mysql_select_db($CONFIG->dbname, $dblink[$dblinkname])) {
		$msg = sprintf(elgg_echo('DatabaseException:NoConnect'), $CONFIG->dbname);
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
		$DB_QUERY_CACHE = new ElggStaticVariableCache('db_query_cache');
	}
}

/**
 * Establish database connections
 *
 * If the configuration has been set up for multiple read/write databases, set those
 * links up separately; otherwise just create the one database link.
 *
 * @uses $CONFIG
 * @uses $dblink
 * @uses establish_db_link
 */
function setup_db_connections() {
	global $CONFIG, $dblink;

	if (!empty($CONFIG->db->split)) {
		establish_db_link('read');
		establish_db_link('write');
	} else {
		establish_db_link('readwrite');
	}
}

/**
 * Display profiling information about db at NOTICE debug level upon shutdown.
 *
 * @uses $dbcalls
 * @uses init_db()
 * @uses register_shutdown_function()
 */
function db_profiling_shutdown_hook() {
	global $dbcalls;

	// demoted to NOTICE as it corrupts javasript at DEBUG
	elgg_log("DB Queries for this page: $dbcalls", 'NOTICE');
}

/**
 * Execute any delayed queries upon shutdown.
 *
 * @uses $DB_DELAYED_QUERIES
 * @uses $CONFIG
 * @uses init_db()
 * @uses register_shutdown_function()
 */
function db_delayedexecution_shutdown_hook() {
	global $DB_DELAYED_QUERIES, $CONFIG;

	foreach ($DB_DELAYED_QUERIES as $query_details) {
		// use one of our db functions so it is included in profiling.
		$result = execute_query($query_details['q'], $query_details['l']);

		try {
			if ((isset($query_details['h'])) && (is_callable($query_details['h']))) {
				$query_details['h']($result);
			}
		} catch (Exception $e) {
			// Suppress all errors since these can't be dealt with here
			elgg_log($e, 'WARNING');
		}
	}
}

/**
 * Registers shutdown functions for database profiling and delayed queries.
 *
 * NOTE: Database connections are established upon first call to database.
 *
 * @param string $event The event type
 * @param string $object_type The object type
 * @param mixed $object Used for nothing in this context
 * @elgg_event_handler boot system
 */
function init_db() {
	register_shutdown_function('db_delayedexecution_shutdown_hook');
	register_shutdown_function('db_profiling_shutdown_hook');

	return true;
}

/**
 * Returns (if required, also creates) a database link resource.
 *
 * Database link resources are stored in the {@link $dblink} global.  These
 * resources are created by {@link setup_db_connections()}, which is called if
 * no links exist.
 *
 * @param string $dblinktype The type of link we want: "read", "write" or "readwrite".
 * @return object Database link
 * @uses setup_db_connections()
 * @uses get_db_link()
 * @uses $dblink
 */
function get_db_link($dblinktype) {
	global $dblink;

	if (isset($dblink[$dblinktype])) {
		return $dblink[$dblinktype];
	} else if (isset($dblink['readwrite'])) {
		return $dblink['readwrite'];
	} else {
		setup_db_connections();
		return get_db_link($dblinktype);
	}
}

/**
 * Execute an EXPLAIN for $query.
 *
 * @param str $query The query to explain
 * @param mixed $link The database link resource to user.
 * @return mixed An object of the query's result, or FALSE
 * @uses execute_query()
 */
function explain_query($query, $link) {
	if ($result = execute_query("explain " . $query, $link)) {
		return mysql_fetch_object($result);
	}

	return FALSE;
}

/**
 * Execute a query.
 *
 * $query is executed via {@link mysql_query()}.  If there is an SQL error,
 * a {@link DatabaseException} is thrown.
 *
 * @internal
 * {@link $dbcalls} is incremented and the query is saved into the {@link $DB_QUERY_CACHE}.
 *
 * @param string $query The query
 * @param link $dblink the DB link
 * @return The result of mysql_query()
 * @uses $CONFIG
 * @uses $dbcalls
 * @uses $DB_QUERY_CACHE
 * @throws DatabaseException
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
 * Queue a query for execution upon shutdown.
 *
 * You can specify a handler function if you care about the result. This function will accept
 * the raw result from {@link mysql_query()}.
 *
 * @param string $query The query to execute
 * @param resource $dblink The database link to use
 * @param string $handler A callback function to pass the results array to
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

	return TRUE;
}

/**
 * Write wrapper for execute_delayed_query()
 *
 * @param string $query The query to execute
 * @param string $handler The handler if you care about the result.
 * @uses execute_delayed_query()
 * @uses get_db_link()
 */
function execute_delayed_write_query($query, $handler = "") {
	return execute_delayed_query($query, get_db_link('write'), $handler);
}

/**
 * Read wrapper for execute_delayed_query()
 *
 * @param string $query The query to execute
 * @param string $handler The handler if you care about the result.
 * @uses execute_delayed_query()
 * @uses get_db_link()
 */
function execute_delayed_read_query($query, $handler = "") {
	return execute_delayed_query($query, get_db_link('read'), $handler);
}

/**
 * Retrieve rows from the database.
 *
 * Queries are executed with {@link execute_query()} and results
 * are retrieved with {@link mysql_fetch_object()}.  If a callback
 * function $callback is defined, each row will be passed as the single
 * argument to $callback.  If no callback function is defined, the
 * entire result set is returned as an array.
 *
 * If no results are matched, FALSE is returned.
 *
 * @param mixed $query The query being passed.
 * @param string $call Optionally, the name of a function to call back to on each row
 * @return array|false An array of database result objects or callback function results or false
 * @uses $CONFIG
 * @uses $DB_QUERY_CACHE
 * @uses get_db_link()
 * @uses execute_query()
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
		return FALSE;
	}

	// Cache result
	if ($DB_QUERY_CACHE) {
		$DB_QUERY_CACHE[$query] = $resultarray;
		elgg_log("$query results cached");
	}

	return $resultarray;
}

/**
 * Retrieve a single row from the database.
 *
 * Similar to {@link get_data()} but returns only the first row
 * matched.  If a callback function $callback is specified, the row will be passed
 * as the only argument to $callback.
 *
 * @param mixed $query The query to execute.
 * @return mixed A single database result object or the result of the callback function.
 * @uses $CONFIG
 * @uses $DB_QUERY_CACHE
 * @uses execute_query()
 * @uses get_db_link()
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
			return FALSE;
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
 * Insert a row into the database.
 *
 * NOTE: Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
 *
 * @param mixed $query The query to execute.
 * @return int|false The database id of the inserted row if a AUTO_INCREMENT field is defined, 0 if not, and false on failure.
 * @uses $CONFIG
 * @uses $DB_QUERY_CACHE
 * @uses get_db_link()
 * @uses execute_query()
 * @uses mysql_insert_id()
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

	return FALSE;
}

/**
 * Update a row in the database.
 *
 * NOTE: Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
 *
 * @param string $query The query to run.
 * @return Bool
 * @uses $CONFIG
 * @uses $DB_QUERY_CACHE
 * @uses get_db_link()
 * @uses execute_db_query()
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
		return TRUE;
	}

	return FALSE;
}

/**
 * Remove a row from the database.
 *
 * NOTE: Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
 *
 * @param string $query The SQL query to run
 * @return int|false The number of affected rows or false on failure
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

	return FALSE;
}


/**
 * Return tables matching the database prefix {@link $CONFIG->dbprefix}% in the currently
 * selected database.
 *
 * @return array|false List of tables or false on failure
 * @uses $CONFIG
 * @static array $tables Tables found matching the database prefix
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
		return FALSE;
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
		return FALSE;
	}

	return $tables;
}

/**
 * Optimise a table.
 *
 * Executes an OPTIMIZE TABLE query on $table.  Useful after large DB changes.
 *
 * @param string $table The name of the table to optimise
 * @uses update_data()
 */
function optimize_table($table) {
	$table = sanitise_string($table);
	return update_data("optimize table $table");
}

/**
 * Get the last database error for a particular database link
 *
 * @param resource $dblink
 * @return string Database error message
 */
function get_db_error($dblink) {
	return mysql_error($dblink);
}

/**
 * Runs a full database script from disk.
 *
 * The file specified should be a standard SQL file as created by
 * mysqldump or similar.  Statements must be terminated with ;
 * and a newline character (\n or \r\n) with only one statement per line.
 *
 * The special string 'prefix_' is replaced with the database prefix
 * as defined in {@link $CONFIG->dbprefix}.
 *
 * WARNING: Errors do not halt execution of the script.  If a line
 * generates an error, the error message is saved and the
 * next line is executed.  After the file is run, any errors
 * are displayed as a {@link DatabaseException}
 *
 * @param string $scriptlocation The full path to the script
 * @throws DatabaseException
 * @uses $CONFIG
 * @uses update_data()
 */
function run_sql_script($scriptlocation) {
	if ($script = file_get_contents($scriptlocation)) {
		global $CONFIG;

		$errors = array();

		// Remove MySQL -- style comments
		$script = preg_replace('/\-\-.*\n/', '', $script);

		// Statements must end with ; and a newline
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
			foreach($errors as $error) {
				$errortxt .= " {$error};";
			}
			throw new DatabaseException(elgg_echo('DatabaseException:DBSetupIssues') . $errortxt);
		}
	} else {
		throw new DatabaseException(sprintf(elgg_echo('DatabaseException:ScriptNotFound'), $scriptlocation));
	}
}

/**
 * Upgrade the database schema in an ordered sequence.
 *
 * Executes all upgrade files in elgg/engine/schema/upgrades/ in sequential order.
 * Upgrade files must be in the standard Elgg release format of YYYYMMDDII.sql
 * where II is an incrementor starting from 01.
 *
 * Files that are < $version will be ignored.
 *
 * WARNING: Plugin authors should not call this function directly.
 *
 * @param int $version The version you are upgrading from in the format YYYYMMDDII.
 * @param string $fromdir Optional directory to load upgrades from (default: engine/schema/upgrades/)
 * @param bool $quiet If true, will suppress all error messages.  Should be used only for the upgrade from version <=1.6.
 * @return bool
 * @uses $CONFIG
 * @uses run_sql_script()
 * @see upgrade.php
 * @see version.php
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
 * Check if MySQL is at least version 5.
 *
 * @todo If multiple dbs are supported check which db is supported and use the appropriate code to validate
 * the appropriate version.
 * @return bool
 * @see mysql_get_server_info()
 */
function db_check_version() {
	$version = mysql_get_server_info();
	$points = explode('.', $version);

	if ($points[0] < 5) {
		return FALSE;
	}

	return TRUE;
}

/**
 * Sanitise a string for database use, but with the option of escaping extra characters.
 *
 * @param string $string The string to sanitise
 * @param string $extra_escapeable Extra characters to escape with '\\'
 * @return string The escaped string
 * @uses sanitise_string()
 */
function sanitise_string_special($string, $extra_escapeable = '') {
	$string = sanitise_string($string);

	for ($n = 0; $n < strlen($extra_escapeable); $n++) {
		$string = str_replace($extra_escapeable[$n], "\\" . $extra_escapeable[$n], $string);
	}

	return $string;
}

/**
 * Sanitise a string for database use.
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
 * @uses sanitise_string()
 */
function sanitize_string($string) {
	return sanitise_string($string);
}

/**
 * Sanitises an integer for database use.
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
 * @uses sanitise_int()
 */
function sanitize_int($int) {
	return (int) $int;
}

/**
 * @elgg_register_event boot system init_db
 */
register_elgg_event_handler('boot', 'system', 'init_db', 0);