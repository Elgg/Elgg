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
 * Add an object to the given group.
 *
 * @param int $group_guid  The group to add the object to.
 * @param int $object_guid The guid of the elgg object (must be ElggObject or a child thereof)
 *
 * @return bool
 * @throws InvalidClassException
 */
function add_object_to_group($group_guid, $object_guid) {
	$group_guid = (int)$group_guid;
	$object_guid = (int)$object_guid;

	$group = get_entity($group_guid);
	$object = get_entity($object_guid);

	if ((!$group) || (!$object)) {
		return false;
	}

	if (!($group instanceof ElggGroup)) {
		$msg = "GUID:" . $group_guid . " is not a valid " . 'ElggGroup';
		throw new InvalidClassException($msg);
	}

	if (!($object instanceof ElggObject)) {
		$msg = "GUID:" . $object_guid . " is not a valid " . 'ElggObject';
		throw new InvalidClassException($msg);
	}

	$object->container_guid = $group_guid;
	return $object->save();
}

/**
 * Remove an object from the given group.
 *
 * @param int $group_guid  The group to remove the object from
 * @param int $object_guid The object to remove
 *
 * @return bool
 * @throws InvalidClassException
 */
function remove_object_from_group($group_guid, $object_guid) {
	$group_guid = (int)$group_guid;
	$object_guid = (int)$object_guid;

	$group = get_entity($group_guid);
	$object = get_entity($object_guid);

	if ((!$group) || (!$object)) {
		return false;
	}

	if (!($group instanceof ElggGroup)) {
		$msg = "GUID:" . $group_guid . " is not a valid " . 'ElggGroup';
		throw new InvalidClassException($msg);
	}

	if (!($object instanceof ElggObject)) {
		$msg = "GUID:" . $object_guid . " is not a valid " . 'ElggObject';
		throw new InvalidClassException($msg);
	}

	$object->container_guid = $object->owner_guid;
	return $object->save();
}

/**
 * Return a list of this group's members.
 *
 * @param int  $group_guid The ID of the container/group.
 * @param int  $limit      The limit
 * @param int  $offset     The offset
 * @param int  $site_guid  The site
 * @param bool $count      Return the users (false) or the count of them (true)
 *
 * @return mixed
 */
function get_group_members($group_guid, $limit = 10, $offset = 0, $site_guid = 0, $count = false) {

	// in 1.7 0 means "not set."  rewrite to make sense.
	if (!$site_guid) {
		$site_guid = ELGG_ENTITIES_ANY_VALUE;
	}

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member',
		'relationship_guid' => $group_guid,
		'inverse_relationship' => TRUE,
		'type' => 'user',
		'limit' => $limit,
		'offset' => $offset,
		'count' => $count,
		'site_guid' => $site_guid
	));
}

/**
 * Return whether a given user is a member of the group or not.
 *
 * @param int $group_guid The group ID
 * @param int $user_guid  The user guid
 *
 * @return bool
 */
function is_group_member($group_guid, $user_guid) {
	$object = check_entity_relationship($user_guid, 'member', $group_guid);
	if ($object) {
		return true;
	} else {
		return false;
	}
}


/**
 * Return all groups a user is a member of.
 *
 * @param int $user_guid GUID of user
 *
 * @return array|false
 */
function get_users_membership($user_guid) {
	$options = array(
		'relationship' => 'member',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => false,
		'limit' => false,
	);
	return elgg_get_entities_from_relationship($options);
}

/**
 * May the current user access item(s) on this page? If the page owner is a group,
 * membership, visibility, and logged in status are taken into account.
 *
 * @param bool $forward         If set to true (default), will forward the page;
 *                              if set to false, will return true or false.
 *
 * @param int  $page_owner_guid The current page owner guid. If not set, this
 *                              will be pulled from elgg_get_page_owner_guid().
 *
 * @return bool Will return if $forward is set to false.
 */
function group_gatekeeper($forward = true, $page_owner_guid = null) {
	if (null === $page_owner_guid) {
		$page_owner_guid = elgg_get_page_owner_guid();
	}

	if (!$page_owner_guid) {
		return true;
	}
	$visibility = Elgg_GroupItemVisibility::factory($page_owner_guid);

	if (!$visibility->shouldHideItems) {
		return true;
	}
	if ($forward) {
		// only forward to group if user can see it
		$group = get_entity($page_owner_guid);
		$forward_url = $group ? $group->getURL() : '';

		if (!elgg_is_logged_in()) {
			_elgg_services()->session->set('last_forward_from', current_page_url());
			$forward_reason = 'login';
		} else {
			$forward_reason = 'member';
		}

		$msg_keys = array(
			'non_member' => 'membershiprequired',
			'logged_out' => 'loggedinrequired',
			'no_access' => 'noaccess',
		);
		register_error(elgg_echo($msg_keys[$visibility->reasonHidden]));
		forward($forward_url, $forward_reason);
	}

	return false;
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
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreGroupTest.php';
	return $value;
}

elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_groups_test');
