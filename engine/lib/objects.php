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

elgg_register_event_handler('init', 'system', 'objects_init', 0);
elgg_register_plugin_hook_handler('unit_test', 'system', 'objects_test');
