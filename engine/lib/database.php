<?php
/**
 * Elgg database procedural code.
 *
 * Includes functions for reading data, writing data, and escaping queries.
 *
 * @package    Elgg.Core
 * @subpackage Database
 */

/**
 * Queue a query for running during shutdown that writes to the database
 *
 * @param string   $query    The query to execute
 * @param callable $callback The optional callback for processing. The callback will receive a
 *                           \Doctrine\DBAL\Driver\Statement object
 * @param array    $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
 *
 * @return boolean
 */
function execute_delayed_write_query($query, $callback = null, array $params = []) {
	return _elgg_services()->db->registerDelayedQuery($query, 'write', $callback, $params);
}

/**
 * Queue a query for running during shutdown that reads from the database
 *
 * @param string   $query    The query to execute
 * @param callable $callback The optional callback for processing. The callback will receive a
 *                           \Doctrine\DBAL\Driver\Statement object
 * @param array    $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
 *
 * @return boolean
 */
function execute_delayed_read_query($query, $callback = null, array $params = []) {
	return _elgg_services()->db->registerDelayedQuery($query, 'read', $callback, $params);
}

/**
 * Retrieve rows from the database.
 *
 * Queries are executed with {@link \Elgg\Database::getResults} and results
 * are retrieved with {@link \PDO::fetchObject()}.  If a callback
 * function $callback is defined, each row will be passed as the single
 * argument to $callback.  If no callback function is defined, the
 * entire result set is returned as an array.
 *
 * @param string   $query    The query being passed.
 * @param callable $callback Optionally, the name of a function to call back to on each row
 * @param array    $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
 *
 * @return array An array of database result objects or callback function results. If the query
 *               returned nothing, an empty array.
 */
function get_data($query, $callback = null, array $params = []) {
	return _elgg_services()->db->getData($query, $callback, $params);
}

/**
 * Retrieve a single row from the database.
 *
 * Similar to {@link get_data()} but returns only the first row
 * matched.  If a callback function $callback is specified, the row will be passed
 * as the only argument to $callback.
 *
 * @param string   $query    The query to execute.
 * @param callable $callback A callback function to apply to the row
 * @param array    $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
 *
 * @return mixed A single database result object or the result of the callback function.
 */
function get_data_row($query, $callback = null, array $params = []) {
	return _elgg_services()->db->getDataRow($query, $callback, $params);
}

/**
 * Insert a row into the database.
 *
 * @note Altering the DB invalidates all queries in the query cache
 *
 * @param string $query  The query to execute.
 * @param array  $params Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
 *
 * @return int|false The database id of the inserted row if a AUTO_INCREMENT field is
 *                   defined, 0 if not, and false on failure.
 */
function insert_data($query, array $params = []) {
	return _elgg_services()->db->insertData($query, $params);
}

/**
 * Update a row in the database.
 *
 * @note Altering the DB invalidates all queries in the query cache
 *
 * @param string $query        The query to run.
 * @param array  $params       Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
 * @param bool   $get_num_rows Return the number of rows affected (default: false).
 *
 * @return bool
 */
function update_data($query, array $params = [], $get_num_rows = false) {
	return _elgg_services()->db->updateData($query, $get_num_rows, $params);
}

/**
 * Remove a row from the database.
 *
 * @note Altering the DB invalidates all queries in the query cache
 *
 * @param string $query  The SQL query to run
 * @param array  $params Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
 *
 * @return int|false The number of affected rows or false on failure
 */
function delete_data($query, array $params = []) {
	return _elgg_services()->db->deleteData($query, $params);
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
 */
function run_sql_script($scriptlocation) {
	_elgg_services()->db->runSqlScript($scriptlocation);
}

/**
 * Sanitizes a string for use in a query
 *
 * @see Elgg\Database::sanitizeString
 *
 * @param string $string The string to sanitize
 * @return string
 * @deprecated Use query parameters where possible
 */
function sanitize_string($string) {
	return _elgg_services()->db->sanitizeString($string);
}

/**
 * Alias of sanitize_string
 *
 * @see Elgg\Database::sanitizeString
 *
 * @param string $string The string to sanitize
 * @return string
 * @deprecated Use query parameters where possible
 */
function sanitise_string($string) {
	return _elgg_services()->db->sanitizeString($string);
}

/**
 * Sanitizes an integer for database use.
 *
 * @see Elgg\Database::sanitizeInt
 *
 * @param int  $int    Value to be sanitized
 * @param bool $signed Whether negative values should be allowed (true)
 * @return int
 * @deprecated Use query parameters where possible
 */
function sanitize_int($int, $signed = true) {
	return _elgg_services()->db->sanitizeInt($int, $signed);
}

/**
 * Alias of sanitize_int
 *
 * @see sanitize_int
 *
 * @param int  $int    Value to be sanitized
 * @param bool $signed Whether negative values should be allowed (true)
 * @return int
 * @deprecated Use query parameters where possible
 */
function sanitise_int($int, $signed = true) {
	return sanitize_int($int, $signed);
}

/**
 * Enable the MySQL query cache
 *
 * @return void
 *
 * @since 2.0.0
 */
function elgg_enable_query_cache() {
	_elgg_services()->db->enableQueryCache();
}

/**
 * Disable the MySQL query cache
 *
 * @note Elgg already manages the query cache sensibly, so you probably don't need to use this.
 *
 * @return void
 *
 * @since 2.0.0
 */
function elgg_disable_query_cache() {
	_elgg_services()->db->disableQueryCache();
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
	elgg_log("DB Queries for this page: $db_calls", 'INFO');
}

/**
 * Get a new query counter that will begin counting from 0. For profiling isolated
 * sections of code.
 *
 * <code>
 * $counter = _elgg_db_get_query_counter();
 *
 * ... code to profile
 *
 * $counter->setDeltaHeader();
 * </code>
 *
 * @return \Elgg\Database\QueryCounter
 * @access private
 */
function _elgg_db_get_query_counter() {
	return _elgg_services()->queryCounter;
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
 * Runs unit tests for the database
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param array  $value  Array of tests
 *
 * @return array
 * @access private
 */
function _elgg_db_test($hook, $type, $value) {
	$value[] = elgg_get_engine_path() . '/tests/ElggDataFunctionsTest.php';
	return $value;
}

/**
 * Registers shutdown functions for database profiling and delayed queries.
 *
 * @access private
 */
function _elgg_db_init() {
	register_shutdown_function('_elgg_db_run_delayed_queries');
	register_shutdown_function('_elgg_db_log_profiling_data');
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_db_test');
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_db_init');
};
