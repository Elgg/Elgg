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
	$options = _elgg_config()->group_tool_options;
	if (!$options) {
		$options = [];
	}
	
	$options[$name] = (object) [
		'name' => $name,
		'label' => $label,
		'default_on' => $default_on,
	];
	_elgg_config()->group_tool_options = $options;
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
	$options = _elgg_config()->group_tool_options;
	if (!is_array($options)) {
		return;
	}
	
	if (!isset($options[$name])) {
		return;
	}
	
	unset($options[$name]);
	
	_elgg_config()->group_tool_options = $options;
}

/**
 * Checks if a group has a specific tool enabled.
 * Forward to the group if the tool is disabled.
 *
 * @param string $option     The group tool option to check
 * @param int    $group_guid The group that owns the page. If not set, this
 *                           will be pulled from elgg_get_page_owner_guid().
 *
 * @return void
 * @since 3.0.0
 */
function elgg_group_tool_gatekeeper($option, $group_guid = null) {
	$group_guid = $group_guid ?: elgg_get_page_owner_guid();
	
	$group = get_entity($group_guid);
	if (!$group instanceof \ElggGroup) {
		return;
	}
	
	if ($group->isToolEnabled($option)) {
		return;
	}
	
	register_error(elgg_echo('groups:tool_gatekeeper'));
	forward($group->getURL(), 'group_tool');
}

/**
 * Function to return available group tool options
 *
 * @param \ElggGroup $group optional group
 *
 * @return array
 * @since 3.0.0
 */
function elgg_get_group_tool_options(\ElggGroup $group = null) {
	
	$tool_options = elgg_get_config('group_tool_options');
	
	$hook_params = [
		'group_tool_options' => $tool_options,
		'entity' => $group,
	];
		
	return (array) elgg_trigger_plugin_hook('tool_options', 'group', $hook_params, $tool_options);
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
		/* @var \ElggGroup $container */
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
 * @codeCoverageIgnore
 */
function _elgg_groups_test($hook, $type, $value) {
	$value[] = ElggCoreGroupTest::class;
	return $value;
}

/**
 * init the groups library
 *
 * @return void
 *
 * @access private
 */
function _elgg_groups_init() {
	elgg_register_plugin_hook_handler('container_permissions_check', 'all', '_elgg_groups_container_override');
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_groups_test');
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_groups_init');
};
