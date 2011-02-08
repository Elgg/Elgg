<?php
/**
 * Elgg calendar / entity / event functions.
 *
 * @package    Elgg.Core
 * @subpackage Calendar
 *
 * @todo Implement or remove
 */

/**
 * Return a timestamp for the start of a given day (defaults today).
 *
 * @param int $day   Day
 * @param int $month Month
 * @param int $year  Year
 *
 * @return int
 */
function get_day_start($day = null, $month = null, $year = null) {
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
 */
function get_day_end($day = null, $month = null, $year = null) {
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
 */
function get_notable_entities($start_time, $end_time, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "asc", $limit = 10, $offset = 0, $count = false, $site_guid = 0,
$container_guid = null) {
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
 */
function get_notable_entities_from_metadata($start_time, $end_time, $meta_name, $meta_value = "",
$entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "",
$site_guid = 0, $count = false) {

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
 */
function get_noteable_entities_from_relationship($start_time, $end_time, $relationship,
$relationship_guid, $inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0) {

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
 */
function get_todays_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "",
$limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = null) {

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
 */
function get_todays_entities_from_metadata($meta_name, $meta_value = "", $entity_type = "",
$entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0,
$count = false) {

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
 */
function get_todays_entities_from_relationship($relationship, $relationship_guid,
$inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0) {

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
 */
function list_notable_entities($start_time, $end_time, $type= "", $subtype = "", $owner_guid = 0,
$limit = 10, $fullview = true, $listtypetoggle = false, $navigation = true) {

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
 */
function list_todays_entities($type= "", $subtype = "", $owner_guid = 0, $limit = 10,
$fullview = true, $listtypetoggle = false, $navigation = true) {

	$day_start = get_day_start();
	$day_end = get_day_end();

	return list_notable_entities($day_start, $day_end, $type, $subtype, $owner_guid, $limit,
		$fullview, $listtypetoggle, $navigation);
}
