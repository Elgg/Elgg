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

	elgg_register_plugin_hook_handler('unit_test', 'system', '_logrotate_test');
}

/**
 * Trigger the log rotation.
 */
function logrotate_archive_cron($hook, $entity_type, $returnvalue, $params) {
	$resulttext = elgg_echo("logrotate:logrotated");

	$day = 86400;

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

	$archived = _logrotate_handle_crashed_table(function () use ($offset) {
		return archive_log($offset);
	});
	if (!$archived) {
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

	$period = elgg_get_plugin_setting('delete', 'logrotate');
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

	$deleted = _logrotate_handle_crashed_table(function () use ($offset) {
		return log_browser_delete_log($offset);
	});
	if (!$deleted) {
		$resulttext = elgg_echo("logrotate:lognotdeleted");
	}

	return $returnvalue . $resulttext;
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

/**
 * Call a function, catching DB table crashes
 *
 * @param callable $func    Function to call
 * @param mixed    $default Value to return on crashed table
 *
 * @return mixed
 * @throws \DatabaseException
 * @access private
 */
function _logrotate_handle_crashed_table(callable $func, $default = null) {
	try {
		return call_user_func($func);

	} catch (\DatabaseException $e) {
		if (!preg_match('~Table (.+?) is marked as crashed~', $e->getMessage(), $m)) {
			throw $e;
		}

		elgg_log($e->getMessage(), 'ERROR');
		$added = elgg_add_admin_notice("crash_table_" . md5($m[1]), elgg_echo('logrotate:table_crashed', [$m[1]]));
		if (!$added) {
			// already added notice
			return $default;
		}

		// notify oldest admin user ¯\_(ツ)_/¯
		$admins = elgg_get_admins([
			'order_by' => 'e.time_created ASC',
			'limit' => 1,
		]);
		if (!$admins) {
			return $default;
		}

		$admin = $admins[0];
		/* @var ElggUser $admin */

		$site = elgg_get_site_entity();

		notify_user(
			$admin->guid,
			$site->guid,
			elgg_echo('logrotate:table_crashed:subject', [$site->name], $admin->language),
			elgg_echo('logrotate:table_crashed', [$m[1]], $admin->language),
			[],
			'email'
		);

		return $default;
	}
}

/**
 * Runs unit tests
 *
 * @return array
 * @access private
 */
function _logrotate_test($hook, $type, $value, $params) {
	$value[] = __DIR__ . '/tests/LogRotateTest.php';
	return $value;
}
