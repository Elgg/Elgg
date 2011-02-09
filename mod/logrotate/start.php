<?php
/**
 * Elgg log rotator.
 *
 * @package ElggLogRotate
 */

elgg_register_event_handler('init', 'system', 'logrotate_init');

function logrotate_init() {
	$period = elgg_get_plugin_setting('period', 'logrotate');
	switch ($period) {
		case 'weekly':
		case 'monthly' :
		case 'yearly' :
			break;
		default:
			$period = 'monthly';
	}

	// Register cron hook
	elgg_register_plugin_hook_handler('cron', $period, 'logrotate_cron');
}

/**
 * Trigger the log rotation.
 */
function logrotate_cron($hook, $entity_type, $returnvalue, $params) {
	$resulttext = elgg_echo("logrotate:logrotated");

	$day = 86400;

	$offset = 0;
	$period = elgg_get_plugin_setting('period', 'logrotate');
	switch ($period) {
		case 'weekly':
			$offset = $day * 7;
			break;
		case 'yearly':
			$offset = $day * 365;
			break;
		case 'monthly':
		default:
			// assume 28 days even if a month is longer. Won't cause data loss.
			$offset = $day * 28;
	}

	if (!archive_log($offset)) {
		$resulttext = elgg_echo("logrotate:lognotrotated");
	}

	return $returnvalue . $resulttext;
}
