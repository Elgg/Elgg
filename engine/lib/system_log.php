<?php
/**
 * Elgg system log.
 * Listens to events and writes crud events into the system log database.
 *
 * @package Elgg.Core
 * @subpackage Logging
 */

/**
 * Retrieve the system log based on a number of parameters.
 *
 * @todo too many args, and the first arg is too confusing
 *
 * @param int|array $by_user    The guid(s) of the user(s) who initiated the event.
 *                              Use 0 for unowned entries. Anything else falsey means anyone.
 * @param string    $event      The event you are searching on.
 * @param string    $class      The class of object it effects.
 * @param string    $type       The type
 * @param string    $subtype    The subtype.
 * @param int       $limit      Maximum number of responses to return.
 * @param int       $offset     Offset of where to start.
 * @param bool      $count      Return count or not
 * @param int       $timebefore Lower time limit
 * @param int       $timeafter  Upper time limit
 * @param int       $object_id  GUID of an object
 * @param string    $ip_address The IP address.
 * @return mixed
 */
function get_system_log($by_user = "", $event = "", $class = "", $type = "", $subtype = "", $limit = 10,
						$offset = 0, $count = false, $timebefore = 0, $timeafter = 0, $object_id = 0,
						$ip_address = "") {

	global $CONFIG;

	$by_user_orig = $by_user;
	if (is_array($by_user) && sizeof($by_user) > 0) {
		foreach ($by_user as $key => $val) {
			$by_user[$key] = (int) $val;
		}
	} else {
		$by_user = (int)$by_user;
	}
	
	$event = sanitise_string($event);
	$class = sanitise_string($class);
	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);
	$ip_address = sanitise_string($ip_address);
	$limit = (int)$limit;
	$offset = (int)$offset;

	$where = array();

	if ($by_user_orig !== "" && $by_user_orig !== false && $by_user_orig !== null) {
		if (is_int($by_user)) {
			$where[] = "performed_by_guid=$by_user";
		} else if (is_array($by_user)) {
			$where [] = "performed_by_guid in (" . implode(",", $by_user) . ")";
		}
	}
	if ($event != "") {
		$where[] = "event='$event'";
	}
	if ($class !== "") {
		$where[] = "object_class='$class'";
	}
	if ($type != "") {
		$where[] = "object_type='$type'";
	}
	if ($subtype !== "") {
		$where[] = "object_subtype='$subtype'";
	}

	if ($timebefore) {
		$where[] = "time_created < " . ((int) $timebefore);
	}
	if ($timeafter) {
		$where[] = "time_created > " . ((int) $timeafter);
	}
	if ($object_id) {
		$where[] = "object_id = " . ((int) $object_id);
	}
	if ($ip_address) {
		$where[] = "ip_address = '$ip_address'";
	}

	$select = "*";
	if ($count) {
		$select = "count(*) as count";
	}
	$query = "SELECT $select from {$CONFIG->dbprefix}system_log where 1 ";
	foreach ($where as $w) {
		$query .= " and $w";
	}

	if (!$count) {
		$query .= " order by time_created desc";
		$query .= " limit $offset, $limit"; // Add order and limit
	}

	if ($count) {
		$numrows = get_data_row($query);
		if ($numrows) {
			return $numrows->count;
		}
	} else {
		return get_data($query);
	}

	return false;
}

/**
 * Return a specific log entry.
 *
 * @param int $entry_id The log entry
 *
 * @return mixed
 */
function get_log_entry($entry_id) {
	global $CONFIG;

	$entry_id = (int)$entry_id;

	return get_data_row("SELECT * from {$CONFIG->dbprefix}system_log where id=$entry_id");
}

/**
 * Return the object referred to by a given log entry
 *
 * @param int $entry_id The log entry
 *
 * @return mixed
 */
function get_object_from_log_entry($entry_id) {
	$entry = get_log_entry($entry_id);

	if ($entry) {
		$class = $entry->object_class;
		// surround with try/catch because object could be disabled
		try {
			$object = new $class($entry->object_id);
		} catch (Exception $e) {
			
		}
		if ($object) {
			return $object;
		}
	}

	return false;
}

/**
 * Log a system event related to a specific object.
 *
 * This is called by the event system and should not be called directly.
 *
 * @param object $object The object you're talking about.
 * @param string $event  The event being logged
 * @return void
 */
function system_log($object, $event) {
	global $CONFIG;
	static $log_cache;
	static $cache_size = 0;

	if ($object instanceof Loggable) {

		/* @var ElggEntity|ElggExtender $object */
		if (datalist_get('version') < 2012012000) {
			// this is a site that doesn't have the ip_address column yet
			return;
		}

		// reset cache if it has grown too large
		if (!is_array($log_cache) || $cache_size > 500) {
			$log_cache = array();
			$cache_size = 0;
		}

		// Has loggable interface, extract the necessary information and store
		$object_id = (int)$object->getSystemLogID();
		$object_class = $object->getClassName();
		$object_type = $object->getType();
		$object_subtype = $object->getSubtype();
		$event = sanitise_string($event);
		$time = time();
		$ip_address = sanitise_string($_SERVER['REMOTE_ADDR']);
		$performed_by = elgg_get_logged_in_user_guid();

		if (isset($object->access_id)) {
			$access_id = $object->access_id;
		} else {
			$access_id = ACCESS_PUBLIC;
		}
		if (isset($object->enabled)) {
			$enabled = $object->enabled;
		} else {
			$enabled = 'yes';
		}

		if (isset($object->owner_guid)) {
			$owner_guid = $object->owner_guid;
		} else {
			$owner_guid = 0;
		}

		// Create log if we haven't already created it
		if (!isset($log_cache[$time][$object_id][$event])) {
			$query = "INSERT into {$CONFIG->dbprefix}system_log
				(object_id, object_class, object_type, object_subtype, event,
				performed_by_guid, owner_guid, access_id, enabled, time_created, ip_address)
			VALUES
				('$object_id','$object_class','$object_type', '$object_subtype', '$event',
				$performed_by, $owner_guid, $access_id, '$enabled', '$time', '$ip_address')";

			insert_data($query);

			$log_cache[$time][$object_id][$event] = true;
			$cache_size += 1;
		}
	}
}

/**
 * This function creates an archive copy of the system log.
 *
 * @param int $offset An offset in seconds from now to archive (useful for log rotation)
 *
 * @return bool
 */
function archive_log($offset = 0) {
	global $CONFIG;

	$offset = (int)$offset;
	$now = time(); // Take a snapshot of now

	$ts = $now - $offset;

	// create table
	$query = "CREATE TABLE {$CONFIG->dbprefix}system_log_$now as
		SELECT * from {$CONFIG->dbprefix}system_log WHERE time_created<$ts";

	if (!update_data($query)) {
		return false;
	}

	// delete
	// Don't delete on time since we are running in a concurrent environment
	if (delete_data("DELETE from {$CONFIG->dbprefix}system_log WHERE time_created<$ts") === false) {
		return false;
	}

	// alter table to engine
	if (!update_data("ALTER TABLE {$CONFIG->dbprefix}system_log_$now engine=archive")) {
		return false;
	}

	return true;
}

/**
 * Default system log handler, allows plugins to override, extend or disable logging.
 *
 * @param string   $event       Event name
 * @param string   $object_type Object type
 * @param Loggable $object      Object to log
 *
 * @return true
 */
function system_log_default_logger($event, $object_type, $object) {
	system_log($object['object'], $object['event']);

	return true;
}

/**
 * System log listener.
 * This function listens to all events in the system and logs anything appropriate.
 *
 * @param String   $event       Event name
 * @param String   $object_type Type of object
 * @param Loggable $object      Object to log
 *
 * @return true
 * @access private
 */
function system_log_listener($event, $object_type, $object) {
	if (($object_type != 'systemlog') && ($event != 'log')) {
		elgg_trigger_event('log', 'systemlog', array('object' => $object, 'event' => $event));
	}

	return true;
}

/** Register event to listen to all events **/
elgg_register_event_handler('all', 'all', 'system_log_listener', 400);

/** Register a default system log handler */
elgg_register_event_handler('log', 'systemlog', 'system_log_default_logger', 999);
