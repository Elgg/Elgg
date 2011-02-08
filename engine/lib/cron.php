<?php
/**
 * Elgg cron library.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Cron initialization
 *
 * @return void
 */
function cron_init() {
	// Register a pagehandler for cron
	register_page_handler('cron', 'cron_page_handler');

	// register a hook for Walled Garden public pages
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'cron_public_pages');
}

/**
 * Cron handler
 *
 * @param array $page Pages
 *
 * @return void
 */
function cron_page_handler($page) {
	global $CONFIG;

	if (!isset($page[0])) {
		forward();
	}

	$period = strtolower($page[0]);

	$allowed_periods = array(
		'minute', 'fiveminute', 'fifteenmin', 'halfhour', 'hourly',
		'daily', 'weekly', 'monthly', 'yearly', 'reboot'
	);

	if (!in_array($period, $allowed_periods)) {
		throw new CronException(elgg_echo('CronException:unknownperiod', array($period)));
	}

	// Get a list of parameters
	$params = array();
	$params['time'] = time();

	foreach ($CONFIG->input as $k => $v) {
		$params[$k] = $v;
	}

	// Data to return to
	$std_out = "";
	$old_stdout = "";
	ob_start();

	$old_stdout = elgg_trigger_plugin_hook('cron', $period, $params, $old_stdout);
	$std_out = ob_get_clean();

	echo $std_out . $old_stdout;
}

/**
 * Register cron's pages as public in case we're in Walled Garden mode
 *
 * @param string $hook         public_pages
 * @param string $type         system
 * @param array  $return_value Array of pages to allow
 * @param mixed  $params       Params
 *
 * @return array
 */
function cron_public_pages($hook, $type, $return_value, $params) {
	$return_value[] = 'pg/cron/minute';
	$return_value[] = 'pg/cron/fiveminute';
	$return_value[] = 'pg/cron/fifteenmin';
	$return_value[] = 'pg/cron/halfhour';
	$return_value[] = 'pg/cron/hourly';
	$return_value[] = 'pg/cron/daily';
	$return_value[] = 'pg/cron/weekly';
	$return_value[] = 'pg/cron/monthly';
	$return_value[] = 'pg/cron/yearly';
	$return_value[] = 'pg/cron/reboot';

	return $return_value;
}

elgg_register_event_handler('init', 'system', 'cron_init');
