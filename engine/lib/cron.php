<?php
/**
 * Elgg cron library.
 *
 * @package    Elgg.Core
 * @subpackage Cron
 */

/**
 * Cron initialization
 *
 * @return void
 * @access private
 */
function _elgg_cron_init() {
	elgg_register_page_handler('cron', '_elgg_cron_page_handler');

	elgg_register_plugin_hook_handler('cron', 'all', '_elgg_cron_monitor', 1000);

	elgg_set_config('elgg_cron_periods', array(
		'minute',
		'fiveminute',
		'fifteenmin',
		'halfhour',
		'hourly',
		'daily',
		'weekly',
		'monthly',
		'yearly',
		// reboot is deprecated and probably does not work
		'reboot',
	));

	elgg_register_admin_menu_item('administer', 'cron', 'statistics');
}

/**
 * Cron handler
 *
 * @param array $page Pages
 *
 * @return bool
 * @throws CronException
 * @access private
 */
function _elgg_cron_page_handler($page) {
	echo elgg_view('resources/cron', ['segments' => $page]);
	return true;
}

/**
 * Record cron running
 *
 * @param string $hook   Hook name
 * @param string $period Cron period
 * @param string $output Output content
 * @param array  $params Hook parameters
 * @return void
 * @access private
 */
function _elgg_cron_monitor($hook, $period, $output, $params) {
	$time = $params['time'];
	$periods = elgg_get_config('elgg_cron_periods');

	if (in_array($period, $periods)) {
		$key = "cron_latest:$period:ts";
		elgg_get_site_entity()->setPrivateSetting($key, $time);
	}
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_cron_init');
};
