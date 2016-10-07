<?php
/**
 * Elgg log rotator.
 *
 * @package ElggLogRotate
 */

elgg_register_event_handler('init', 'system', 'logrotate_init');

function logrotate_init() {
	$period = elgg_get_plugin_setting('period', 'logrotate');
	$delete = elgg_get_plugin_setting('delete', 'logrotate');
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

	if ($delete != 'never') {
		// Register cron hook for deletion of selected archived logs
		elgg_register_plugin_hook_handler('cron', $delete, 'logrotate_delete_cron');
	}
}

/**
 * Trigger the log rotation.
 */
function logrotate_archive_cron($hook, $entity_type, $returnvalue, $params) {
	$resulttext = elgg_echo("logrotate:logrotated");

	$period = elgg_get_plugin_setting('period', 'logrotate');
	$offset = logrotate_get_seconds_in_period($period);

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

	$period = elgg_get_plugin_setting('delete', 'logrotate');
	$offset = logrotate_get_seconds_in_period($period);

	if (!log_browser_delete_log($offset)) {
		$resulttext = elgg_echo("logrotate:lognotdeleted");
	}

	return $returnvalue . $resulttext;
}


/**
 * @param $period
 * @return int
 */
function logrotate_get_seconds_in_period($period)
{
	$seconds_in_day = 86400;
	switch ($period) {
		case 'weekly':
			$offset = $seconds_in_day * 7;
			break;
		case 'yearly':
			$offset = $seconds_in_day * 365;
			break;
		case 'monthly':
		default:
			// assume 28 days even if a month is longer. Won't cause data loss.
			$offset = $seconds_in_day * 28;
	}
	return $offset;
}

/**
 * This function deletes archived copies of the system logs that are older than specified.
 *
 * @param int $time_of_delete An offset in seconds from now to delete log tables
 * @return bool Were any log tables deleted
 */
function log_browser_delete_log($time_of_delete) {
	global $CONFIG;

	$cutoff = time() - (int)$time_of_delete;

	$deleted_tables = false;
	$results = get_data("SHOW TABLES like '{$CONFIG->dbprefix}system_log_%'");
	if ($results) {
		foreach ($results as $result) {
			$data = (array)$result;
			$table_name = array_shift($data);
			// extract log table rotation time
			$log_time = str_replace("{$CONFIG->dbprefix}system_log_", '', $table_name);
			if ($log_time < $cutoff) {
				if (delete_data("DROP TABLE $table_name") !== false) {
					// delete_data returns 0 when dropping a table (false for failure)
					$deleted_tables = true;
				} else {
					elgg_log("Failed to delete the log table $table_name", 'ERROR');
				}
			}
		}
	}

	return $deleted_tables;
}
