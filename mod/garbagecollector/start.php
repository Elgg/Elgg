<?php
/**
 * Elgg garbage collector.
 *
 * @package ElggGarbageCollector
 */

elgg_register_event_handler('init', 'system', 'garbagecollector_init');

function garbagecollector_init() {
	$period = elgg_get_plugin_setting('period', 'garbagecollector');
	switch ($period) {
		case 'weekly':
		case 'monthly':
		case 'yearly':
			break;
		default:
			$period = 'monthly';
	}

	// Register cron hook
	elgg_register_plugin_hook_handler('cron', $period, 'garbagecollector_cron');
	
	elgg_register_plugin_hook_handler('gc', 'system', 'garbagecollector_entities');
}

/**
 * Cron job
 */
function garbagecollector_cron($hook, $entity_type, $returnvalue, $params) {

	echo elgg_echo('garbagecollector') . "\n";

	// Now, because we are nice, trigger a plugin hook to let other plugins do some GC
	$rv = true;
	$period = elgg_get_plugin_setting('period', 'garbagecollector');
	elgg_trigger_plugin_hook('gc', 'system', ['period' => $period]);

	// Now we optimize all tables
	$tables = garbagecollector_get_tables();
	foreach ($tables as $table) {
		echo elgg_echo('garbagecollector:optimize', [$table]);

		if (garbagecollector_optimize_table($table) !== false) {
			echo elgg_echo('garbagecollector:ok');
		} else {
			echo elgg_echo('garbagecollector:error');
		}

		echo "\n";
	}

	echo elgg_echo('garbagecollector:done');
}

/**
 * Get array of table names
 *
 * @return array
 */
function garbagecollector_get_tables() {
	static $tables;

	if (isset($tables)) {
		return $tables;
	}

	$table_prefix = elgg_get_config('dbprefix');
	$result = get_data("SHOW TABLES LIKE '$table_prefix%'");

	$tables = [];
	if (is_array($result) && !empty($result)) {
		foreach ($result as $row) {
			$row = (array) $row;
			if (is_array($row) && !empty($row)) {
				foreach ($row as $element) {
					$tables[] = $element;
				}
			}
		}
	}

	return $tables;
}

/**
 * Optimize a table
 *
 * @param string $table Database table name
 * @return bool
 */
function garbagecollector_optimize_table($table) {
	$table = sanitise_string($table);
	return update_data("OPTIMIZE TABLE $table");
}

/**
 * Garbage collect stub and fragments from any broken delete/create calls
 *
 * @return void
 */
function garbagecollector_entities() {
	$dbprefix = elgg_get_config('dbprefix');

	$tables = [
		'site' => 'sites_entity',
		'object' => 'objects_entity',
		'group' => 'groups_entity',
		'user' => 'users_entity',
	];

	foreach ($tables as $type => $table) {
		delete_data("DELETE FROM {$dbprefix}{$table}
		WHERE guid NOT IN (SELECT guid FROM {$dbprefix}entities)");
		delete_data("DELETE FROM {$dbprefix}entities
		WHERE type = '$type' AND guid NOT IN (SELECT guid FROM {$dbprefix}{$table})");
	}
}
