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
global $DB_QUERY_CACHE;
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
 * @global array $DB_DELAYED_QUERIES
 */
global $DB_DELAYED_QUERIES;
$DB_DELAYED_QUERIES = array();

/**
 * Database connection resources.
 *
 * Each database link created with establish_db_link($name) is stored in
 * $dblink as $dblink[$name] => resource.  Use get_db_link($name) to retrieve it.
 *
 * @global array $dblink
 */
global $dblink;
$dblink = array();

/**
 * Database call count
 *
 * Each call to the database increments this counter.
 *
 * @global integer $dbcalls
 */
global $dbcalls;
$dbcalls = 0;


/**
 * @return ElggDatabase The singleton DB object.
 */
function elgg_get_database() {
	global $CONFIG;
	if (!isset($CONFIG->databaseObj)) {
		$CONFIG->databaseObj = new ElggDatabase();	
	}
	
	return $CONFIG->databaseObj;
}

/**
 * Establish a connection to the database servser
 *
 * Connect to the database server and use the Elgg database for a particular database link
 *
 * @param string $dblinkname The type of database connection. Used to identify the
 * resource. eg "read", "write", or "readwrite".
 *
 * @return void
 * @access private
 */
function establish_db_link($dblinkname = "readwrite") {
	elgg_get_database()->establishLink($dblinkname);
}

/**
 * Establish database connections
 *
 * If the configuration has been set up for multiple read/write databases, set those
 * links up separately; otherwise just create the one database link.
 *
 * @return void
 * @access private
 */
function setup_db_connections() {
	elgg_get_database()->setupConnections();
}

/**
 * Returns (if required, also creates) a database link resource.
 *
 * Database link resources are stored in the {@link $dblink} global.  These
 * resources are created by {@link setup_db_connections()}, which is called if
 * no links exist.
 *
 * @param string $dblinktype The type of link we want: "read", "write" or "readwrite".
 *
 * @return object Database link
 * @access private
 */
function get_db_link($dblinktype) {
	return elgg_get_database()->getLink($dblinktype);
}

/**
 * Execute an EXPLAIN for $query.
 *
 * @param str   $query The query to explain
 * @param mixed $link  The database link resource to user.
 *
 * @return mixed An object of the query's result, or FALSE
 * @access private
 */
function explain_query($query, $link) {
	return elgg_get_database()->explainQuery($query, $link);
}

/**
 * Queue a query for execution upon shutdown.
 *
 * You can specify a handler function if you care about the result. This function will accept
 * the raw result from {@link mysql_query()}.
 *
 * @param string   $query   The query to execute
 * @param resource $dblink  The database link to use or the link type (read | write)
 * @param string   $handler A callback function to pass the results array to
 *
 * @return boolean Whether successful.
 * @access private
 */
function execute_delayed_query($query, $dblink, $handler = "") {
	return elgg_get_database()->registerDelayedQuery($query, $dblink, $handler);
}

/**
 * Write wrapper for execute_delayed_query()
 *
 * @param string $query   The query to execute
 * @param string $handler The handler if you care about the result.
 *
 * @return true
 * @uses execute_delayed_query()
 * @uses get_db_link()
 * @access private
 */
function execute_delayed_write_query($query, $handler = "") {
	return execute_delayed_query($query, 'write', $handler);
}

/**
 * Read wrapper for execute_delayed_query()
 *
 * @param string $query   The query to execute
 * @param string $handler The handler if you care about the result.
 *
 * @return true
 * @uses execute_delayed_query()
 * @uses get_db_link()
 * @access private
 */
function execute_delayed_read_query($query, $handler = "") {
	return execute_delayed_query($query, 'read', $handler);
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
 * @param mixed  $query    The query being passed.
 * @param string $callback Optionally, the name of a function to call back to on each row
 *
 * @return array An array of database result objects or callback function results. If the query
 *               returned nothing, an empty array.
 * @access private
 * @deprecated 1.9 Use ElggDatabase::getData() instead!
 */
function get_data($query, $callback = "") {
	return elgg_get_database()->getData($query, $callback);
}

/**
 * Retrieve a single row from the database.
 *
 * Similar to {@link get_data()} but returns only the first row
 * matched.  If a callback function $callback is specified, the row will be passed
 * as the only argument to $callback.
 *
 * @param mixed  $query    The query to execute.
 * @param string $callback A callback function
 *
 * @return mixed A single database result object or the result of the callback function.
 * @access private
 * @deprecated 1.9 Use ElggDatabase::getDataRow() instead!
 */
function get_data_row($query, $callback = "") {
	return elgg_get_database()->getDataRow($query, $callback);
}

/**
 * Insert a row into the database.
 *
 * @note Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
 *
 * @param mixed $query The query to execute.
 *
 * @return int|false The database id of the inserted row if a AUTO_INCREMENT field is
 *                   defined, 0 if not, and false on failure.
 * @access private
 */
function insert_data($query) {
	return elgg_get_database()->insertData($query);
}

/**
 * Update a row in the database.
 *
 * @note Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
 *
 * @param string $query The query to run.
 *
 * @return bool
 * @access private
 */
function update_data($query) {
	return elgg_get_database()->updateData($query);
}

/**
 * Remove a row from the database.
 *
 * @note Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
 *
 * @param string $query The SQL query to run
 *
 * @return int|false The number of affected rows or false on failure
 * @access private
 */
function delete_data($query) {
	return elgg_get_database()->deleteData($query);
}


/**
 * Return tables matching the database prefix {@link $CONFIG->dbprefix}% in the currently
 * selected database.
 *
 * @return array|false List of tables or false on failure
 * @static array $tables Tables found matching the database prefix
 * @access private
 */
function get_db_tables() {
	return elgg_get_database()->getTables();
}

/**
 * Optimise a table.
 *
 * Executes an OPTIMIZE TABLE query on $table.  Useful after large DB changes.
 *
 * @param string $table The name of the table to optimise
 *
 * @return bool
 * @access private
 */
function optimize_table($table) {
	$table = sanitise_string($table);
	return elgg_get_database()->updateData("optimize table $table");
}

/**
 * Get the last database error for a particular database link
 *
 * @param resource $dblink The DB link
 *
 * @return string Database error message
 * @access private
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
 * @warning Errors do not halt execution of the script.  If a line
 * generates an error, the error message is saved and the
 * next line is executed.  After the file is run, any errors
 * are displayed as a {@link DatabaseException}
 *
 * @param string $scriptlocation The full path to the script
 *
 * @return void
 * @throws DatabaseException
 * @access private
 */
function run_sql_script($scriptlocation) {
	return elgg_get_database()->runSqlScript($scriptlocation);
}

/**
 * Format a query string for logging
 * 
 * @param string $query Query string
 * @return string
 * @access private
 */
function elgg_format_query($query) {
	// remove newlines and extra spaces so logs are easier to read
	return preg_replace('/\s\s+/', ' ', $query);
}

/**
 * Sanitise a string for database use, but with the option of escaping extra characters.
 *
 * @param string $string           The string to sanitise
 * @param string $extra_escapeable Extra characters to escape with '\\'
 *
 * @return string The escaped string
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
 *
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
 *
 * @return string Sanitised string
 */
function sanitize_string($string) {
	return sanitise_string($string);
}

/**
 * Sanitises an integer for database use.
 *
 * @param int  $int    Value to be sanitized
 * @param bool $signed Whether negative values should be allowed (true)
 * @return int
 */
function sanitise_int($int, $signed = true) {
	$int = (int) $int;

	if ($signed === false) {
		if ($int < 0) {
			$int = 0;
		}
	}

	return (int) $int;
}

/**
 * Sanitizes an integer for database use.
 * Wrapper function for alternate English spelling (@see sanitise_int)
 *
 * @param int  $int    Value to be sanitized
 * @param bool $signed Whether negative values should be allowed (true)
 * @return int
 */
function sanitize_int($int, $signed = true) {
	return sanitise_int($int, $signed);
}

/**
 * Display profiling information about db at NOTICE debug level upon shutdown.
 *
 * @return void
 * @access private
 */
function db_profiling_shutdown_hook() {
	global $dbcalls;

	// demoted to NOTICE as it corrupts javasript at DEBUG
	elgg_log("DB Queries for this page: $dbcalls", 'NOTICE');
}

/**
 * Execute any delayed queries upon shutdown.
 *
 * @return void
 * @access private
 */
function db_delayedexecution_shutdown_hook() {
	elgg_get_database()->executeDelayedQueries();
}

/**
 * Registers shutdown functions for database profiling and delayed queries.
 *
 * @access private
 */
function init_db() {
	register_shutdown_function('db_delayedexecution_shutdown_hook');
	register_shutdown_function('db_profiling_shutdown_hook');
}

elgg_register_event_handler('init', 'system', 'init_db');
