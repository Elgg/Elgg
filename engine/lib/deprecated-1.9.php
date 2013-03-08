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
 * @todo this function also accepts an array(type => subtypes) for 3rd arg. Should we document this?
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
 * Returns the URL for an entity.
 *
 * @tip Can be overridden with {@link register_entity_url_handler()}.
 *
 * @param int $entity_guid The GUID of the entity
 *
 * @return string The URL of the entity
 * @see register_entity_url_handler()
 * @deprecated 1.9 Use ElggEntity::getURL()
 */
function get_entity_url($entity_guid) {
	elgg_deprecated_notice('get_entity_url has been deprecated in favor of ElggEntity::getURL', '1.9');
	if ($entity = get_entity($entity_guid)) {
		return $entity->getURL();
	}

	return false;
}

/**
 * Delete an entity.
 *
 * Removes an entity and its metadata, annotations, relationships, river entries,
 * and private data.
 *
 * Optionally can remove entities contained and owned by $guid.
 *
 * @warning If deleting recursively, this bypasses ownership of items contained by
 * the entity.  That means that if the container_guid = $guid, the item will be deleted
 * regardless of who owns it.
 *
 * @param int  $guid      The guid of the entity to delete
 * @param bool $recursive If true (default) then all entities which are
 *                        owned or contained by $guid will also be deleted.
 *
 * @return bool
 * @access private
 * @deprecated 1.9 Use ElggEntity::delete() instead.
 */
function delete_entity($guid, $recursive = true) {
	elgg_deprecated_notice('delete_entity has been deprecated in favor of ElggEntity::delete', '1.9');
	$guid = (int)$guid;
	if ($entity = get_entity($guid)) {
		return $entity->delete($recursive);
	}
	return false;
}

/**
 * Enable an entity.
 *
 * @warning In order to enable an entity using ElggEntity::enable(),
 * you must first use {@link access_show_hidden_entities()}.
 *
 * @param int  $guid      GUID of entity to enable
 * @param bool $recursive Recursively enable all entities disabled with the entity?
 *
 * @return bool
 * @deprecated 1.9 Use ElggEntity::enable()
 */
function enable_entity($guid, $recursive = true) {
	elgg_deprecated_notice('enable_entity has been deprecated in favor of ElggEntity::enable', '1.9');
	
	$guid = (int)$guid;

	// Override access only visible entities
	$old_access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);

	$result = false;
	if ($entity = get_entity($guid)) {
		$result = $entity->enable($recursive);
	}

	access_show_hidden_entities($old_access_status);
	return $result;
}

/**
 * Returns if $user_guid can edit the metadata on $entity_guid.
 *
 * @tip Can be overridden by by registering for the permissions_check:metadata
 * plugin hook.
 *
 * @warning If a $user_guid isn't specified, the currently logged in user is used.
 *
 * @param int          $entity_guid The GUID of the entity
 * @param int          $user_guid   The GUID of the user
 * @param ElggMetadata $metadata    The metadata to specifically check (if any; default null)
 *
 * @return bool Whether the user can edit metadata on the entity.
 * @deprecated 1.9 Use ElggEntity::canEditMetadata
 */
function can_edit_entity_metadata($entity_guid, $user_guid = 0, $metadata = null) {
	elgg_deprecated_notice('can_edit_entity_metadata has been deprecated in favor of ElggEntity::canEditMetadata', '1.9');
	if ($entity = get_entity($entity_guid)) {
		return $entity->canEditMetadata($metadata, $user_guid);
	} else {
		return false;
	}
}

/**
 * Disable an entity.
 *
 * Disabled entities do not show up in list or elgg_get_entities()
 * calls, but still exist in the database.
 *
 * Entities are disabled by setting disabled = yes in the
 * entities table.
 *
 * You can ignore the disabled field by using {@link access_show_hidden_entities()}.
 *
 * @param int    $guid      The guid
 * @param string $reason    Optional reason
 * @param bool   $recursive Recursively disable all entities owned or contained by $guid?
 *
 * @return bool
 * @see access_show_hidden_entities()
 * @link http://docs.elgg.org/Entities
 * @access private
 * @deprecated 1.9 Use ElggEntity::disable instead.
 */
function disable_entity($guid, $reason = "", $recursive = true) {
	elgg_deprecated_notice('disable_entity was deprecated in favor of ElggEntity::disable', '1.9');
	
	if ($entity = get_entity($guid)) {
		return $entity->disable($reason, $recursive);
	}
	
	return false;
}

/**
 * Returns if $user_guid is able to edit $entity_guid.
 *
 * @tip Can be overridden by registering for the permissions_check plugin hook.
 *
 * @warning If a $user_guid is not passed it will default to the logged in user.
 *
 * @param int $entity_guid The GUID of the entity
 * @param int $user_guid   The GUID of the user
 *
 * @return bool
 * @link http://docs.elgg.org/Entities/AccessControl
 * @deprecated 1.9 Use ElggEntity::canEdit instead
 */
function can_edit_entity($entity_guid, $user_guid = 0) {
	elgg_deprecated_notice('can_edit_entity was deprecated in favor of ElggEntity::canEdit', '1.9');
	if ($entity = get_entity($entity_guid)) {
		return $entity->canEdit($user_guid);
	}
	
	return false;
}

/**
 * Join a user to a group.
 *
 * @param int $group_guid The group GUID.
 * @param int $user_guid  The user GUID.
 *
 * @return bool
 * @deprecated 1.9 Use ElggGroup::join instead.
 */
function join_group($group_guid, $user_guid) {
	elgg_deprecated_notice('join_group was deprecated in favor of ElggGroup::join', '1.9');
	
	$group = get_entity($group_guid);
	$user = get_entity($user_guid);
	
	if ($group instanceof ElggGroup && $user instanceof ElggUser) {
		return $group->join($user);
	}
	
	return false;
}

/**
 * Remove a user from a group.
 *
 * @param int $group_guid The group.
 * @param int $user_guid  The user.
 *
 * @return bool Whether the user was removed from the group.
 * @deprecated 1.9 Use ElggGroup::leave()
 */
function leave_group($group_guid, $user_guid) {
	elgg_deprecated_notice('leave_group was deprecated in favor of ElggGroup::leave', '1.9');
	$group = get_entity($group_guid);
	$user = get_entity($user_guid);
	
	if ($group instanceof ElggGroup && $user instanceof ElggUser) {
		return $group->leave($user);	
	}

	return false;
}

/**
 * Create paragraphs from text with line spacing
 *
 * @param string $string The string
 * @return string
 * @deprecated 1.9 Use elgg_autop instead
 **/
function autop($string) {
	elgg_deprecated_notice('autop has been deprecated in favor of elgg_autop', '1.9');
	return elgg_autop($string);
}

/**
 * Register a function as a web service method
 * 
 * @deprecated 1.9 Enable the web services plugin and use elgg_ws_expose_function().
 */
function expose_function($method, $function, array $parameters = NULL, $description = "",
		$call_method = "GET", $require_api_auth = false, $require_user_auth = false) {
	elgg_deprecated_notice("expose_function() deprecated for the function elgg_ws_expose_function() in web_services plugin", 1.9);
	if (!elgg_admin_notice_exists("elgg:ws:1.9")) {
		elgg_add_admin_notice("elgg:ws:1.9", "The web services are now a plugin in Elgg 1.9.
			You must enable this plugin and update your web services to use elgg_ws_expose_function().");
	}

	if (function_exists("elgg_ws_expose_function")) {
		return elgg_ws_expose_function($method, $function, $parameters, $description, $call_method, $require_api_auth, $require_user_auth);
	}
}

/**
 * Unregister a web services method
 *
 * @param string $method The api name that was exposed
 * @return void
 * @deprecated 1.9 Enable the web services plugin and use elgg_ws_unexpose_function().
 */
function unexpose_function($method) {
	elgg_deprecated_notice("unexpose_function() deprecated for the function elgg_ws_unexpose_function() in web_services plugin", 1.9);
	if (function_exists("elgg_ws_unexpose_function")) {
		return elgg_ws_unexpose_function($method);
	}
}

/**
 * Registers a web services handler
 *
 * @param string $handler  Web services type
 * @param string $function Your function name
 * @return bool Depending on success
 * @deprecated 1.9 Enable the web services plugin and use elgg_ws_register_service_handler().
 */
function register_service_handler($handler, $function) {
	elgg_deprecated_notice("register_service_handler() deprecated for the function elgg_ws_register_service_handler() in web_services plugin", 1.9);
	if (function_exists("elgg_ws_register_service_handler")) {
		return elgg_ws_register_service_handler($handler, $function);
	}
}

/**
 * Remove a web service
 * To replace a web service handler, register the desired handler over the old on
 * with register_service_handler().
 *
 * @param string $handler web services type
 * @return void
 * @deprecated 1.9 Enable the web services plugin and use elgg_ws_unregister_service_handler().
 */
function unregister_service_handler($handler) {
	elgg_deprecated_notice("unregister_service_handler() deprecated for the function elgg_ws_unregister_service_handler() in web_services plugin", 1.9);
	if (function_exists("elgg_ws_unregister_service_handler")) {
		return elgg_ws_unregister_service_handler($handler);
	}
}

/**
 * Create or update the entities table for a given site.
 * Call create_entity first.
 *
 * @param int    $guid        Site GUID
 * @param string $name        Site name
 * @param string $description Site Description
 * @param string $url         URL of the site
 *
 * @return bool
 * @access private
 * @deprecated 1.9 Use ElggSite constructor
 */
function create_site_entity($guid, $name, $description, $url) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use ElggSite constructor', 1.9);
	global $CONFIG;

	$guid = (int)$guid;
	$name = sanitise_string($name);
	$description = sanitise_string($description);
	$url = sanitise_string($url);

	$row = get_entity_as_row($guid);

	if ($row) {
		// Exists and you have access to it
		$query = "SELECT guid from {$CONFIG->dbprefix}sites_entity where guid = {$guid}";
		if ($exists = get_data_row($query)) {
			$query = "UPDATE {$CONFIG->dbprefix}sites_entity
				set name='$name', description='$description', url='$url' where guid=$guid";
			$result = update_data($query);

			if ($result != false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				if (elgg_trigger_event('update', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
					//delete_entity($guid);
				}
			}
		} else {
			// Update failed, attempt an insert.
			$query = "INSERT into {$CONFIG->dbprefix}sites_entity
				(guid, name, description, url) values ($guid, '$name', '$description', '$url')";
			$result = insert_data($query);

			if ($result !== false) {
				$entity = get_entity($guid);
				if (elgg_trigger_event('create', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
					//delete_entity($guid);
				}
			}
		}
	}

	return false;
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
 * @access private
 * @deprecated 1.9 Use ElggGroup constructor
 */
function create_group_entity($guid, $name, $description) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use ElggGroup constructor', 1.9);
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
 * Create or update the entities table for a given user.
 * Call create_entity first.
 *
 * @param int    $guid     The user's GUID
 * @param string $name     The user's display name
 * @param string $username The username
 * @param string $password The password
 * @param string $salt     A salt for the password
 * @param string $email    The user's email address
 * @param string $language The user's default language
 * @param string $code     A code
 *
 * @return bool
 * @access private
 * @deprecated 1.9 Use ElggUser constructor
 */
function create_user_entity($guid, $name, $username, $password, $salt, $email, $language, $code) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use ElggUser constructor', 1.9);
	global $CONFIG;

	$guid = (int)$guid;
	$name = sanitise_string($name);
	$username = sanitise_string($username);
	$password = sanitise_string($password);
	$salt = sanitise_string($salt);
	$email = sanitise_string($email);
	$language = sanitise_string($language);
	$code = sanitise_string($code);

	$row = get_entity_as_row($guid);
	if ($row) {
		// Exists and you have access to it
		$query = "SELECT guid from {$CONFIG->dbprefix}users_entity where guid = {$guid}";
		if ($exists = get_data_row($query)) {
			$query = "UPDATE {$CONFIG->dbprefix}users_entity
				SET name='$name', username='$username', password='$password', salt='$salt',
				email='$email', language='$language', code='$code'
				WHERE guid = $guid";

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
			// Exists query failed, attempt an insert.
			$query = "INSERT into {$CONFIG->dbprefix}users_entity
				(guid, name, username, password, salt, email, language, code)
				values ($guid, '$name', '$username', '$password', '$salt', '$email', '$language', '$code')";

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
 * Create or update the extras table for a given object.
 * Call create_entity first.
 *
 * @param int    $guid        The guid of the entity you're creating (as obtained by create_entity)
 * @param string $title       The title of the object
 * @param string $description The object's description
 *
 * @return bool
 * @access private
 * @deprecated 1.9 Use ElggObject constructor
 */
function create_object_entity($guid, $title, $description) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use ElggObject constructor', 1.9);
	global $CONFIG;

	$guid = (int)$guid;
	$title = sanitise_string($title);
	$description = sanitise_string($description);

	$row = get_entity_as_row($guid);

	if ($row) {
		// Core entities row exists and we have access to it
		$query = "SELECT guid from {$CONFIG->dbprefix}objects_entity where guid = {$guid}";
		if ($exists = get_data_row($query)) {
			$query = "UPDATE {$CONFIG->dbprefix}objects_entity
				set title='$title', description='$description' where guid=$guid";

			$result = update_data($query);
			if ($result != false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				elgg_trigger_event('update', $entity->type, $entity);
				return $guid;
			}
		} else {
			// Update failed, attempt an insert.
			$query = "INSERT into {$CONFIG->dbprefix}objects_entity
				(guid, title, description) values ($guid, '$title','$description')";

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
 * Attempt to construct an ODD object out of a XmlElement or sub-elements.
 *
 * @param XmlElement $element The element(s)
 *
 * @return mixed An ODD object if the element can be handled, or false.
 * @access private
 * @deprecated 1.9
 */
function ODD_factory (XmlElement $element) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
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
 * @deprecated 1.9
 */
function ODD_Import($xml) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
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
 * @deprecated 1.9
 */
function ODD_Export(ODDDocument $document) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	return "$document";
}

/**
 * Get a UUID from a given object.
 *
 * @param mixed $object The object either an ElggEntity, ElggRelationship or ElggExtender
 *
 * @return string|false the UUID or false
 * @deprecated 1.9
 */
function get_uuid_from_object($object) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	if ($object instanceof ElggEntity) {
		return guid_to_uuid($object->guid);
	} else if ($object instanceof ElggExtender) {
		$type = $object->type;
		if ($type == 'volatile') {
			$uuid = guid_to_uuid($object->entity_guid) . $type . "/{$object->name}/";
		} else {
			$uuid = guid_to_uuid($object->entity_guid) . $type . "/{$object->id}/";
		}

		return $uuid;
	} else if ($object instanceof ElggRelationship) {
		return guid_to_uuid($object->guid_one) . "relationship/{$object->id}/";
	}

	return false;
}

/**
 * Generate a UUID from a given GUID.
 *
 * @param int $guid The GUID of an object.
 *
 * @return string
 * @deprecated 1.9
 */
function guid_to_uuid($guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	return elgg_get_site_url()  . "export/opendd/$guid/";
}

/**
 * Test to see if a given uuid is for this domain, returning true if so.
 *
 * @param string $uuid A unique ID
 *
 * @return bool
 * @deprecated 1.9
 */
function is_uuid_this_domain($uuid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	if (strpos($uuid, elgg_get_site_url()) === 0) {
		return true;
	}

	return false;
}

/**
 * This function attempts to retrieve a previously imported entity via its UUID.
 *
 * @param string $uuid A unique ID
 *
 * @return ElggEntity|false
 * @deprecated 1.9
 */
function get_entity_from_uuid($uuid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$uuid = sanitise_string($uuid);

	$options = array('metadata_name' => 'import_uuid', 'metadata_value' => $uuid);
	$entities = elgg_get_entities_from_metadata($options);

	if ($entities) {
		return $entities[0];
	}

	return false;
}

/**
 * Tag a previously created guid with the uuid it was imported on.
 *
 * @param int    $guid A GUID
 * @param string $uuid A Unique ID
 *
 * @return bool
 * @deprecated 1.9
 */
function add_uuid_to_guid($guid, $uuid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$guid = (int)$guid;
	$uuid = sanitise_string($uuid);

	$result = create_metadata($guid, "import_uuid", $uuid);
	return (bool)$result;
}


$IMPORTED_DATA = array();
$IMPORTED_OBJECT_COUNTER = 0;

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
 * @deprecated 1.9
 */
function _process_element(ODD $odd) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;

	// See if anyone handles this element, return true if it is.
	$to_be_serialised = null;
	if ($odd) {
		$handled = elgg_trigger_plugin_hook("import", "all", array("element" => $odd), $to_be_serialised);

		// If not, then see if any of its sub elements are handled
		if ($handled) {
			// Increment validation counter
			$IMPORTED_OBJECT_COUNTER ++;
			// Return the constructed object
			$IMPORTED_DATA[] = $handled;

			return true;
		}
	}

	return false;
}

/**
 * Exports an entity as an array
 *
 * @param int $guid Entity GUID
 *
 * @return array
 * @throws ExportException
 * @access private
 * @deprecated 1.9
 */
function exportAsArray($guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$guid = (int)$guid;

	// Trigger a hook to
	$to_be_serialised = elgg_trigger_plugin_hook("export", "all", array("guid" => $guid), array());

	// Sanity check
	if ((!is_array($to_be_serialised)) || (count($to_be_serialised) == 0)) {
		throw new ExportException(elgg_echo('ExportException:NoSuchEntity', array($guid)));
	}

	return $to_be_serialised;
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
 * @return string XML
 * @see ElggEntity for an example of its usage.
 * @access private
 * @deprecated 1.9
 */
function export($guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$odd = new ODDDocument(exportAsArray($guid));

	return ODD_Export($odd);
}

/**
 * Import an XML serialisation of an object.
 * This will make a best attempt at importing a given xml doc.
 *
 * @param string $xml XML string
 *
 * @return bool
 * @throws ImportException if there was a problem importing the data.
 * @access private
 * @deprecated 1.9
 */
function import($xml) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
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
 * Register the OpenDD import action
 *
 * @return void
 * @access private
 * @deprecated 1.9
 */
function _export_init() {
	global $CONFIG;

	elgg_register_action("import/opendd");
}

// Register a startup event
elgg_register_event_handler('init', 'system', '_export_init', 100);