<?php
/**
 * Elgg Groups.
 * Groups contain other entities, or rather act as a placeholder for other entities to
 * mark any given container as their container.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Group
 */

/**
 * Get the group entity.
 *
 * @param int $guid GUID for a group
 *
 * @return array|false
 * @access private
 */
function get_group_entity_as_row($guid) {
	global $CONFIG;

	$guid = (int)$guid;

	return get_data_row("SELECT * from {$CONFIG->dbprefix}groups_entity where guid=$guid");
}

/**
 * Adds a group tool option
 *
 * @see remove_group_tool_option().
 *
 * @param string $name       Name of the group tool option
 * @param string $label      Used for the group edit form
 * @param bool   $default_on True if this option should be active by default
 *
 * @return void
 * @since 1.5.0
 */
function add_group_tool_option($name, $label, $default_on = true) {
	global $CONFIG;

	if (!isset($CONFIG->group_tool_options)) {
		$CONFIG->group_tool_options = array();
	}

	$group_tool_option = new stdClass;

	$group_tool_option->name = $name;
	$group_tool_option->label = $label;
	$group_tool_option->default_on = $default_on;

	$CONFIG->group_tool_options[] = $group_tool_option;
}

/**
 * Removes a group tool option based on name
 *
 * @see add_group_tool_option()
 *
 * @param string $name Name of the group tool option
 *
 * @return void
 * @since 1.7.5
 */
function remove_group_tool_option($name) {
	global $CONFIG;

	if (!isset($CONFIG->group_tool_options)) {
		return;
	}

	foreach ($CONFIG->group_tool_options as $i => $option) {
		if ($option->name == $name) {
			unset($CONFIG->group_tool_options[$i]);
		}
	}
}

/**
 * Allow group members to write to the group container
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $result The value of the hook
 * @param array  $params Parameters related to the hook
 * @return bool
 * @access private
 */
function _elgg_groups_container_override($hook, $type, $result, $params) {
	$container = $params['container'];
	$user = $params['user'];
	if (elgg_instanceof($container, 'group') && $user) {
		/* @var ElggGroup $container */
		if ($container->isMember($user)) {
			return true;
		}
	}

	return $result;
}

/**
 * Runs unit tests for the group entities.
 *
 * @param string $hook  Hook name
 * @param string $type  Hook type
 * @param array  $value Array of unit test locations
 *
 * @return array
 * @access private
 */
function _elgg_groups_test($hook, $type, $value) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/ElggGroupTest.php';
	return $value;
}

/**
 * init the groups library
 * @access private
 */
function _elgg_groups_init() {
	elgg_register_plugin_hook_handler('container_permissions_check', 'all', '_elgg_groups_container_override');
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_groups_test');
}

elgg_register_event_handler('init', 'system', '_elgg_groups_init');
