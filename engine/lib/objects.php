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
 * @access private
 */
function get_object_entity_as_row($guid) {
	global $CONFIG;

	$guid = (int)$guid;
	return get_data_row("SELECT * from {$CONFIG->dbprefix}objects_entity where guid=$guid");
}


/**
 * Get the sites this object is part of
 *
 * @param int $object_guid The object's GUID
 * @param int $limit       Number of results to return
 * @param int $offset      Any indexing offset
 *
 * @return array On success, an array of ElggSites
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
 * @access private
 */
function objects_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/ElggCoreObjectTest.php";
	return $value;
}

elgg_register_event_handler('init', 'system', 'objects_init', 0);
elgg_register_plugin_hook_handler('unit_test', 'system', 'objects_test');
