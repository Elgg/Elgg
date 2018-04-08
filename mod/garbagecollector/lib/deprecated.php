<?php
/**
 * Elgg garbage collector.
 *
 * @package ElggGarbageCollector
 */

/**
 * Get array of table names
 *
 * @return array
 * @throws DatabaseException
 *
 * @deprecated 3.0 Use elgg()->garbagecollector->tables()
 * @codeCoverageIgnore
 */
function garbagecollector_get_tables() {
	return \Elgg\GarbageCollector\GarbageCollector::instance()->tables();
}

/**
 * Optimize a table
 *
 * @param string $table Database table name
 *
 * @return bool
 * @throws DatabaseException
 *
 * @deprecated 3.0 Use elgg()->garbagecollector->optimizeTable()
 * @codeCoverageIgnore
 */
function garbagecollector_optimize_table($table) {
	return \Elgg\GarbageCollector\GarbageCollector::instance()->optimizeTable($table);
}

