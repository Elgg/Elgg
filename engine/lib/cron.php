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

	elgg_register_menu_item('page', [
		'name' => 'cron',
		'text' => elgg_echo('admin:cron'),
		'href' => 'admin/cron',
		'section' => 'information',
		'context' => 'admin',
	]);
}

/**
 * Cron run
 *
 * This function was designed to be called every one minute from a cron job to
 * executes each Elgg cron period at the desired interval.
 *
 * Can be called manually by: http://YOUR.SITE/cron/run/
 *
 * This will only execute cron periods at specified intervals to force execution
 * of a specific period you will need to use http://YOUR.SITE/cron/<period>/
 *
 * @return void
 *
 * @access private
 */
function _elgg_cron_run() {
	
	$starttime = time();
	
	$all_std_out = '';

	$periods = [
		'minute' => '* * * * *',
		'fiveminute' => '*/5 * * * *',
		'fifteenmin' => '*/15 * * * *',
		'halfhour' => '*/30 * * * *',
		'hourly' => '0 * * * *',
		'daily' => '0 0 * * *',
		'weekly' => '0 0 * * 0',
		'monthly' => '0 0 1 * *',
		'yearly' => '0 0 1 1 *',
	];
	
	// prepare scheduler with all cron intervals
	$scheduler = new GO\Scheduler();
	
	foreach ($periods as $period => $expression) {
		$scheduler->call('_elgg_cron_execute_period', [$period, $starttime])->at($expression)->then(function($output) use (&$all_std_out) {
			$all_std_out .= $output . PHP_EOL;
		});
	}
	
	try {
		$scheduler->run();
	} catch (\CronException $cron_exception) {
		$all_std_out .= "Exception {$cron_exception->getMessage()}" . PHP_EOL;
	}
	
	echo $all_std_out;
}

/**
 * /cron handler
 *
 * @param array $page URL segments
 *
 * @return bool
 * @access private
 */
function _elgg_cron_page_handler($page) {
	
	if (PHP_SAPI !== 'cli' && _elgg_config()->security_protect_cron) {
		elgg_signed_request_gatekeeper();
	}
	
	$period = strtolower(elgg_extract(0, $page));
	switch ($period) {
		case 'run':
			_elgg_cron_run();
			break;
		default:
			echo _elgg_cron_execute_period($period);
			break;
	}
	
	return true;
}

/**
 * Execute a cron interval
 *
 * @param string $period    the cron interval to execute
 * @param int    $starttime when was the cron started (default: time())
 *
 * @throws \CronException
 * @return string
 *
 * @since 3.0
 * @internal
 */
function _elgg_cron_execute_period($period, $starttime = null) {
	
	$allowed_periods = _elgg_config()->elgg_cron_periods;
	if (!in_array($period, $allowed_periods)) {
		throw new \CronException("$period is not a recognized cron period.");
	}
	
	if (!isset($starttime)) {
		$starttime = time();
	}
	
	// give every period at least 'max_execution_time' (PHP ini setting)
	set_time_limit((int) ini_get('max_execution_time'));
	
	// Prepare params for hook
	$params = [];
	$params['time'] = $starttime;

	// Data to return to
	ob_start();

	$msg_key = "cron_latest:$period:msg";
	$msg = elgg_echo('admin:cron:started', [$period, date('r', $starttime)]) . PHP_EOL;
	
	// make sure we log the start of the cron
	elgg_get_site_entity()->setPrivateSetting($msg_key, $msg);
	
	// trigger hook to allow others to execute tasks
	$old_stdout = elgg_trigger_plugin_hook('cron', $period, $params, '');
	
	$std_out = ob_get_clean();

	$msg .= $std_out . $old_stdout;
	
	// log the message to the site
	elgg_get_site_entity()->setPrivateSetting($msg_key, $msg);
	
	return $msg;
}

/**
 * Record cron completion time
 *
 * @param string $hook   'cron'
 * @param string $period Cron period
 * @param string $output Output content
 * @param array  $params Hook parameters
 *
 * @return void
 *
 * @access private
 */
function _elgg_cron_monitor($hook, $period, $output, $params) {
	
	$periods = _elgg_config()->elgg_cron_periods;
	if (!in_array($period, $periods)) {
		return;
	}
	
	$completed_time = time();
	
	$key = "cron_latest:$period:ts";
	elgg_get_site_entity()->setPrivateSetting($key, $completed_time);
	
	echo elgg_echo('admin:cron:complete', [$period, date('r', $completed_time)]) . PHP_EOL;
}

/**
 * @see \Elgg\Application\Bootstrap::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_cron_init');
};
