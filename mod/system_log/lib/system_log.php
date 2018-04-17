<?php
/**
 * Elgg system log.
 * Listens to events and writes crud events into the system log database.
 *
 * @package    Elgg.Core
 * @subpackage Logging
 */

use Elgg\SystemLog\SystemLog;
use Elgg\SystemLog\SystemLogEntry;

/**
 * Retrieve the system log based on a number of parameters.
 *
 * @param array $options Options
 *
 * @option int       $limit             Maximum number of responses to return. (default from settings)
 * @option int       $offset            Offset of where to start.
 * @option bool      $count             Return count or not
 * @option int|array $performed_by_guid The guid(s) of the user(s) who initiated the event.
 * @option string    $event             The event you are searching on.
 * @option string    $object_class      The class of object it effects.
 * @option string    $object_type       The type
 * @option string    $object_subtype    The subtype.
 * @option int       $object_id         GUID of an object
 * @option int       $created_before    Lower time limit
 * @option int       $created_after     Upper time limit
 * @option string    $ip_address        The IP address.
 *
 * @return int|SystemLogEntry[]
 */
function system_log_get_log($options = null) {

	if (!is_array($options)) {
		elgg_deprecated_notice(__FUNCTION__ . ' accepts a single argument as an array of options', '3.0');

		$options = [];

		$arguments = func_get_args();
		$arguments = array_pad($arguments, 12, null);

		$options['performed_by_guid'] = $arguments[0];
		$options['event'] = $arguments[1];
		$options['object_class'] = $arguments[2];
		$options['object_type'] = $arguments[3];
		$options['object_subtype'] = $arguments[4];
		$options['limit'] = $arguments[5];
		$options['offset'] = $arguments[6];
		$options['count'] = $arguments[7];
		$options['created_before'] = $arguments[8];
		$options['created_after'] = $arguments[9];
		if ($arguments[10]) {
			// legacy usage of function uses 0 to signify all
			$options['object_id'] = $arguments[10];
		}
		$options['ip_address'] = $arguments[11];
	}

	return SystemLog::instance()->getAll($options);
}

/**
 * Return a specific log entry.
 *
 * @param int $entry_id The log entry
 *
 * @return SystemLogEntry|false
 * @throws DatabaseException
 */
function system_log_get_log_entry($entry_id) {
	return SystemLog::instance()->get($entry_id);
}

/**
 * Return the object referred to by a given log entry
 *
 * @param stdClass|int $entry The log entry row or its ID
 *
 * @return ElggData|false
 * @throws DatabaseException
 */
function system_log_get_object_from_log_entry($entry) {
	if (is_numeric($entry)) {
		$entry = system_log_get_log_entry($entry);
		if (!$entry) {
			return false;
		}
	}

	return $entry->getObject();
}

/**
 * Log a system event related to a specific object.
 *
 * This is called by the event system and should not be called directly.
 *
 * @param object $object The object you're talking about.
 * @param string $event  The event being logged
 *
 * @return void
 */
function system_log($object, $event) {
	SystemLog::instance()->insert($object, $event);
}

/**
 * This function creates an archive copy of the system log.
 *
 * @param int $offset An offset in seconds that has passed since the time of log entry
 *
 * @return bool
 * @throws DatabaseException
 */
function system_log_archive_log($offset = 0) {

	$log = SystemLog::instance();

	$time = $log->getCurrentTime()->getTimestamp();

	$cutoff = $time - (int) $offset;

	$created_before = new DateTime();
	$created_before->setTimestamp($cutoff);

	return $log->archive($created_before);
}

/**
 * Convert an interval to seconds
 *
 * @param string $period interval
 *
 * @return int
 */
function system_log_get_seconds_in_period($period) {
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
 * This function deletes archived copies of the system logs that are older than specified offset
 *
 * @param int $offset An offset in seconds that has passed since the time of archival
 *
 * @return bool
 * @throws DatabaseException
 */
function system_log_browser_delete_log($offset) {

	$log = SystemLog::instance();

	$time = $log->getCurrentTime()->getTimestamp();

	$cutoff = $time - (int) $offset;

	$archived_before = new DateTime();
	$archived_before->setTimestamp($cutoff);

	return $log->deleteArchive($archived_before);
}