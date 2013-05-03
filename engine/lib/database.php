<?php
/**
 * Elgg database procedural code.
 *
 * Includes functions for establishing and retrieving a database link,
 * reading data, writing data, upgrading DB schemas, and sanitizing input.
 *
 * @package    Elgg.Core
 * @subpackage Database
 */

/**
 * Query cache for all queries.
 *
 * Each query and its results are stored in this cache as:
 * <code>
 * $DB_QUERY_CACHE[query hash] => array(result1, result2, ... resultN)
 * </code>
 * @see elgg_query_runner() for details on the hash.
 *
 * @warning Elgg used to set this as an empty array to turn off the cache
 *
 * @global Elgg_Cache_LRUCache|null $DB_QUERY_CACHE
 * @access private
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
 * @access private
 */
global $DB_DELAYED_QUERIES;
$DB_DELAYED_QUERIES = array();


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
	_elgg_services()->db->setupConnections();
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
 * @return resource Database link
 * @access private
 */
function get_db_link($dblinktype) {
	return _elgg_services()->db->getLink($dblinktype);
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
	return _elgg_services()->db->registerDelayedQuery($query, $dblink, $handler);
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
 */
function get_data($query, $callback = "") {
	return _elgg_services()->db->getData($query, $callback);
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
 */
function get_data_row($query, $callback = "") {
	return _elgg_services()->db->getDataRow($query, $callback);
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
 */
function insert_data($query) {
	return _elgg_services()->db->insertData($query);
}

/**
 * Update a row in the database.
 *
 * @note Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
 *
 * @param string $query The query to run.
 *
 * @return bool
 */
function update_data($query) {
	return _elgg_services()->db->updateData($query);
}

/**
 * Remove a row from the database.
 *
 * @note Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
 *
 * @param string $query The SQL query to run
 *
 * @return int|false The number of affected rows or false on failure
 */
function delete_data($query) {
	return _elgg_services()->db->deleteData($query);
}

/**
 * Invalidate the query cache
 * 
 * @access private
 */
function _elgg_invalidate_query_cache() {
	_elgg_services()->db->invalidateQueryCache();
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
	return _elgg_services()->db->getTables();
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
	return _elgg_services()->db->updateData("optimize table $table");
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
	return _elgg_services()->db->runSqlScript($scriptlocation);
}

/**
 * Sanitise a string for database use.
 *
 * @param string $string The string to sanitise
 *
 * @return string Sanitised string
 */
function sanitise_string($string) {
	return mysql_real_escape_string($string);
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
 * Log db profiling information at NOTICE debug level upon shutdown.
 *
 * @return void
 * @access private
 */
function _elgg_db_log_profiling_data() {
	$db_calls = _elgg_services()->db->getQueryCount();

	// demoted to NOTICE as it corrupts javascript at DEBUG
	elgg_log("DB Queries for this page: $db_calls", 'NOTICE');
}

/**
 * Execute any delayed queries upon shutdown.
 *
 * @return void
 * @access private
 */
function _elgg_db_run_delayed_queries() {
	_elgg_services()->db->executeDelayedQueries();
}

/**
 * Registers shutdown functions for database profiling and delayed queries.
 *
 * @access private
 */
function _elgg_db_init() {
	register_shutdown_function('_elgg_db_run_delayed_queries');
	register_shutdown_function('_elgg_db_log_profiling_data');
}

elgg_register_event_handler('init', 'system', '_elgg_db_init');
