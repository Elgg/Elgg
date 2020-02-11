<?php
/**
 * Elgg system log.
 * Listens to events and writes crud events into the system log database.
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
function system_log_get_log(array $options = []) {
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
 * @param SystemLogEntry|int $entry The log entry row or its ID
 *
 * @return ElggData|false
 * @throws DatabaseException
 */
function system_log_get_object_from_log_entry($entry) {
	if (is_numeric($entry)) {
		$entry = system_log_get_log_entry($entry);
	}
	
	if (!$entry instanceof SystemLogEntry) {
		return false;
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
