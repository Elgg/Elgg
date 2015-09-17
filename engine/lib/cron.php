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
 * Cron run
 *
 * This function was designed to be called every one minute from a cron job to
 * executes each Elgg cron period at the desired interval.  Will also exeute
 * any Elgg cron period that have not fired by the expected deadline.
 *
 * Can be called manually by: http://YOUR.SITE/cron/run/
 *
 * This will only execute cron periods at specified intervals to force execution
 * of a specific period you will need to use http://YOUR.SITE/cron/<period>/
 *
 * @access private
 */
function _elgg_cron_run() {
	$now = time();
	$params = array();
	$params['time'] = $now;

	// Data to return to
	$old_stdout = "";
	$std_out = "";
	ob_start();

	$periods = array(
		'minute' => 60,
		'fiveminute' => 300,
		'fifteenmin' => 900,
		'halfhour' => 1800,
		'hourly' => 3600,
		'daily' => 86400,
		'weekly' => 604800,
		'monthly' => 2628000,
		'yearly' => 31536000,
		'reboot' => 31536000,
	);

	foreach ($periods as $period => $interval) {
		$key = "cron_latest:$period:ts";
		$ts = elgg_get_site_entity()->getPrivateSetting($key);
		$deadline = $ts + $interval;
		if ($now > $deadline) {
			$old_stdout = elgg_trigger_plugin_hook('cron', $period, $params, $old_stdout);
			$std_out .= ob_clean();
			$std_out .= $old_stdout;
		}
	}

	$std_out .= ob_get_clean();
	echo $std_out;
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
	if (!isset($page[0])) {
		forward();
	}

	$period = strtolower($page[0]);

	$allowed_periods = elgg_get_config('elgg_cron_periods');

	if (($period != 'run') && !in_array($period, $allowed_periods)) {
		throw new \CronException("$period is not a recognized cron period.");
	}

	if ($period == 'run') {
		_elgg_cron_run();
	} else {
		// Get a list of parameters
		$params = array();
		$params['time'] = time();

		// Data to return to
		$old_stdout = "";
		ob_start();

		$old_stdout = elgg_trigger_plugin_hook('cron', $period, $params, $old_stdout);
		$std_out = ob_get_clean();

		echo $std_out . $old_stdout;
	}
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
