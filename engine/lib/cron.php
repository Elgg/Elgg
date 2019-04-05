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
	elgg_set_config('elgg_cron_periods', array_keys(\Elgg\Cron::$intervals));

	elgg_register_menu_item('page', [
		'name' => 'cron',
		'text' => elgg_echo('admin:cron'),
		'href' => 'admin/cron',
		'section' => 'information',
		'context' => 'admin',
	]);
}

/**
 * /cron handler
 *
 * @param array $segments URL segments
 *
 * @return bool
 * @access private
 */
function _elgg_cron_page_handler($segments) {

	if (_elgg_config()->security_protect_cron) {
		elgg_signed_request_gatekeeper();
	}

	$interval = strtolower(array_shift($segments));

	$intervals = null;
	if ($interval !== 'run') {
		$intervals = [$interval];
	}

	$output = '';
	try {
		$force = (bool) get_input('force');
		$jobs = _elgg_services()->cron->run($intervals, $force);
		foreach ($jobs as $job) {
			$output .= $job->getOutput() . PHP_EOL;
		}
	} catch (CronException $ex) {
		$output .= "Exception: {$ex->getMessage()}";
	}

	echo nl2br($output);
	return true;
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function (\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_cron_init');
};
