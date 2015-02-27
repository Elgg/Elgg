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
 * @param int $guid The guid to retrieve
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
 * Runs unit tests for \ElggObject
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function _elgg_objects_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/ElggObjectTest.php";
	return $value;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$hooks->registerHandler('unit_test', 'system', '_elgg_objects_test');
};
