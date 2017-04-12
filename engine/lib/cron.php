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

	elgg_set_config('elgg_cron_periods', [
		'minute',
		'fiveminute',
		'fifteenmin',
		'halfhour',
		'hourly',
		'daily',
		'weekly',
		'monthly',
		'yearly',
	]);

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
	if (!isset($page[0])) {
		forward();
	}

	if (PHP_SAPI !== 'cli' && elgg_get_config('security_protect_cron')) {
		elgg_signed_request_gatekeeper();
	}
	
	$period = strtolower($page[0]);

	$allowed_periods = elgg_get_config('elgg_cron_periods');

	if (!in_array($period, $allowed_periods)) {
		throw new \CronException("$period is not a recognized cron period.");
	}

	// Get a list of parameters
	$params = [];
	$params['time'] = time();

	// Data to return to
	$old_stdout = "";
	ob_start();

	$msg_key = "cron_latest:$period:msg";
	$msg = elgg_echo('admin:cron:started', [$period, date('r', time())]);
	elgg_get_site_entity()->setPrivateSetting($msg_key, $msg);

	$old_stdout = elgg_trigger_plugin_hook('cron', $period, $params, $old_stdout);
	$std_out = ob_get_clean();

	$msg = $std_out . $old_stdout;
	echo $msg;

	elgg_get_site_entity()->setPrivateSetting($msg_key, $msg);

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
		echo elgg_echo('admin:cron:complete', [$period, date('r', $time)]);
	}
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_cron_init');
};
