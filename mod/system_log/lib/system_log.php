<?php
/**
 * Elgg system log.
 * Listens to events and writes crud events into the system log database.
 *
 * @package    Elgg.Core
 * @subpackage Logging
 */

use Elgg\SystemLog\SystemLogInsert;

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
 * @return int|stdClass[]
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
		$options['object_id'] = $arguments[10];
		$options['ip_address'] = $arguments[11];
	}

	$query = new \Elgg\SystemLog\SystemLogQuery();
	foreach ($options as $key => $value) {
		$query->$key = $value;
	}

	return $query->execute();
}

/**
 * Return a specific log entry.
 *
 * @param int $entry_id The log entry
 *
 * @return stdClass|false
 */
function system_log_get_log_entry($entry_id) {
	$entry_id = (int) $entry_id;

	$qb = \Elgg\Database\Select::fromTable('system_log');
	$qb->select('*');
	$qb->where($qb->compare('id', '=', $entry_id, ELGG_VALUE_INTEGER));

	return get_data_row($qb);
}

/**
 * Return the object referred to by a given log entry
 *
 * @param \stdClass|int $entry The log entry row or its ID
 *
 * @return mixed
 */
function system_log_get_object_from_log_entry($entry) {
	if (is_numeric($entry)) {
		$entry = system_log_get_log_entry($entry);
		if (!$entry) {
			return false;
		}
	}

	$class = $entry->object_class;
	$id = $entry->object_id;

	if (!class_exists($class)) {
		// failed autoload
		return false;
	}

	$getters = [
		ElggAnnotation::class => 'elgg_get_annotation_from_id',
		ElggMetadata::class => 'elgg_get_metadata_from_id',
		ElggRelationship::class => 'get_relationship',
	];

	if (isset($getters[$class]) && is_callable($getters[$class])) {
		$object = call_user_func($getters[$class], $id);
	} else if (preg_match('~^Elgg[A-Z]~', $class)) {
		$object = get_entity($id);
	} else {
		// surround with try/catch because object could be disabled
		try {
			$object = new $class($entry->object_id);

			return $object;
		} catch (Exception $e) {
		}
	}

	if (!is_object($object) || get_class($object) !== $class) {
		return false;
	}

	return $object;
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
	// PHPDI creates the shared cache if not exists already
	// PHPDI creates new insert (passing in cache), calls our function
	elgg()->dic->call(function (SystemLogInsert $insert) use ($object, $event) {
		$insert->insert($object, $event);
	});
}

/**
 * This function creates an archive copy of the system log.
 *
 * @param int $offset An offset in seconds from now to archive (useful for log rotation)
 *
 * @return bool
 */
function system_log_archive_log($offset = 0) {
	$offset = (int) $offset;
	$now = time(); // Take a snapshot of now
	$prefix = _elgg_config()->dbprefix;

	$ts = $now - $offset;

	// create table
	$query = "
		CREATE TABLE {$prefix}system_log_$now as
			SELECT * FROM {$prefix}system_log 
			WHERE time_created < $ts
	";

	if (!update_data($query)) {
		return false;
	}

	// delete
	// Don't delete on time since we are running in a concurrent environment
	if (delete_data("DELETE from {$prefix}system_log WHERE time_created < $ts") === false) {
		return false;
	}

	// alter table to engine
	if (!update_data("ALTER TABLE {$prefix}system_log_$now engine=archive")) {
		return false;
	}

	return true;
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
 * This function deletes archived copies of the system logs that are older than specified
 *
 * @param int $time_of_delete An offset in seconds from now to delete log tables
 *
 * @return bool
 */
function system_log_browser_delete_log($time_of_delete) {
	$dbprefix = elgg_get_config('dbprefix');
	$cutoff = time() - (int) $time_of_delete;

	$deleted_tables = false;
	$results = get_data("SHOW TABLES like '{$dbprefix}system_log_%'");
	if ($results) {
		foreach ($results as $result) {
			$data = (array) $result;
			$table_name = array_shift($data);
			// extract log table rotation time
			$log_time = str_replace("{$dbprefix}system_log_", '', $table_name);
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