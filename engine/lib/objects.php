<?php
/**
 * Elgg objects
 * Functions to manage multiple or single objects in an Elgg install
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Return the object specific details of a object by a row.
 *
 * @param int $guid The guid to retreive
 *
 * @return bool
 */
function get_object_entity_as_row($guid) {
	global $CONFIG;

	$guid = (int)$guid;
	return get_data_row("SELECT * from {$CONFIG->dbprefix}objects_entity where guid=$guid");
}

/**
 * Create or update the extras table for a given object.
 * Call create_entity first.
 *
 * @param int    $guid        The guid of the entity you're creating (as obtained by create_entity)
 * @param string $title       The title of the object
 * @param string $description The object's description
 *
 * @return bool
 */
function create_object_entity($guid, $title, $description) {
	global $CONFIG;

	$guid = (int)$guid;
	$title = sanitise_string($title);
	$description = sanitise_string($description);

	$row = get_entity_as_row($guid);

	if ($row) {
		// Core entities row exists and we have access to it
		$query = "SELECT guid from {$CONFIG->dbprefix}objects_entity where guid = {$guid}";
		if ($exists = get_data_row($query)) {
			$query = "UPDATE {$CONFIG->dbprefix}objects_entity
				set title='$title', description='$description' where guid=$guid";

			$result = update_data($query);
			if ($result != false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				if (elgg_trigger_event('update', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
				}
			}
		} else {
			// Update failed, attempt an insert.
			$query = "INSERT into {$CONFIG->dbprefix}objects_entity
				(guid, title, description) values ($guid, '$title','$description')";

			$result = insert_data($query);
			if ($result !== false) {
				$entity = get_entity($guid);
				if (elgg_trigger_event('create', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
				}
			}
		}
	}

	return false;
}

/**
 * THIS FUNCTION IS DEPRECATED.
 *
 * Delete a object's extra data.
 *
 * @todo - this should be removed - was deprecated in 1.5 or earlier
 *
 * @param int $guid GUID
 *
 * @return 1
 */
function delete_object_entity($guid) {
	system_message(elgg_echo('deprecatedfunction', array('delete_user_entity')));

	return 1; // Always return that we have deleted one row in order to not break existing code.
}

/**
 * Searches for an object based on a complete or partial title
 * or description using full text searching.
 *
 * IMPORTANT NOTE: With MySQL's default setup:
 * 1) $criteria must be 4 or more characters long
 * 2) If $criteria matches greater than 50% of results NO RESULTS ARE RETURNED!
 *
 * @param string  $criteria The partial or full name or username.
 * @param int     $limit    Limit of the search.
 * @param int     $offset   Offset.
 * @param string  $order_by The order.
 * @param boolean $count    Whether to return the count of results or just the results.
 *
 * @return int|false
 * @deprecated 1.7
 */
function search_for_object($criteria, $limit = 10, $offset = 0, $order_by = "", $count = false) {
	elgg_deprecated_notice('search_for_object() was deprecated by new search plugin.', 1.7);
	global $CONFIG;

	$criteria = sanitise_string($criteria);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$order_by = sanitise_string($order_by);
	$container_guid = (int)$container_guid;

	$access = get_access_sql_suffix("e");

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT e.* ";
	}
	$query .= "from {$CONFIG->dbprefix}entities e
		join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid
		where match(o.title,o.description) against ('$criteria') and $access";

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($count = get_data_row($query)) {
			return $count->total;
		}
	}
	return false;
}

/**
 * Get the sites this object is part of
 *
 * @param int $object_guid The object's GUID
 * @param int $limit       Number of results to return
 * @param int $offset      Any indexing offset
 *
 * @return false|array On success, an array of ElggSites
 */
function get_object_sites($object_guid, $limit = 10, $offset = 0) {
	$object_guid = (int)$object_guid;
	$limit = (int)$limit;
	$offset = (int)$offset;

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member_of_site',
		'relationship_guid' => $object_guid,
		'types' => 'site',
		'limit' => $limit,
		'offset' => $offset
	));
}

/**
 * Runs unit tests for ElggObject
 *
 * @param sting  $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 */
function objects_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/objects/objects.php";
	return $value;
}


/**
 * Returns a formatted list of objects suitable for injecting into search.
 *
 * @deprecated 1.7
 *
 * @param sting  $hook        Hook
 * @param string $user        user
 * @param mixed  $returnvalue Previous return value
 * @param mixed  $tag         Search term
 *
 * @return array
 */
function search_list_objects_by_name($hook, $user, $returnvalue, $tag) {
	elgg_deprecated_notice('search_list_objects_by_name was deprecated by new search plugin.', 1.7);

	// Change this to set the number of users that display on the search page
	$threshold = 4;

	$object = get_input('object');

	if (!get_input('offset') && (empty($object) || $object == 'user')) {
		if ($users = search_for_user($tag, $threshold)) {
			$countusers = search_for_user($tag, 0, 0, "", true);

			$return = elgg_view('user/search/startblurb', array('count' => $countusers, 'tag' => $tag));
			foreach ($users as $user) {
				$return .= elgg_view_entity($user);
			}
			$return .= elgg_view('user/search/finishblurb',
				array('count' => $countusers, 'threshold' => $threshold, 'tag' => $tag));

			return $return;

		}
	}
}

elgg_register_event_handler('init', 'system', 'objects_init', 0);
elgg_register_plugin_hook_handler('unit_test', 'system', 'objects_test');