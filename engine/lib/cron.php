<?php
/**
 * Elgg cron library.
 */

/**
 * Cron initialization
 *
 * @return void
 * @internal
 */
function _elgg_cron_init() {
	elgg_register_menu_item('page', [
		'name' => 'cron',
		'text' => elgg_echo('admin:cron'),
		'href' => 'admin/cron',
		'section' => 'information',
		'context' => 'admin',
	]);
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function (\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_cron_init');
};
