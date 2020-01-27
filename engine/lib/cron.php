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
