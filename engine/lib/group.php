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
 */
function get_group_entity_as_row($guid) {
	global $CONFIG;

	$guid = (int)$guid;

	return get_data_row("SELECT * from {$CONFIG->dbprefix}groups_entity where guid=$guid");
}

/**
 * Create or update the entities table for a given group.
 * Call create_entity first.
 *
 * @param int    $guid        GUID
 * @param string $name        Name
 * @param string $description Description
 *
 * @return bool
 */
function create_group_entity($guid, $name, $description) {
	global $CONFIG;

	$guid = (int)$guid;
	$name = sanitise_string($name);
	$description = sanitise_string($description);

	$row = get_entity_as_row($guid);

	if ($row) {
		// Exists and you have access to it
		$exists = get_data_row("SELECT guid from {$CONFIG->dbprefix}groups_entity WHERE guid = {$guid}");
		if ($exists) {
			$query = "UPDATE {$CONFIG->dbprefix}groups_entity set"
				. " name='$name', description='$description' where guid=$guid";
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
			$query = "INSERT into {$CONFIG->dbprefix}groups_entity"
				. " (guid, name, description) values ($guid, '$name', '$description')";

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
		$msg = elgg_echo('InvalidClassException:NotValidElggStar', array($group_guid, 'ElggGroup'));
		throw new InvalidClassException($msg);
	}

	if (!($object instanceof ElggObject)) {
		$msg = elgg_echo('InvalidClassException:NotValidElggStar', array($object_guid, 'ElggObject'));
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
		$msg = elgg_echo('InvalidClassException:NotValidElggStar', array($group_guid, 'ElggGroup'));
		throw new InvalidClassException($msg);
	}

	if (!($object instanceof ElggObject)) {
		$msg = elgg_echo('InvalidClassException:NotValidElggStar', array($object_guid, 'ElggObject'));
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
		'types' => 'user',
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
 * Join a user to a group.
 *
 * @param int $group_guid The group GUID.
 * @param int $user_guid  The user GUID.
 *
 * @return bool
 */
function join_group($group_guid, $user_guid) {
	$result = add_entity_relationship($user_guid, 'member', $group_guid);

	if ($result) {
		$params = array('group' => get_entity($group_guid), 'user' => get_entity($user_guid));
		elgg_trigger_event('join', 'group', $params);
	}

	return $result;
}

/**
 * Remove a user from a group.
 *
 * @param int $group_guid The group.
 * @param int $user_guid  The user.
 *
 * @return bool
 */
function leave_group($group_guid, $user_guid) {
	// event needs to be triggered while user is still member of group to have access to group acl
	$params = array('group' => get_entity($group_guid), 'user' => get_entity($user_guid));

	elgg_trigger_event('leave', 'group', $params);
	$result = remove_entity_relationship($user_guid, 'member', $group_guid);
	return $result;
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
		'inverse_relationship' => FALSE
	);
	return elgg_get_entities_from_relationship($options);
}

/**
 * Checks access to a group.
 *
 * @param boolean $forward If set to true (default), will forward the page;
 *                         if set to false, will return true or false.
 *
 * @return true|false If $forward is set to false.
 */
function group_gatekeeper($forward = true) {
	$allowed = true;
	$url = '';

	if ($group = elgg_get_page_owner_entity()) {
		if ($group instanceof ElggGroup) {
			$url = $group->getURL();
			if (
				((!elgg_is_logged_in()) && (!$group->isPublicMembership())) ||
				((!$group->isMember(elgg_get_logged_in_user_entity()) && (!$group->isPublicMembership())))
			) {
				$allowed = false;
			}

			// Admin override
			if (elgg_is_admin_logged_in()) {
				$allowed = true;
			}
		}
	}

	if ($forward && $allowed == false) {
		register_error(elgg_echo('membershiprequired'));
		if (!forward($url, 'member')) {
			throw new SecurityException(elgg_echo('SecurityException:UnexpectedOutputInGatekeeper'));
		}
	}

	return $allowed;
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
