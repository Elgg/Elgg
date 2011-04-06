<?php
/**
 * Get entities with the specified access collection id.
 *
 * @deprecated 1.7. Use elgg_get_entities_from_access_id()
 *
 * @param int    $collection_id  ID of collection
 * @param string $entity_type    Type of entities
 * @param string $entity_subtype Subtype of entities
 * @param int    $owner_guid     Guid of owner
 * @param int    $limit          Limit of number of entities to return
 * @param int    $offset         Skip this many entities
 * @param string $order_by       Column to order by
 * @param int    $site_guid      The site guid
 * @param bool   $count          Return a count or entities
 *
 * @return array
 */
function get_entities_from_access_id($collection_id, $entity_type = "", $entity_subtype = "",
	$owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {
	// log deprecated warning
	elgg_deprecated_notice('get_entities_from_access_id() was deprecated by elgg_get_entities()', 1.7);

	if (!$collection_id) {
		return FALSE;
	}

	// build the options using given parameters
	$options = array();
	$options['limit'] = $limit;
	$options['offset'] = $offset;
	$options['count'] = $count;

	if ($entity_type) {
		$options['type'] = sanitise_string($entity_type);
	}

	if ($entity_subtype) {
		$options['subtype'] = $entity_subtype;
	}

	if ($site_guid) {
		$options['site_guid'] = $site_guid;
	}

	if ($order_by) {
		$options['order_by'] = sanitise_string("e.time_created, $order_by");
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($site_guid) {
		$options['site_guid'] = $site_guid;
	}

	$options['access_id'] = $collection_id;

	return elgg_get_entities_from_access_id($options);
}

/**
 * @deprecated 1.7
 */
function get_entities_from_access_collection($collection_id, $entity_type = "", $entity_subtype = "",
	$owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {

	elgg_deprecated_notice('get_entities_from_access_collection() was deprecated by elgg_get_entities()', 1.7);

	return get_entities_from_access_id($collection_id, $entity_type, $entity_subtype,
			$owner_guid, $limit, $offset, $order_by, $site_guid, $count);
}

/**
 * Get entities from annotations
 *
 * No longer used.
 *
 * @deprecated 1.7 Use elgg_get_entities_from_annotations()
 *
 * @param mixed  $entity_type    Type of entity
 * @param mixed  $entity_subtype Subtype of entity
 * @param string $name           Name of annotation
 * @param string $value          Value of annotation
 * @param int    $owner_guid     Guid of owner of annotation
 * @param int    $group_guid     Guid of group
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       SQL order by string
 * @param bool   $count          Count or return entities
 * @param int    $timelower      Lower time limit
 * @param int    $timeupper      Upper time limit
 *
 * @return unknown_type
 */
function get_entities_from_annotations($entity_type = "", $entity_subtype = "", $name = "",
$value = "", $owner_guid = 0, $group_guid = 0, $limit = 10, $offset = 0, $order_by = "asc",
$count = false, $timelower = 0, $timeupper = 0) {
	$msg = 'get_entities_from_annotations() is deprecated by elgg_get_entities_from_annotations().';
	elgg_deprecated_notice($msg, 1.7);

	$options = array();

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	$options['annotation_names'] = $name;

	if ($value) {
		$options['annotation_values'] = $value;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['annotation_owner_guids'] = $owner_guid;
		} else {
			$options['annotation_owner_guid'] = $owner_guid;
		}
	}

	if ($group_guid) {
		$options['container_guid'] = $group_guid;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'] = "maxtime $order_by";
	}

	if ($count) {
		$options['count'] = $count;
	}

	if ($timelower) {
		$options['annotation_created_time_lower'] = $timelower;
	}

	if ($timeupper) {
		$options['annotation_created_time_upper'] = $timeupper;
	}

	return elgg_get_entities_from_annotations($options);
}

/**
 * Lists entities
 *
 * @see elgg_view_entity_list
 *
 * @param string  $entity_type    Type of entity.
 * @param string  $entity_subtype Subtype of entity.
 * @param string  $name           Name of annotation.
 * @param string  $value          Value of annotation.
 * @param int     $limit          Maximum number of results to return.
 * @param int     $owner_guid     Owner.
 * @param int     $group_guid     Group container. Currently only supported if entity_type is object
 * @param boolean $asc            Whether to list in ascending or descending order (default: desc)
 * @param boolean $fullview       Whether to display the entities in full
 * @param boolean $listtypetoggle Can 'gallery' view can be displayed (default: no)
 *
 * @deprecated 1.7 Use elgg_list_entities_from_annotations()
 * @return string Formatted entity list
 */
function list_entities_from_annotations($entity_type = "", $entity_subtype = "", $name = "",
$value = "", $limit = 10, $owner_guid = 0, $group_guid = 0, $asc = false, $fullview = true,
$listtypetoggle = false) {

	$msg = 'list_entities_from_annotations is deprecated by elgg_list_entities_from_annotations';
	elgg_deprecated_notice($msg, 1.8);

	$options = array();

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	if ($name) {
		$options['annotation_names'] = $name;
	}

	if ($value) {
		$options['annotation_values'] = $value;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($owner_guid) {
		$options['annotation_owner_guid'] = $owner_guid;
	}

	if ($group_guid) {
		$options['container_guid'] = $group_guid;
	}

	if ($asc) {
		$options['order_by'] = 'maxtime desc';
	}

	if ($offset = sanitise_int(get_input('offset', null))) {
		$options['offset'] = $offset;
	}

	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;
	$options['pagination'] = $pagination;

	return elgg_list_entities_from_annotations($options);
}

/**
 * Returns all php files in a directory.
 *
 * @deprecated 1.7 Use elgg_get_file_list() instead
 *
 * @param string $directory  Directory to look in
 * @param array  $exceptions Array of extensions (with .!) to ignore
 * @param array  $list       A list files to include in the return
 *
 * @return array
 */
function get_library_files($directory, $exceptions = array(), $list = array()) {
	elgg_deprecated_notice('get_library_files() deprecated by elgg_get_file_list()', 1.7);
	return elgg_get_file_list($directory, $exceptions, $list, array('.php'));
}

/**
 * Add action tokens to URL.
 *
 * @param string $url URL
 *
 * @return string
 *
 * @deprecated 1.7 final
 */
function elgg_validate_action_url($url) {
	elgg_deprecated_notice('elgg_validate_action_url() deprecated by elgg_add_action_tokens_to_url().',
		1.7);

	return elgg_add_action_tokens_to_url($url);
}

/**
 * Does nothing.
 *
 * @deprecated 1.7
 * @return 0
 */
function test_ip() {
	elgg_deprecated_notice('test_ip() was removed because of licensing issues.', 1.7);

	return 0;
}

/**
 * Does nothing.
 *
 * @return bool
 * @deprecated 1.7
 */
function is_ip_in_array() {
	elgg_deprecated_notice('is_ip_in_array() was removed because of licensing issues.', 1.7);

	return false;
}

/**
 * Returns entities.
 *
 * @deprecated 1.7.  Use elgg_get_entities().
 *
 * @param string $type           Entity type
 * @param string $subtype        Entity subtype
 * @param int    $owner_guid     Owner GUID
 * @param string $order_by       Order by clause
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param bool   $count          Return a count or an array of entities
 * @param int    $site_guid      Site GUID
 * @param int    $container_guid Container GUID
 * @param int    $timelower      Lower time limit
 * @param int    $timeupper      Upper time limit
 *
 * @return array
 */
function get_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10,
$offset = 0, $count = false, $site_guid = 0, $container_guid = null, $timelower = 0,
$timeupper = 0) {

	elgg_deprecated_notice('get_entities() was deprecated by elgg_get_entities().', 1.7);

	// rewrite owner_guid to container_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
			$owner_guid = NULL;
		}
	}

	$options = array();
	if ($type) {
		if (is_array($type)) {
			$options['types'] = $type;
		} else {
			$options['type'] = $type;
		}
	}

	if ($subtype) {
		if (is_array($subtype)) {
			$options['subtypes'] = $subtype;
		} else {
			$options['subtype'] = $subtype;
		}
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($order_by) {
		$options['order_by'] = $order_by;
	}

	// need to pass 0 for all option
	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($count) {
		$options['count'] = $count;
	}

	if ($site_guid) {
		$options['site_guids'] = $site_guid;
	}

	if ($container_guid) {
		$options['container_guids'] = $container_guid;
	}

	if ($timeupper) {
		$options['created_time_upper'] = $timeupper;
	}

	if ($timelower) {
		$options['created_time_lower'] = $timelower;
	}

	$r = elgg_get_entities($options);
	return $r;
}

/**
 * Delete multiple entities that match a given query.
 * This function iterates through and calls delete_entity on
 * each one, this is somewhat inefficient but lets
 * the 'delete' event be called for each entity.
 *
 * @deprecated 1.7. This is a dangerous function as it defaults to deleting everything.
 *
 * @param string $type       The type of entity (eg "user", "object" etc)
 * @param string $subtype    The arbitrary subtype of the entity
 * @param int    $owner_guid The GUID of the owning user
 *
 * @return false
 */
function delete_entities($type = "", $subtype = "", $owner_guid = 0) {
	elgg_deprecated_notice('delete_entities() was deprecated because no one should use it.', 1.7);
	return false;
}

/**
 * Lists entities.
 *
 * @param int  $owner_guid     Owner GUID
 * @param int  $limit          Limit
 * @param bool $fullview       Show entity full views
 * @param bool $listtypetoggle Show list type toggle
 * @param bool $allowedtypes   A string of the allowed types
 *
 * @return string
 * @deprecated 1.7.  Use elgg_list_registered_entities().
 */
function list_registered_entities($owner_guid = 0, $limit = 10, $fullview = true,
$listtypetoggle = false, $allowedtypes = true) {

	elgg_deprecated_notice('list_registered_entities() was deprecated by elgg_list_registered_entities().', 1.7);

	$options = array();

	// don't want to send anything if not being used.
	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($allowedtypes) {
		$options['allowed_types'] = $allowedtypes;
	}

	// need to send because might be BOOL
	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;

	$options['offset'] = get_input('offset', 0);

	return elgg_list_registered_entities($options);
}

/**
 * Lists entities
 *
 * @deprecated 1.7.  Use elgg_list_entities().
 *
 * @param string $type           Entity type
 * @param string $subtype        Entity subtype
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Limit
 * @param bool   $fullview       Display entity full views?
 * @param bool   $listtypetoggle Allow switching to gallery mode?
 * @param bool   $pagination     Show pagination?
 *
 * @return string
 */
function list_entities($type= "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true,
$listtypetoggle = false, $pagination = true) {

	elgg_deprecated_notice('list_entities() was deprecated by elgg_list_entities()!', 1.7);

	$options = array();

	// rewrite owner_guid to container_guid to emulate old functionality
	if ($owner_guid) {
		$options['container_guids'] = $owner_guid;
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset = sanitise_int(get_input('offset', null))) {
		$options['offset'] = $offset;
	}

	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;
	$options['pagination'] = $pagination;

	return elgg_list_entities($options);
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
 * Return a list of entities based on the given search criteria.
 *
 * @deprecated 1.7 use elgg_get_entities_from_metadata().
 *
 * @param mixed  $meta_name      Metadat name
 * @param mixed  $meta_value     Metadata value
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       Optional ordering.
 * @param int    $site_guid      Site GUID. 0 for current, -1 for any.
 * @param bool   $count          Return a count instead of entities
 * @param bool   $case_sensitive Metadata names case sensitivity
 *
 * @return int|array A list of entities, or a count if $count is set to true
 */
function get_entities_from_metadata($meta_name, $meta_value = "", $entity_type = "",
$entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "",
$site_guid = 0, $count = FALSE, $case_sensitive = TRUE) {

	elgg_deprecated_notice('get_entities_from_metadata() was deprecated by elgg_get_entities_from_metadata()!', 1.7);

	$options = array();

	$options['metadata_names'] = $meta_name;

	if ($meta_value) {
		$options['metadata_values'] = $meta_value;
	}

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	// need to be able to pass false
	$options['metadata_case_sensitive'] = $case_sensitive;

	return elgg_get_entities_from_metadata($options);
}

/**
 * Return entities from metadata
 *
 * @deprecated 1.7.  Use elgg_get_entities_from_metadata().
 *
 * @param mixed  $meta_array          Metadata name
 * @param string $entity_type         The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype      The subtype of the entity.
 * @param int    $owner_guid          Owner GUID
 * @param int    $limit               Limit
 * @param int    $offset              Offset
 * @param string $order_by            Optional ordering.
 * @param int    $site_guid           Site GUID. 0 for current, -1 for any.
 * @param bool   $count               Return a count instead of entities
 * @param bool   $meta_array_operator Operator for metadata values
 *
 * @return int|array A list of entities, or a count if $count is set to true
 */
function get_entities_from_metadata_multi($meta_array, $entity_type = "", $entity_subtype = "",
$owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0,
$count = false, $meta_array_operator = 'and') {

	elgg_deprecated_notice('get_entities_from_metadata_multi() was deprecated by elgg_get_entities_from_metadata()!', 1.7);

	if (!is_array($meta_array) || sizeof($meta_array) == 0) {
		return false;
	}

	$options = array();

	$options['metadata_name_value_pairs'] = $meta_array;

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	$options['metadata_name_value_pairs_operator'] = $meta_array_operator;

	return elgg_get_entities_from_metadata($options);
}

/**
 * Returns a menu item for use in the children section of add_menu()
 * This is not currently used in the Elgg core.
 *
 * @param string $menu_name The name of the menu item
 * @param string $menu_url  Its URL
 *
 * @return stdClass|false Depending on success
 * @deprecated 1.7
 */
function menu_item($menu_name, $menu_url) {
	elgg_deprecated_notice('menu_item() is deprecated by add_submenu_item', 1.7);
	return make_register_object($menu_name, $menu_url);
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

/**
 * Return entities from relationships
 *
 * @deprecated 1.7 Use elgg_get_entities_from_relationship()
 *
 * @param string $relationship         The relationship type
 * @param int    $relationship_guid    The GUID of the relationship owner
 * @param bool   $inverse_relationship Invert relationship?
 * @param string $type                 Entity type
 * @param string $subtype              Entity subtype
 * @param int    $owner_guid           Entity owner GUID
 * @param string $order_by             Order by clause
 * @param int    $limit                Limit
 * @param int    $offset               Offset
 * @param bool   $count                Return a count instead of entities?
 * @param int    $site_guid            Site GUID
 *
 * @return mixed
 */
function get_entities_from_relationship($relationship, $relationship_guid,
$inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0) {

	elgg_deprecated_notice('get_entities_from_relationship() was deprecated by elgg_get_entities_from_relationship()!', 1.7);

	$options = array();

	$options['relationship'] = $relationship;
	$options['relationship_guid'] = $relationship_guid;
	$options['inverse_relationship'] = $inverse_relationship;

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_relationship($options);
}

/**
 * Searches for a site based on a complete or partial name
 * or description or url using full text searching.
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
 * @return mixed
 * @deprecated 1.7
 */
function search_for_site($criteria, $limit = 10, $offset = 0, $order_by = "", $count = false) {
	elgg_deprecated_notice('search_for_site() was deprecated by new search plugin.', 1.7);
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
	$query .= "from {$CONFIG->dbprefix}entities e
		join {$CONFIG->dbprefix}sites_entity s on e.guid=s.guid
		where match(s.name, s.description, s.url) against ('$criteria') and $access";

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
 * Searches for a user based on a complete or partial name or username.
 *
 * @param string  $criteria The partial or full name or username.
 * @param int     $limit    Limit of the search.
 * @param int     $offset   Offset.
 * @param string  $order_by The order.
 * @param boolean $count    Whether to return the count of results or just the results.
 *
 * @return mixed
 * @deprecated 1.7
 */
function search_for_user($criteria, $limit = 10, $offset = 0, $order_by = "", $count = false) {
	elgg_deprecated_notice('search_for_user() was deprecated by new search.', 1.7);
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
	$query .= "from {$CONFIG->dbprefix}entities e
		join {$CONFIG->dbprefix}users_entity u on e.guid=u.guid where ";

	$query .= "(u.name like \"%{$criteria}%\" or u.username like \"%{$criteria}%\")";
	$query .= " and $access";

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit";
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($count = get_data_row($query)) {
			return $count->total;
		}
	}
	return false;
}

/**
 * Displays a list of user objects that have been searched for.
 *
 * @see elgg_view_entity_list
 *
 * @param string $tag   Search criteria
 * @param int    $limit The number of entities to display on a page
 *
 * @return string The list in a form suitable to display
 *
 * @deprecated 1.7
 */
function list_user_search($tag, $limit = 10) {
	elgg_deprecated_notice('list_user_search() deprecated by new search', 1.7);
	$offset = (int) get_input('offset');
	$limit = (int) $limit;
	$count = (int) search_for_user($tag, 10, 0, '', true);
	$entities = search_for_user($tag, $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, false);
}

/**
 * Returns a formatted list of users suitable for injecting into search.
 *
 * @deprecated 1.7
 *
 * @param string $hook        Hook name
 * @param string $user        User?
 * @param mixed  $returnvalue Previous hook's return value
 * @param mixed  $tag         Tag to search against
 *
 * @return void
 */
function search_list_users_by_name($hook, $user, $returnvalue, $tag) {
	elgg_deprecated_notice('search_list_users_by_name() was deprecated by new search', 1.7);
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

			$vars = array('count' => $countusers, 'threshold' => $threshold, 'tag' => $tag);
			$return .= elgg_view('user/search/finishblurb', $vars);
			return $return;

		}
	}
}

/**
 * Extend a view
 *
 * @deprecated 1.7.  Use elgg_extend_view().
 *
 * @param string $view      The view to extend.
 * @param string $view_name This view is added to $view
 * @param int    $priority  The priority, from 0 to 1000,
 *                          to add at (lowest numbers displayed first)
 * @param string $viewtype  Not used
 *
 * @return void
 */
function extend_view($view, $view_name, $priority = 501, $viewtype = '') {
	elgg_deprecated_notice('extend_view() was deprecated by elgg_extend_view()!', 1.7);
	elgg_extend_view($view, $view_name, $priority, $viewtype);
}

/**
 * Get views in a dir
 *
 * @deprecated 1.7.  Use elgg_get_views().
 *
 * @param string $dir  Dir
 * @param string $base Base view
 *
 * @return array
 */
function get_views($dir, $base) {
	elgg_deprecated_notice('get_views() was deprecated by elgg_get_views()!', 1.7);
	elgg_get_views($dir, $base);
}

/**
 * Constructs and returns a register object.
 *
 * @param string $register_name  The name of the register
 * @param mixed  $register_value The value of the register
 * @param array  $children_array Optionally, an array of children
 *
 * @return false|stdClass Depending on success
 * @deprecated 1.7 Use {@link add_submenu_item()}
 */
function make_register_object($register_name, $register_value, $children_array = array()) {
	elgg_deprecated_notice('make_register_object() is deprecated by add_submenu_item()', 1.7);
	if (empty($register_name) || empty($register_value)) {
		return false;
	}

	$register = new stdClass;
	$register->name = $register_name;
	$register->value = $register_value;
	$register->children = $children_array;

	return $register;
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
 * THIS FUNCTION IS DEPRECATED.
 *
 * Delete a user's extra data.
 *
 * @todo remove
 *
 * @param int $guid User GUID
 *
 * @return 1
 */
function delete_user_entity($guid) {
	system_message(elgg_echo('deprecatedfunction', array('delete_user_entity')));

	return 1; // Always return that we have deleted one row in order to not break existing code.
}