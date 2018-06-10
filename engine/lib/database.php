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
 * Runs unit tests for the database
 *
 * @param string $hook  'unit_test'
 * @param string $type  'system'
 * @param array  $value Array of tests
 *
 * @return array
 * @access private
 * @codeCoverageIgnore
 */
function _elgg_db_test($hook, $type, $value) {
	$value[] = ElggDataFunctionsTest::class;
	return $value;
}

/**
 * Register database seeds
 *
 * @elgg_plugin_hook seeds database
 *
 * @param \Elgg\Hook $hook Hook
 * @return array
 */
function _elgg_db_register_seeds(\Elgg\Hook $hook) {

	$seeds = $hook->getValue();

	$seeds[] = \Elgg\Database\Seeds\Users::class;
	$seeds[] = \Elgg\Database\Seeds\Groups::class;

	return $seeds;
}

/**
 * Registers shutdown functions for database profiling and delayed queries.
 *
 * @return void
 *
 * @access private
 */
function _elgg_db_init() {
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_db_test');
	elgg_register_plugin_hook_handler('seeds', 'database', '_elgg_db_register_seeds');
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_db_init');
};
