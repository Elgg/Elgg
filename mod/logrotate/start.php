<?php
/**
 * Elgg log rotator.
 *
 * @package ElggLogRotate
 */

elgg_register_event_handler('init', 'system', 'logrotate_init');

function logrotate_init() {
	$period = elgg_get_plugin_setting('period', 'logrotate');
	$time = elgg_get_plugin_setting('time', 'logrotate');
	switch ($period) {
		case 'weekly':
		case 'monthly' :
		case 'yearly' :
			break;
		default:
			$period = 'monthly';
	}

	// Register cron hook for archival of logs
	elgg_register_plugin_hook_handler('cron', $period, 'logrotate_archive_cron');
	// Register cron hook for deletion of selected archived logs
	elgg_register_plugin_hook_handler('cron', $time, 'logrotate_delete_cron');
}

/**
 * Trigger the log rotation.
 */
function logrotate_archive_cron($hook, $entity_type, $returnvalue, $params) {
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

/**
 * Trigger the log deletion.
 */
function logrotate_delete_cron($hook, $entity_type, $returnvalue, $params) {
	$resulttext = elgg_echo("logrotate:logdeleted");

	$day = 86400;

	$offset = 0;
	$period = elgg_get_plugin_setting('time', 'logrotate');
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

	if (!log_browser_delete_log($offset)) {
		$resulttext = elgg_echo("logrotate:lognotdeleted");
	}

	return $returnvalue . $resulttext;
}

/**
 * This function deletes archived copies of the system logs that are older than specified.
 *
 * @param int $time_of_delete An offset in seconds from now to delete (useful for log deletion)
 */

function log_browser_delete_log($time_of_delete) {
	global $CONFIG;

	$offset = (int)$time_of_delete;
	$now = time();

	$ts = $now - $offset;

	$FLAG = 1;      
	$result = mysql_query("SHOW TABLES like '{$CONFIG->dbprefix}system_log_%'");
	while ($showtablerow = mysql_fetch_array($result)) {
		//To obtain time of archival
		$log_time = explode("{$CONFIG->dbprefix}system_log_", $showtablerow[0]);
		if ($log_time < $ts) {
			//If the time of archival is before the required offset then delete
			if (!mysql_query("DROP TABLE $showtablerow[0]")) {
				$FLAG = 0;
			}	
		}
	}

	//Check if the appropriate tables have been deleted and return true if yes
	if ($FLAG) {
		return true;
	} else {
		return false;
	}

}
