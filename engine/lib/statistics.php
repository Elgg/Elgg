<?php
/**
 * Elgg statistics library.
 *
 * This file contains a number of functions for obtaining statistics about the running system.
 * These statistics are mainly used by the administration pages, and is also where the basic
 * views for statistics are added.
 *
 * @package Elgg.Core
 * @subpackage Statistics
 */

/**
 * Return an array reporting the number of various entities in the system.
 *
 * @param int $owner_guid Optional owner of the statistics
 *
 * @return array
 */
function get_entity_statistics($owner_guid = 0) {
	global $CONFIG;

	$entity_stats = array();
	$owner_guid = (int)$owner_guid;

	$query = "SELECT distinct e.type,s.subtype,e.subtype as subtype_id
		from {$CONFIG->dbprefix}entities e left
		join {$CONFIG->dbprefix}entity_subtypes s on e.subtype=s.id";

	$owner_query = "";
	if ($owner_guid) {
		$query .= " where owner_guid=$owner_guid";
		$owner_query = "and owner_guid=$owner_guid ";
	}

	// Get a list of major types

	$types = get_data($query);
	foreach ($types as $type) {
		// assume there are subtypes for now
		if (!is_array($entity_stats[$type->type])) {
			$entity_stats[$type->type] = array();
		}

		$query = "SELECT count(*) as count
			from {$CONFIG->dbprefix}entities where type='{$type->type}' $owner_query";

		if ($type->subtype) {
			$query .= " and subtype={$type->subtype_id}";
		}

		$subtype_cnt = get_data_row($query);

		if ($type->subtype) {
			$entity_stats[$type->type][$type->subtype] = $subtype_cnt->count;
		} else {
			$entity_stats[$type->type]['__base__'] = $subtype_cnt->count;
		}
	}

	return $entity_stats;
}

/**
 * Return the number of users registered in the system.
 *
 * @param bool $show_deactivated Count not enabled users?
 *
 * @return int
 */
function get_number_users($show_deactivated = false) {
	global $CONFIG;

	$access = "";

	if (!$show_deactivated) {
		$access = "and " . get_access_sql_suffix();
	}

	$query = "SELECT count(*) as count
		from {$CONFIG->dbprefix}entities where type='user' $access";

	$result = get_data_row($query);

	if ($result) {
		return $result->count;
	}

	return false;
}

/**
 * Return a list of how many users are currently online, rendered as a view.
 *
 * @return string
  */
function get_online_users() {
	$offset = get_input('offset', 0);
	$count = find_active_users(600, 10, $offset, true);
	$objects = find_active_users(600, 10, $offset);

	if ($objects) {
		return elgg_view_entity_list($objects, array(
			'count' => $count,
			'offset' => $offset,
			'limit' => 10
		));
	}
}

/**
 * Initialise the statistics admin page.
 *
 * @return void
 */
function statistics_init() {
	elgg_extend_view('core/settings/statistics', 'core/settings/statistics/online');
	elgg_extend_view('core/settings/statistics', 'core/settings/statistics/numentities');
}

/// Register init function
elgg_register_event_handler('init', 'system', 'statistics_init');
