<?php
/**
 * Return a timestamp for the start of a given day (defaults today).
 *
 * @param int $day   Day
 * @param int $month Month
 * @param int $year  Year
 *
 * @return int
 * @access private
 * @deprecated 1.9
 */
function get_day_start($day = null, $month = null, $year = null) {
	elgg_deprecated_notice('get_day_start() has been deprecated', 1.9);
	return mktime(0, 0, 0, $month, $day, $year);
}

/**
 * Return a timestamp for the end of a given day (defaults today).
 *
 * @param int $day   Day
 * @param int $month Month
 * @param int $year  Year
 *
 * @return int
 * @access private
 * @deprecated 1.9
 */
function get_day_end($day = null, $month = null, $year = null) {
	elgg_deprecated_notice('get_day_end() has been deprecated', 1.9);
	return mktime(23, 59, 59, $month, $day, $year);
}

/**
 * Return the notable entities for a given time period.
 *
 * @param int     $start_time     The start time as a unix timestamp.
 * @param int     $end_time       The end time as a unix timestamp.
 * @param string  $type           The type of entity (eg "user", "object" etc)
 * @param string  $subtype        The arbitrary subtype of the entity
 * @param int     $owner_guid     The GUID of the owning user
 * @param string  $order_by       The field to order by; by default, time_created desc
 * @param int     $limit          The number of entities to return; 10 by default
 * @param int     $offset         The indexing offset, 0 by default
 * @param boolean $count          Set to true to get a count instead of entities. Defaults to false.
 * @param int     $site_guid      Site to get entities for. Default 0 = current site. -1 = any.
 * @param mixed   $container_guid Container or containers to get entities from (default: any).
 *
 * @return array|false
 * @access private
 * @deprecated 1.9
 */
function get_notable_entities($start_time, $end_time, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "asc", $limit = 10, $offset = 0, $count = false, $site_guid = 0,
$container_guid = null) {
	elgg_deprecated_notice('get_notable_entities() has been deprecated', 1.9);
	global $CONFIG;

	if ($subtype === false || $subtype === null || $subtype === 0) {
		return false;
	}

	$start_time = (int)$start_time;
	$end_time = (int)$end_time;
	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$where = array();

	if (is_array($type)) {
		$tempwhere = "";
		if (sizeof($type)) {
			foreach ($type as $typekey => $subtypearray) {
				foreach ($subtypearray as $subtypeval) {
					$typekey = sanitise_string($typekey);
					if (!empty($subtypeval)) {
						$subtypeval = (int) get_subtype_id($typekey, $subtypeval);
					} else {
						$subtypeval = 0;
					}
					if (!empty($tempwhere)) {
						$tempwhere .= " or ";
					}
					$tempwhere .= "(e.type = '{$typekey}' and e.subtype = {$subtypeval})";
				}
			}
		}
		if (!empty($tempwhere)) {
			$where[] = "({$tempwhere})";
		}
	} else {
		$type = sanitise_string($type);
		$subtype = get_subtype_id($type, $subtype);

		if ($type != "") {
			$where[] = "e.type='$type'";
		}

		if ($subtype !== "") {
			$where[] = "e.subtype=$subtype";
		}
	}

	if ($owner_guid != "") {
		if (!is_array($owner_guid)) {
			$owner_array = array($owner_guid);
			$owner_guid = (int) $owner_guid;
			$where[] = "e.owner_guid = '$owner_guid'";
		} else if (sizeof($owner_guid) > 0) {
			$owner_array = array_map('sanitise_int', $owner_guid);
			// Cast every element to the owner_guid array to int
			$owner_guid = implode(",", $owner_guid);
			$where[] = "e.owner_guid in ({$owner_guid})";
		}
		if (is_null($container_guid)) {
			$container_guid = $owner_array;
		}
	}

	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if (!is_null($container_guid)) {
		if (is_array($container_guid)) {
			foreach ($container_guid as $key => $val) {
				$container_guid[$key] = (int) $val;
			}
			$where[] = "e.container_guid in (" . implode(",", $container_guid) . ")";
		} else {
			$container_guid = (int) $container_guid;
			$where[] = "e.container_guid = {$container_guid}";
		}
	}

	// Add the calendar stuff
	$cal_join = "
		JOIN {$CONFIG->dbprefix}metadata cal_start on e.guid=cal_start.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_start_name on cal_start.name_id=cal_start_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_start_value on cal_start.value_id=cal_start_value.id

		JOIN {$CONFIG->dbprefix}metadata cal_end on e.guid=cal_end.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_end_name on cal_end.name_id=cal_end_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_end_value on cal_end.value_id=cal_end_value.id
	";
	$where[] = "cal_start_name.string='calendar_start'";
	$where[] = "cal_start_value.string>=$start_time";
	$where[] = "cal_end_name.string='calendar_end'";
	$where[] = "cal_end_value.string <= $end_time";


	if (!$count) {
		$query = "SELECT e.* from {$CONFIG->dbprefix}entities e $cal_join where ";
	} else {
		$query = "SELECT count(e.guid) as total from {$CONFIG->dbprefix}entities e $cal_join where ";
	}
	foreach ($where as $w) {
		$query .= " $w and ";
	}

	$query .= get_access_sql_suffix('e'); // Add access controls

	if (!$count) {
		$query .= " order by n.calendar_start $order_by";
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
 * Return the notable entities for a given time period based on an item of metadata.
 *
 * @param int    $start_time     The start time as a unix timestamp.
 * @param int    $end_time       The end time as a unix timestamp.
 * @param mixed  $meta_name      Metadata name
 * @param mixed  $meta_value     Metadata value
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       Optional ordering.
 * @param int    $site_guid      Site to get entities for. Default 0 = current site. -1 = any.
 * @param bool   $count          If true, returns count instead of entities. (Default: false)
 *
 * @return int|array A list of entities, or a count if $count is set to true
 * @access private
 * @deprecated 1.9
 */
function get_notable_entities_from_metadata($start_time, $end_time, $meta_name, $meta_value = "",
$entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "",
$site_guid = 0, $count = false) {
	elgg_deprecated_notice('get_notable_entities_from_metadata() has been deprecated', 1.9);

	global $CONFIG;

	$meta_n = get_metastring_id($meta_name);
	$meta_v = get_metastring_id($meta_value);

	$start_time = (int)$start_time;
	$end_time = (int)$end_time;
	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$site_guid = (int) $site_guid;
	if ((is_array($owner_guid) && (count($owner_guid)))) {
		foreach ($owner_guid as $key => $guid) {
			$owner_guid[$key] = (int) $guid;
		}
	} else {
		$owner_guid = (int) $owner_guid;
	}

	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	//$access = get_access_list();

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

	if (is_array($owner_guid)) {
		$where[] = "e.container_guid in (" . implode(",", $owner_guid) . ")";
	} else if ($owner_guid > 0) {
		$where[] = "e.container_guid = {$owner_guid}";
	}

	// Add the calendar stuff
	$cal_join = "
		JOIN {$CONFIG->dbprefix}metadata cal_start on e.guid=cal_start.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_start_name on cal_start.name_id=cal_start_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_start_value on cal_start.value_id=cal_start_value.id

		JOIN {$CONFIG->dbprefix}metadata cal_end on e.guid=cal_end.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_end_name on cal_end.name_id=cal_end_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_end_value on cal_end.value_id=cal_end_value.id
	";

	$where[] = "cal_start_name.string='calendar_start'";
	$where[] = "cal_start_value.string>=$start_time";
	$where[] = "cal_end_name.string='calendar_end'";
	$where[] = "cal_end_value.string <= $end_time";

	if (!$count) {
		$query = "SELECT distinct e.* ";
	} else {
		$query = "SELECT count(distinct e.guid) as total ";
	}

	$query .= "from {$CONFIG->dbprefix}entities e"
	. " JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid $cal_join where";

	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= get_access_sql_suffix("e");
	$query .= ' and ' . get_access_sql_suffix("m");

	if (!$count) {
		// Add order and limit
		$query .= " order by $order_by limit $offset, $limit";
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($row = get_data_row($query)) {
			return $row->total;
		}
	}

	return false;
}

/**
 * Return the notable entities for a given time period based on their relationship.
 *
 * @param int     $start_time           The start time as a unix timestamp.
 * @param int     $end_time             The end time as a unix timestamp.
 * @param string  $relationship         The relationship eg "friends_of"
 * @param int     $relationship_guid    The guid of the entity to use query
 * @param bool    $inverse_relationship Reverse the normal function of the query to say
 *                                      "give me all entities for whom $relationship_guid is a
 *                                      $relationship of"
 * @param string  $type                 Entity type
 * @param string  $subtype              Entity subtype
 * @param int     $owner_guid           Owner GUID
 * @param string  $order_by             Optional Order by
 * @param int     $limit                Limit
 * @param int     $offset               Offset
 * @param boolean $count                If true returns a count of entities (default false)
 * @param int     $site_guid            Site to get entities for. Default 0 = current site. -1 = any
 *
 * @return array|int|false An array of entities, or the number of entities, or false on failure
 * @access private
 * @deprecated 1.9
 */
function get_noteable_entities_from_relationship($start_time, $end_time, $relationship,
$relationship_guid, $inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0) {
	elgg_deprecated_notice('get_noteable_entities_from_relationship() has been deprecated', 1.9);

	global $CONFIG;

	$start_time = (int)$start_time;
	$end_time = (int)$end_time;
	$relationship = sanitise_string($relationship);
	$relationship_guid = (int)$relationship_guid;
	$inverse_relationship = (bool)$inverse_relationship;
	$type = sanitise_string($type);
	$subtype = get_subtype_id($type, $subtype);
	$owner_guid = (int)$owner_guid;
	if ($order_by == "") {
		$order_by = "time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	//$access = get_access_list();

	$where = array();

	if ($relationship != "") {
		$where[] = "r.relationship='$relationship'";
	}
	if ($relationship_guid) {
		$where[] = $inverse_relationship ?
			"r.guid_two='$relationship_guid'" : "r.guid_one='$relationship_guid'";
	}
	if ($type != "") {
		$where[] = "e.type='$type'";
	}
	if ($subtype) {
		$where[] = "e.subtype=$subtype";
	}
	if ($owner_guid != "") {
		$where[] = "e.container_guid='$owner_guid'";
	}
	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	// Add the calendar stuff
	$cal_join = "
		JOIN {$CONFIG->dbprefix}metadata cal_start on e.guid=cal_start.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_start_name on cal_start.name_id=cal_start_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_start_value on cal_start.value_id=cal_start_value.id

		JOIN {$CONFIG->dbprefix}metadata cal_end on e.guid=cal_end.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_end_name on cal_end.name_id=cal_end_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_end_value on cal_end.value_id=cal_end_value.id
	";
	$where[] = "cal_start_name.string='calendar_start'";
	$where[] = "cal_start_value.string>=$start_time";
	$where[] = "cal_end_name.string='calendar_end'";
	$where[] = "cal_end_value.string <= $end_time";

	// Select what we're joining based on the options
	$joinon = "e.guid = r.guid_one";
	if (!$inverse_relationship) {
		$joinon = "e.guid = r.guid_two";
	}

	if ($count) {
		$query = "SELECT count(distinct e.guid) as total ";
	} else {
		$query = "SELECT distinct e.* ";
	}
	$query .= " from {$CONFIG->dbprefix}entity_relationships r"
	. " JOIN {$CONFIG->dbprefix}entities e on $joinon $cal_join where ";

	foreach ($where as $w) {
		$query .= " $w and ";
	}
	// Add access controls
	$query .= get_access_sql_suffix("e");
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
 * Get all entities for today.
 *
 * @param string  $type           The type of entity (eg "user", "object" etc)
 * @param string  $subtype        The arbitrary subtype of the entity
 * @param int     $owner_guid     The GUID of the owning user
 * @param string  $order_by       The field to order by; by default, time_created desc
 * @param int     $limit          The number of entities to return; 10 by default
 * @param int     $offset         The indexing offset, 0 by default
 * @param boolean $count          If true returns a count of entities (default false)
 * @param int     $site_guid      Site to get entities for. Default 0 = current site. -1 = any
 * @param mixed   $container_guid Container(s) to get entities from (default: any).
 *
 * @return array|false
 * @access private
 * @deprecated 1.9
 */
function get_todays_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "",
$limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = null) {
	elgg_deprecated_notice('get_todays_entities() has been deprecated', 1.9);

	$day_start = get_day_start();
	$day_end = get_day_end();

	return get_notable_entities($day_start, $day_end, $type, $subtype, $owner_guid, $order_by,
		$limit, $offset, $count, $site_guid, $container_guid);
}

/**
 * Get entities for today from metadata.
 *
 * @param mixed  $meta_name      Metadata name
 * @param mixed  $meta_value     Metadata value
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       Optional ordering.
 * @param int    $site_guid      Site to get entities for. Default 0 = current site. -1 = any.
 * @param bool   $count          If true, returns count instead of entities. (Default: false)
 *
 * @return int|array A list of entities, or a count if $count is set to true
 * @access private
 * @deprecated 1.9
 */
function get_todays_entities_from_metadata($meta_name, $meta_value = "", $entity_type = "",
$entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0,
$count = false) {
	elgg_deprecated_notice('get_todays_entities_from_metadata() has been deprecated', 1.9);

	$day_start = get_day_start();
	$day_end = get_day_end();

	return get_notable_entities_from_metadata($day_start, $day_end, $meta_name, $meta_value,
		$entity_type, $entity_subtype, $owner_guid, $limit, $offset, $order_by, $site_guid, $count);
}

/**
 * Get entities for today from a relationship
 *
 * @param string  $relationship         The relationship eg "friends_of"
 * @param int     $relationship_guid    The guid of the entity to use query
 * @param bool    $inverse_relationship Reverse the normal function of the query to say
 *                                      "give me all entities for whom $relationship_guid is a
 *                                      $relationship of"
 * @param string  $type                 Entity type
 * @param string  $subtype              Entity subtype
 * @param int     $owner_guid           Owner GUID
 * @param string  $order_by             Optional Order by
 * @param int     $limit                Limit
 * @param int     $offset               Offset
 * @param boolean $count                If true returns a count of entities (default false)
 * @param int     $site_guid            Site to get entities for. Default 0 = current site. -1 = any
 *
 * @return array|int|false An array of entities, or the number of entities, or false on failure
 * @access private
 * @deprecated 1.9
 */
function get_todays_entities_from_relationship($relationship, $relationship_guid,
$inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0) {
	elgg_deprecated_notice('get_todays_entities_from_relationship() has been deprecated', 1.9);

	$day_start = get_day_start();
	$day_end = get_day_end();

	return get_notable_entities_from_relationship($day_start, $day_end, $relationship,
		$relationship_guid,	$inverse_relationship, $type, $subtype, $owner_guid, $order_by,
		$limit, $offset, $count, $site_guid);
}

/**
 * Returns a viewable list of entities for a given time period.
 *
 * @see elgg_view_entity_list
 *
 * @param int     $start_time     The start time as a unix timestamp.
 * @param int     $end_time       The end time as a unix timestamp.
 * @param string  $type           The type of entity (eg "user", "object" etc)
 * @param string  $subtype        The arbitrary subtype of the entity
 * @param int     $owner_guid     The GUID of the owning user
 * @param int     $limit          The number of entities to return; 10 by default
 * @param boolean $fullview       Whether or not to display the full view (default: true)
 * @param boolean $listtypetoggle Whether or not to allow gallery view
 * @param boolean $navigation     Display pagination? Default: true
 *
 * @return string A viewable list of entities
 * @access private
 * @deprecated 1.9
 */
function list_notable_entities($start_time, $end_time, $type= "", $subtype = "", $owner_guid = 0,
$limit = 10, $fullview = true, $listtypetoggle = false, $navigation = true) {
	elgg_deprecated_notice('list_notable_entities() has been deprecated', 1.9);

	$offset = (int) get_input('offset');
	$count = get_notable_entities($start_time, $end_time, $type, $subtype,
		$owner_guid, "", $limit, $offset, true);

	$entities = get_notable_entities($start_time, $end_time, $type, $subtype,
		$owner_guid, "", $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit,
		$fullview, $listtypetoggle, $navigation);
}

/**
 * Return a list of today's entities.
 *
 * @see list_notable_entities
 *
 * @param string  $type           The type of entity (eg "user", "object" etc)
 * @param string  $subtype        The arbitrary subtype of the entity
 * @param int     $owner_guid     The GUID of the owning user
 * @param int     $limit          The number of entities to return; 10 by default
 * @param boolean $fullview       Whether or not to display the full view (default: true)
 * @param boolean $listtypetoggle Whether or not to allow gallery view
 * @param boolean $navigation     Display pagination? Default: true
 *
 * @return string A viewable list of entities
 * @access private
 * @deprecated 1.9
 */
function list_todays_entities($type= "", $subtype = "", $owner_guid = 0, $limit = 10,
$fullview = true, $listtypetoggle = false, $navigation = true) {
	elgg_deprecated_notice('list_todays_entities() has been deprecated', 1.9);

	$day_start = get_day_start();
	$day_end = get_day_end();

	return list_notable_entities($day_start, $day_end, $type, $subtype, $owner_guid, $limit,
		$fullview, $listtypetoggle, $navigation);
}

/**
 * Unregisters an entity type and subtype as a public-facing type.
 *
 * @warning With a blank subtype, it unregisters that entity type including
 * all subtypes. This must be called after all subtypes have been registered.
 *
 * @param string $type    The type of entity (object, site, user, group)
 * @param string $subtype The subtype to register (may be blank)
 *
 * @return true|false Depending on success
 * @deprecated 1.9 Use {@link elgg_unregister_entity_type()}
 */
function unregister_entity_type($type, $subtype) {
	elgg_deprecated_notice("unregister_entity_type() was deprecated by elgg_unregister_entity_type()", 1.9);
	return elgg_unregister_entity_type($type, $subtype);
}

/**
 * Function to determine if the object trying to attach to other, has already done so
 *
 * @param int $guid_one This is the target object
 * @param int $guid_two This is the object trying to attach to $guid_one
 *
 * @return bool
 * @access private
 * @deprecated 1.9
 */
function already_attached($guid_one, $guid_two) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	if ($attached = check_entity_relationship($guid_one, "attached", $guid_two)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Function to get all objects attached to a particular object
 *
 * @param int    $guid Entity GUID
 * @param string $type The type of object to return e.g. 'file', 'friend_of' etc
 *
 * @return an array of objects
 * @access private
 * @deprecated 1.9
 */
function get_attachments($guid, $type = "") {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$options = array(
					'relationship' => 'attached',
					'relationship_guid' => $guid,
					'inverse_relationship' => false,
					'types' => $type,
					'subtypes' => '',
					'owner_guid' => 0,
					'order_by' => 'time_created desc',
					'limit' => 10,
					'offset' => 0,
					'count' => false,
					'site_guid' => 0
				);
	$attached = elgg_get_entities_from_relationship($options);
	return $attached;
}

/**
 * Function to remove a particular attachment between two objects
 *
 * @param int $guid_one This is the target object
 * @param int $guid_two This is the object to remove from $guid_one
 *
 * @return void
 * @access private
 * @deprecated 1.9
 */
function remove_attachment($guid_one, $guid_two) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	if (already_attached($guid_one, $guid_two)) {
		remove_entity_relationship($guid_one, "attached", $guid_two);
	}
}

/**
 * Function to start the process of attaching one object to another
 *
 * @param int $guid_one This is the target object
 * @param int $guid_two This is the object trying to attach to $guid_one
 *
 * @return true|void
 * @access private
 * @deprecated 1.9
 */
function make_attachment($guid_one, $guid_two) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	if (!(already_attached($guid_one, $guid_two))) {
		if (add_entity_relationship($guid_one, "attached", $guid_two)) {
			return true;
		}
	}
}

/**
 * Utility function used by import_entity_plugin_hook() to
 * process an ODDEntity into an unsaved ElggEntity.
 *
 * @param ODDEntity $element The OpenDD element
 *
 * @return ElggEntity the unsaved entity which should be populated by items.
 * @todo Remove this.
 * @access private
 */
function oddentity_to_elggentity(ODDEntity $element) {
	$class = $element->getAttribute('class');
	$subclass = $element->getAttribute('subclass');

	// See if we already have imported this uuid
	$tmp = get_entity_from_uuid($element->getAttribute('uuid'));

	if (!$tmp) {
		// Construct new class with owner from session
		$classname = get_subtype_class($class, $subclass);
		if ($classname != "") {
			if (class_exists($classname)) {
				$tmp = new $classname();

				if (!($tmp instanceof ElggEntity)) {
					$msg = elgg_echo('ClassException:ClassnameNotClass', array($classname, get_class()));
					throw new ClassException($msg);
				}
			} else {
				error_log(elgg_echo('ClassNotFoundException:MissingClass', array($classname)));
			}
		} else {
			switch ($class) {
				case 'object' :
					$tmp = new ElggObject($row);
					break;
				case 'user' :
					$tmp = new ElggUser($row);
					break;
				case 'group' :
					$tmp = new ElggGroup($row);
					break;
				case 'site' :
					$tmp = new ElggSite($row);
					break;
				default:
					$msg = elgg_echo('InstallationException:TypeNotSupported', array($class));
					throw new InstallationException($msg);
			}
		}
	}

	if ($tmp) {
		if (!$tmp->import($element)) {
			$msg = elgg_echo('ImportException:ImportFailed', array($element->getAttribute('uuid')));
			throw new ImportException($msg);
		}

		return $tmp;
	}

	return NULL;
}

/**
 * Import an entity.
 *
 * This function checks the passed XML doc (as array) to see if it is
 * a user, if so it constructs a new elgg user and returns "true"
 * to inform the importer that it's been handled.
 *
 * @param string $hook        import
 * @param string $entity_type all
 * @param mixed  $returnvalue Value from previous hook
 * @param mixed  $params      Array of params
 *
 * @return mixed
 * @elgg_plugin_hook_handler import all
 * @todo document
 * @access private
 */
function import_entity_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = NULL;

	if ($element instanceof ODDEntity) {
		$tmp = oddentity_to_elggentity($element);

		if ($tmp) {
			// Make sure its saved
			if (!$tmp->save()) {
				$msg = elgg_echo('ImportException:ProblemSaving', array($element->getAttribute('uuid')));
				throw new ImportException($msg);
			}

			// Belts and braces
			if (!$tmp->guid) {
				throw new ImportException(elgg_echo('ImportException:NoGUID'));
			}

			// We have saved, so now tag
			add_uuid_to_guid($tmp->guid, $element->getAttribute('uuid'));

			return $tmp;
		}
	}
}

/**
 * Utility function used by import_extender_plugin_hook() to process
 * an ODDMetaData and add it to an entity. This function does not
 * hit ->save() on the entity (this lets you construct in memory)
 *
 * @param ElggEntity  $entity  The entity to add the data to.
 * @param ODDMetaData $element The OpenDD element
 *
 * @return bool
 * @access private
 */
function oddmetadata_to_elggextender(ElggEntity $entity, ODDMetaData $element) {
	// Get the type of extender (metadata, type, attribute etc)
	$type = $element->getAttribute('type');
	$attr_name = $element->getAttribute('name');
	$attr_val = $element->getBody();

	switch ($type) {
		// Ignore volatile items
		case 'volatile' :
			break;
		case 'annotation' :
			$entity->annotate($attr_name, $attr_val);
			break;
		case 'metadata' :
			$entity->setMetaData($attr_name, $attr_val, "", true);
			break;
		default : // Anything else assume attribute
			$entity->set($attr_name, $attr_val);
	}

	// Set time if appropriate
	$attr_time = $element->getAttribute('published');
	if ($attr_time) {
		$entity->set('time_updated', $attr_time);
	}

	return true;
}

/**
 *  Handler called by trigger_plugin_hook on the "import" event.
 *
 * @param string $hook        volatile
 * @param string $entity_type metadata
 * @param string $returnvalue Return value from previous hook
 * @param array  $params      The parameters
 *
 * @return null
 * @elgg_plugin_hook_handler volatile metadata
 * @todo investigate more.
 * @access private
 */
function import_extender_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = NULL;

	if ($element instanceof ODDMetaData) {
		// Recall entity
		$entity_uuid = $element->getAttribute('entity_uuid');
		$entity = get_entity_from_uuid($entity_uuid);
		if (!$entity) {
			throw new ImportException(elgg_echo('ImportException:GUIDNotFound', array($entity_uuid)));
		}

		oddmetadata_to_elggextender($entity, $element);

		// Save
		if (!$entity->save()) {
			$attr_name = $element->getAttribute('name');
			$msg = elgg_echo('ImportException:ProblemUpdatingMeta', array($attr_name, $entity_uuid));
			throw new ImportException($msg);
		}

		return true;
	}
}

/**
 * Attempt to construct an ODD object out of a XmlElement or sub-elements.
 *
 * @param XmlElement $element The element(s)
 *
 * @return mixed An ODD object if the element can be handled, or false.
 * @access private
 */
function ODD_factory (XmlElement $element) {
	$name = $element->name;
	$odd = false;

	switch ($name) {
		case 'entity' :
			$odd = new ODDEntity("", "", "");
			break;
		case 'metadata' :
			$odd = new ODDMetaData("", "", "", "");
			break;
		case 'relationship' :
			$odd = new ODDRelationship("", "", "");
			break;
	}

	// Now populate values
	if ($odd) {
		// Attributes
		foreach ($element->attributes as $k => $v) {
			$odd->setAttribute($k, $v);
		}

		// Body
		$body = $element->content;
		$a = stripos($body, "<![CDATA");
		$b = strripos($body, "]]>");
		if (($body) && ($a !== false) && ($b !== false)) {
			$body = substr($body, $a + 8, $b - ($a + 8));
		}

		$odd->setBody($body);
	}

	return $odd;
}

/**
 * Import an ODD document.
 *
 * @param string $xml The XML ODD.
 *
 * @return ODDDocument
 * @access private
 */
function ODD_Import($xml) {
	// Parse XML to an array
	$elements = xml_to_object($xml);

	// Sanity check 1, was this actually XML?
	if ((!$elements) || (!$elements->children)) {
		return false;
	}

	// Create ODDDocument
	$document = new ODDDocument();

	// Itterate through array of elements and construct ODD document
	$cnt = 0;

	foreach ($elements->children as $child) {
		$odd = ODD_factory($child);

		if ($odd) {
			$document->addElement($odd);
			$cnt++;
		}
	}

	// Check that we actually found something
	if ($cnt == 0) {
		return false;
	}

	return $document;
}

/**
 * Export an ODD Document.
 *
 * @param ODDDocument $document The Document.
 *
 * @return string
 * @access private
 */
function ODD_Export(ODDDocument $document) {
	return "$document";
}

/**
 * Handler called by trigger_plugin_hook on the "import" event.
 *
 * @param string $hook        import
 * @param string $entity_type all
 * @param mixed  $returnvalue Value from previous hook
 * @param mixed  $params      Array of params
 *
 * @return mixed
 * @access private
 */
function import_relationship_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = NULL;

	if ($element instanceof ODDRelationship) {
		$tmp = new ElggRelationship();
		$tmp->import($element);

		return $tmp;
	}
}

/**
 * This function processes an element, passing elements to the plugin stack to see if someone will
 * process it.
 *
 * If nobody processes the top level element, the sub level elements are processed.
 *
 * @param ODD $odd The odd element to process
 *
 * @return bool
 * @access private
 */
function _process_element(ODD $odd) {
	global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;

	// See if anyone handles this element, return true if it is.
	if ($odd) {
		$handled = elgg_trigger_plugin_hook("import", "all", array("element" => $odd), $to_be_serialised);
	}

	// If not, then see if any of its sub elements are handled
	if ($handled) {
		// Increment validation counter
		$IMPORTED_OBJECT_COUNTER ++;
		// Return the constructed object
		$IMPORTED_DATA[] = $handled;

		return true;
	}

	return false;
}

/**
 * Import an XML serialisation of an object.
 * This will make a best attempt at importing a given xml doc.
 *
 * @param string $xml XML string
 *
 * @return bool
 * @throws Exception if there was a problem importing the data.
 * @access private
 */
function import($xml) {
	global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;

	$IMPORTED_DATA = array();
	$IMPORTED_OBJECT_COUNTER = 0;

	$document = ODD_Import($xml);
	if (!$document) {
		throw new ImportException(elgg_echo('ImportException:NoODDElements'));
	}

	foreach ($document as $element) {
		_process_element($element);
	}

	if ($IMPORTED_OBJECT_COUNTER != count($IMPORTED_DATA)) {
		throw new ImportException(elgg_echo('ImportException:NotAllImported'));
	}

	return true;
}

/**
 * Export a GUID.
 *
 * This function exports a GUID and all information related to it in an XML format.
 *
 * This function makes use of the "serialise" plugin hook, which is passed an array to which plugins
 * should add data to be serialised to.
 *
 * @param int $guid The GUID.
 *
 * @return xml
 * @see ElggEntity for an example of its usage.
 * @access private
 */
function export($guid) {
	$odd = new ODDDocument(exportAsArray($guid));

	return ODD_Export($odd);
}

/**
 * Register the OpenDD import action
 *
 * @return void
 * @access private
 */
function export_init() {
	global $CONFIG;

	elgg_register_action("import/opendd");
}

// Register a startup event
elgg_register_event_handler('init', 'system', 'export_init', 100);

// Register the import hook
elgg_register_plugin_hook_handler("import", "all", "import_entity_plugin_hook", 0);

// Register the hook
elgg_register_plugin_hook_handler("import", "all", "import_extender_plugin_hook", 2);

// Register the import hook
elgg_register_plugin_hook_handler("import", "all", "import_relationship_plugin_hook", 3);

$IMPORTED_DATA = array();
$IMPORTED_OBJECT_COUNTER = 0;