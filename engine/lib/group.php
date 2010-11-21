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
 * Create or update the extras table for a given group.
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
 * THIS FUNCTION IS DEPRECATED.
 *
 * Delete a group's extra data.
 *
 * @param int $guid The guid of the group
 *
 * @return bool
 * @deprecated 1.6
 */
function delete_group_entity($guid) {
	elgg_deprecated_notice("delete_group_entity has been deprecated", 1.6);

	// Always return that we have deleted one row in order to not break existing code.
	return 1;
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
 * Return an array of objects in a given container.
 *
 * @see get_entities()
 *
 * @param int    $group_guid The container (defaults to current page owner)
 * @param string $subtype    The subtype
 * @param int    $owner_guid Owner
 * @param int    $site_guid  The site
 * @param string $order_by   Order
 * @param int    $limit      Limit on number of elements to return, by default 10.
 * @param int    $offset     Where to start, by default 0.
 * @param bool   $count      Whether to return the entities or a count of them.
 *
 * @return array|false
 * @deprecated 1.8 Use elgg_get_entities() instead
 */
function get_objects_in_group($group_guid, $subtype = "", $owner_guid = 0, $site_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = FALSE) {
	elgg_deprecated_notice("get_objects_in_group was deprected in 1.8.  Use elgg_get_entities() instead", 1.8);

	global $CONFIG;

	if ($subtype === FALSE || $subtype === null || $subtype === 0) {
		return FALSE;
	}

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$container_guid = (int)$group_guid;
	if ($container_guid == 0) {
		$container_guid = elgg_get_page_owner_guid();
	}

	$where = array();

	$where[] = "e.type='object'";

	if (!empty($subtype)) {
		if (!$subtype = get_subtype_id('object', $subtype)) {
			return FALSE;
		}
		$where[] = "e.subtype=$subtype";
	}
	if ($owner_guid != "") {
		if (!is_array($owner_guid)) {
			$owner_guid = (int) $owner_guid;
			$where[] = "e.container_guid = '$owner_guid'";
		} else if (sizeof($owner_guid) > 0) {
			// Cast every element to the owner_guid array to int
			$owner_guid = array_map("sanitise_int", $owner_guid);
			$owner_guid = implode(",", $owner_guid);
			$where[] = "e.container_guid in ({$owner_guid})";
		}
	}
	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if (!$count) {
		$query = "SELECT * from {$CONFIG->dbprefix}entities e"
			. " join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where ";
	} else {
		$query = "SELECT count(e.guid) as total from {$CONFIG->dbprefix}entities e"
			. " join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where ";
	}
	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= get_access_sql_suffix('e');
	if (!$count) {
		$query .= " order by $order_by";

		// Add order and limit
		if ($limit) {
			$query .= " limit $offset, $limit";
		}

		$dt = get_data($query, "entity_row_to_elggstar");
		return $dt;
	} else {
		$total = get_data_row($query);
		return $total->total;
	}
}

/**
 * Lists entities that belong to a group.
 *
 * @param string $subtype        The arbitrary subtype of the entity
 * @param int    $owner_guid     The GUID of the owning user
 * @param int    $container_guid The GUID of the containing group
 * @param int    $limit          The number of entities to display per page (default: 10)
 * @param bool   $fullview       Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle Whether or not to allow gallery view (default: true)
 * @param bool   $pagination     Whether to display pagination (default: true)
 *
 * @return string List of parsed entities
 *
 * @see elgg_list_entities()
 * @deprecated 1.8 Use elgg_list_entities() instead
 */
function list_entities_groups($subtype = "", $owner_guid = 0, $container_guid = 0,
$limit = 10, $fullview = true, $listtypetoggle = true, $pagination = true) {
	elgg_deprecated_notice("list_entities_groups was deprecated in 1.8.  Use elgg_list_entities() instead.", 1.8);
	$offset = (int) get_input('offset');
	$count = get_objects_in_group($container_guid, $subtype, $owner_guid,
		0, "", $limit, $offset, true);
	$entities = get_objects_in_group($container_guid, $subtype, $owner_guid,
		0, "", $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit,
		$fullview, $listtypetoggle, $pagination);
}

/**
 * Get all the entities from metadata from a group.
 *
 * @param int    $group_guid     The ID of the group.
 * @param mixed  $meta_name      Metadata name
 * @param mixed  $meta_value     Metadata value
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $owner_guid     Owner guid
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       Optional ordering.
 * @param int    $site_guid      Site GUID. 0 for current, -1 for any
 * @param bool   $count          Return count instead of entities
 *
 * @return array|false
 * @deprecated 1.8 Use elgg_get_entities_from_metadata()
 */
function get_entities_from_metadata_groups($group_guid, $meta_name, $meta_value = "",
$entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0,
$order_by = "", $site_guid = 0, $count = false) {
	elgg_deprecated_notice("get_entities_from_metadata_groups was deprecated in 1.8.", 1.8);
	global $CONFIG;

	$meta_n = get_metastring_id($meta_name);
	$meta_v = get_metastring_id($meta_value);

	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$site_guid = (int) $site_guid;
	if (is_array($owner_guid)) {
		foreach ($owner_guid as $key => $guid) {
			$owner_guid[$key] = (int) $guid;
		}
	} else {
		$owner_guid = (int) $owner_guid;
	}
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$container_guid = (int)$group_guid;
	if ($container_guid == 0) {
		$container_guid = elgg_get_page_owner_guid();
	}

	$where = array();

	if ($entity_type != "") {
		$where[] = "e.type='$entity_type'";
	}
	if ($entity_subtype) {
		$where[] = "e.subtype=$entity_subtype";
	}
	if ($meta_name != "") {
		$where[] = "m.name_id='$meta_n'";
	}
	if ($meta_value != "") {
		$where[] = "m.value_id='$meta_v'";
	}
	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}
	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if (is_array($owner_guid)) {
		$where[] = "e.container_guid in (" . implode(",", $owner_guid ) . ")";
	} else if ($owner_guid > 0) {
		$where[] = "e.container_guid = {$owner_guid}";
	}

	if (!$count) {
		$query = "SELECT distinct e.* ";
	} else {
		$query = "SELECT count(e.guid) as total ";
	}

	$query .= "from {$CONFIG->dbprefix}entities e"
		. " JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid "
		. " JOIN {$CONFIG->dbprefix}objects_entity o on e.guid = o.guid where";

	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= get_access_sql_suffix("e");

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($row = get_data_row($query)) {
			return $row->total;
		}
	}
	return false;
}

/**
 * As get_entities_from_metadata_groups() but with multiple entities.
 *
 * @param int    $group_guid     The ID of the group.
 * @param array  $meta_array     Array of 'name' => 'value' pairs
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       Optional ordering.
 * @param int    $site_guid      Site GUID. 0 for current, -1 for any
 * @param bool   $count          Return count of entities instead of entities
 *
 * @return int|array List of ElggEntities, or the total number if count is set to false
 * @deprecated 1.8 Use elgg_get_entities_from_metadata()
 */
function get_entities_from_metadata_groups_multi($group_guid, $meta_array, $entity_type = "",
$entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "",
$site_guid = 0, $count = false) {
	elgg_deprecated_notice("get_entities_from_metadata_groups_multi was deprecated in 1.8.", 1.8);

	global $CONFIG;

	if (!is_array($meta_array) || sizeof($meta_array) == 0) {
		return false;
	}

	$where = array();

	$mindex = 1;
	$join = "";
	foreach ($meta_array as $meta_name => $meta_value) {
		$meta_n = get_metastring_id($meta_name);
		$meta_v = get_metastring_id($meta_value);
		$join .= " JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid"
			. " JOIN {$CONFIG->dbprefix}objects_entity o on e.guid = o.guid ";

		if ($meta_name != "") {
			$where[] = "m{$mindex}.name_id='$meta_n'";
		}

		if ($meta_value != "") {
			$where[] = "m{$mindex}.value_id='$meta_v'";
		}

		$mindex++;
	}

	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$owner_guid = (int) $owner_guid;

	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	//$access = get_access_list();

	if ($entity_type != "") {
		$where[] = "e.type = '{$entity_type}'";
	}

	if ($entity_subtype) {
		$where[] = "e.subtype = {$entity_subtype}";
	}

	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if ($owner_guid > 0) {
		$where[] = "e.owner_guid = {$owner_guid}";
	}

	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT distinct e.* ";
	}

	$query .= " from {$CONFIG->dbprefix}entities e {$join} where";
	foreach ($where as $w) {
		$query .= " $w and ";
	}
	$query .= get_access_sql_suffix("e"); // Add access controls

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
 * @param int $group_guid The group.
 * @param int $user_guid  The user.
 *
 * @return bool
 */
function join_group($group_guid, $user_guid) {
	$result = add_entity_relationship($user_guid, 'member', $group_guid);

	$param = array('group' => get_entity($group_guid), 'user' => get_entity($user_guid));
	elgg_trigger_event('join', 'group', $params);

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

	if ($group = elgg_get_page_owner()) {
		if ($group instanceof ElggGroup) {
			$url = $group->getURL();
			if (
				((!isloggedin()) && (!$group->isPublicMembership())) ||
				((!$group->isMember(get_loggedin_user()) && (!$group->isPublicMembership())))
			) {
				$allowed = false;
			}

			// Admin override
			if (isadminloggedin()) {
				$allowed = true;
			}
		}
	}

	if ($forward && $allowed == false) {
		register_error(elgg_echo('membershiprequired'));
		forward($url, 'member');
		exit;
	}

	return $allowed;
}

/**
 * Manages group tool options
 *
 * @param string  $name       Name of the group tool option
 * @param string  $label      Used for the group edit form
 * @param boolean $default_on True if this option should be active by default
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
 * Searches for a group based on a complete or partial name or description
 *
 * @param string  $criteria The partial or full name or description
 * @param int     $limit    Limit of the search.
 * @param int     $offset   Offset.
 * @param string  $order_by The order.
 * @param boolean $count    Whether to return the count of results or just the results.
 *
 * @return mixed
 * @deprecated 1.7
 */
function search_for_group($criteria, $limit = 10, $offset = 0, $order_by = "", $count = false) {
	elgg_deprecated_notice('search_for_group() was deprecated by new search plugin.', 1.7);
	global $CONFIG;

	$criteria = sanitise_string($criteria);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$order_by = sanitise_string($order_by);

	$access = get_access_sql_suffix("e");

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT e.* ";
	}
	$query .= "from {$CONFIG->dbprefix}entities e"
		. " JOIN {$CONFIG->dbprefix}groups_entity g on e.guid=g.guid where ";

	$query .= "(g.name like \"%{$criteria}%\" or g.description like \"%{$criteria}%\")";
	$query .= " and $access";

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
 * Returns a formatted list of groups suitable for injecting into search.
 *
 * @deprecated 1.7
 *
 * @param string $hook        Hook name
 * @param string $user        User
 * @param mixed  $returnvalue Previous hook's return value
 * @param string $tag         Tag to search on
 *
 * @return string
 */
function search_list_groups_by_name($hook, $user, $returnvalue, $tag) {
	elgg_deprecated_notice('search_list_groups_by_name() was deprecated by new search plugin', 1.7);
	// Change this to set the number of groups that display on the search page
	$threshold = 4;

	$object = get_input('object');

	if (!get_input('offset') && (empty($object) || $object == 'group')) {
		if ($groups = search_for_group($tag, $threshold)) {
			$countgroups = search_for_group($tag, 0, 0, "", true);

			$return = elgg_view('group/search/startblurb', array('count' => $countgroups, 'tag' => $tag));
			foreach ($groups as $group) {
				$return .= elgg_view_entity($group);
			}
			$vars = array('count' => $countgroups, 'threshold' => $threshold, 'tag' => $tag);
			$return .= elgg_view('group/search/finishblurb', $vars);
			return $return;
		}
	}
}

/**
 * Displays a list of group objects that have been searched for.
 *
 * @see elgg_view_entity_list
 *
 * @param string $tag   Search criteria
 * @param int    $limit The number of entities to display on a page
 *
 * @return string The list in a form suitable to display
 * @deprecated 1.7
 */
function list_group_search($tag, $limit = 10) {
	elgg_deprecated_notice('list_group_search() was deprecated by new search plugin.', 1.7);
	$offset = (int) get_input('offset');
	$limit = (int) $limit;
	$count = (int) search_for_group($tag, 10, 0, '', true);
	$entities = search_for_group($tag, $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, false);

}

/**
 * Performs initialisation functions for groups
 *
 * @return void
 */
function group_init() {
	// Register an entity type
	register_entity_type('group', '');
}

elgg_register_event_handler('init', 'system', 'group_init');
