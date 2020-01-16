<?php
/**
 * Elgg database procedural code.
 *
 * Includes functions for reading data, writing data, and escaping queries.
 */

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
 * @internal
 */
function _elgg_db_init() {
	elgg_register_plugin_hook_handler('seeds', 'database', '_elgg_db_register_seeds', 1);
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_db_init');
};
