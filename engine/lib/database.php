<?php
/**
 * Elgg database procedural code.
 *
 * Includes functions for reading data, writing data, and escaping queries.
 *
 * @package    Elgg.Core
 * @subpackage Database
 */

use Elgg\Database\QueryBuilder;

/**
 * Get an Elgg query builder instance (based on Doctrine DBAL)
 *
 * @return QueryBuilder
 */
function elgg_get_query_builder() {
	return _elgg_services()->db->getQueryBuilder();
}

/**
 * Queue a query for running during shutdown that writes to the database
 *
 * @param string|QueryBuilder $query   The query to execute
 * @param string              $handler The optional handler for processing the result
 *
 * @return boolean
 */
function execute_delayed_write_query($query, $handler = "") {
	return _elgg_services()->db->registerDelayedQuery($query, 'write', $handler);
}

/**
 * Queue a query for running during shutdown that reads from the database
 *
 * @param string|QueryBuilder $query   The query to execute
 * @param string              $handler The optional handler for processing the result
 *
 * @return boolean
 */
function execute_delayed_read_query($query, $handler = "") {
	return _elgg_services()->db->registerDelayedQuery($query, 'read', $handler);
}

/**
 * Retrieve rows from the database.
 *
 * Queries are executed with {@link execute_query()} and results
 * are retrieved with {@link \PDO::fetchObject()}.  If a callback
 * function $callback is defined, each row will be passed as the single
 * argument to $callback.  If no callback function is defined, the
 * entire result set is returned as an array.
 *
 * @param string|QueryBuilder $query    The query being passed.
 * @param callable            $callback Optionally, the name of a function to call back to on each row
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
 * @param string|QueryBuilder $query    The query to execute.
 * @param callable            $callback A callback function
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
 * @param string|QueryBuilder $query The query to execute.
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
 * @param string|QueryBuilder $query The query to run.
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
 * @param string|QueryBuilder $query The SQL query to run
 *
 * @return int|false The number of affected rows or false on failure
 */
function delete_data($query) {
	return _elgg_services()->db->deleteData($query);
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
 * @deprecated Use elgg_get_query_builder() where possible
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
 * @deprecated Use elgg_get_query_builder() where possible
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
 * @deprecated Use elgg_get_query_builder() where possible
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
 * @deprecated Use elgg_get_query_builder() where possible
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
 * Registers shutdown functions for database profiling and delayed queries.
 *
 * @access private
 */
function _elgg_db_init() {
	register_shutdown_function('_elgg_db_run_delayed_queries');
	register_shutdown_function('_elgg_db_log_profiling_data');
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_db_init');
};
