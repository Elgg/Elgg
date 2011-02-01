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
		'1.7b');

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
