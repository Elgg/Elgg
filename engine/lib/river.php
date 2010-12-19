<?php
/**
 * Elgg river.
 * Activity stream functions.
 *
 * @package Elgg.Core
 * @subpackage SocialModel.River
 */

/**
 * Adds an item to the river.
 *
 * @param string $view          The view that will handle the river item (must exist)
 * @param string $action_type   An arbitrary string to define the action (eg 'comment', 'create')
 * @param int    $subject_guid  The GUID of the entity doing the action
 * @param int    $object_guid   The GUID of the entity being acted upon
 * @param int    $access_id     The access ID of the river item (default: same as the object)
 * @param int    $posted        The UNIX epoch timestamp of the river item (default: now)
 * @param int    $annotation_id The annotation ID associated with this river entry
 *
 * @return bool Depending on success
 */
function add_to_river($view, $action_type, $subject_guid, $object_guid, $access_id = "",
$posted = 0, $annotation_id = 0) {

	// use default viewtype for when called from REST api
	if (!elgg_view_exists($view, 'default')) {
		return false;
	}
	if (!($subject = get_entity($subject_guid))) {
		return false;
	}
	if (!($object = get_entity($object_guid))) {
		return false;
	}
	if (empty($action_type)) {
		return false;
	}
	if ($posted == 0) {
		$posted = time();
	}
	if ($access_id === "") {
		$access_id = $object->access_id;
	}
	$annotation_id = (int)$annotation_id;
	$type = $object->getType();
	$subtype = $object->getSubtype();
	$action_type = sanitise_string($action_type);

	// Load config
	global $CONFIG;

	// Attempt to save river item; return success status
	$insert_data = insert_data("insert into {$CONFIG->dbprefix}river " .
		" set type = '{$type}', " .
		" subtype = '{$subtype}', " .
		" action_type = '{$action_type}', " .
		" access_id = {$access_id}, " .
		" view = '{$view}', " .
		" subject_guid = {$subject_guid}, " .
		" object_guid = {$object_guid}, " .
		" annotation_id = {$annotation_id}, " .
		" posted = {$posted} ");

	//update the entities which had the action carried out on it
	if ($insert_data) {
		update_entity_last_action($object_guid, $posted);
		return $insert_data;
	}
}

/**
 * Removes all items relating to a particular acting entity from the river
 *
 * @param int $subject_guid The GUID of the entity
 *
 * @return bool Depending on success
 */
function remove_from_river_by_subject($subject_guid) {
	// Sanitise
	$subject_guid = (int) $subject_guid;

	// Load config
	global $CONFIG;

	// Remove
	return delete_data("delete from {$CONFIG->dbprefix}river where subject_guid = {$subject_guid}");
}

/**
 * Removes all items relating to a particular entity being acted upon from the river
 *
 * @param int $object_guid The GUID of the entity
 *
 * @return bool Depending on success
 */
function remove_from_river_by_object($object_guid) {
	// Sanitise
	$object_guid = (int) $object_guid;

	// Load config
	global $CONFIG;

	// Remove
	return delete_data("delete from {$CONFIG->dbprefix}river where object_guid = {$object_guid}");
}

/**
 * Removes all items relating to a particular annotation being acted upon from the river
 *
 * @param int $annotation_id The ID of the annotation
 *
 * @return bool Depending on success
 * @since 1.7.0
 */
function remove_from_river_by_annotation($annotation_id) {
	// Sanitise
	$annotation_id = (int) $annotation_id;

	// Load config
	global $CONFIG;

	// Remove
	return delete_data("delete from {$CONFIG->dbprefix}river where annotation_id = {$annotation_id}");
}

/**
 * Removes a single river entry
 *
 * @param int $id The ID of the river entry
 *
 * @return bool Depending on success
 * @since 1.7.2
 */
function remove_from_river_by_id($id) {
	global $CONFIG;

	// Sanitise
	$id = (int) $id;

	return delete_data("delete from {$CONFIG->dbprefix}river where id = {$id}");
}

/**
 * Get river items
 *
 * @param array $options
 *   subject_guids        => INT|ARR Subject guid(s)
 *   object_guids         => INT|ARR Object guid(s)
 *   annotation_ids       => INT|ARR The identifier of the annotation(s)
 *   action_types         => STR|ARR The river action type(s) identifier
 *   posted_time_lower    => INT     The lower bound on the time posted
 *   posted_time_upper    => INT     The upper bound on the time posted
 *
 *   types                => STR|ARR Entity type string(s)
 *   subtypes             => STR|ARR Entity subtype string(s)
 *   type_subtype_pairs   => ARR     Array of type => subtype pairs where subtype
 *                                   can be an array of subtype strings
 *
 *   relationship         => STR     Relationship identifier
 *   relationship_guid    => INT|ARR Entity guid(s)
 *   inverse_relationship => BOOL    Subject or object of the relationship (false)
 *
 * 	 limit                => INT     Number to show per page (20)
 *   offset               => INT     Offset in list (0)
 *   count                => BOOL    Count the river items? (false)
 *   order_by             => STR     Order by clause (rv.posted desc)
 *   group_by             => STR     Group by clause
 *
 * @return array|int
 * @since 1.8.0
 */
function elgg_get_river(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'subject_guids'	       => ELGG_ENTITIES_ANY_VALUE,
		'object_guids'         => ELGG_ENTITIES_ANY_VALUE,
		'annotation_ids'       => ELGG_ENTITIES_ANY_VALUE,
		'action_types'         => ELGG_ENTITIES_ANY_VALUE,

		'relationship'         => NULL,
		'relationship_guid'    => NULL,
		'inverse_relationship' => FALSE,

		'types'	               => ELGG_ENTITIES_ANY_VALUE,
		'subtypes'             => ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'   => ELGG_ENTITIES_ANY_VALUE,

		'posted_time_lower'	   => ELGG_ENTITIES_ANY_VALUE,
		'posted_time_upper'	   => ELGG_ENTITIES_ANY_VALUE,

		'limit'                => 20,
		'offset'               => 0,
		'count'                => FALSE,

		'order_by'             => 'rv.posted desc',
		'group_by'             => ELGG_ENTITIES_ANY_VALUE,

		'wheres'               => array(),
		'joins'                => array(),
	);

	$options = array_merge($defaults, $options);

	$singulars = array('subject_guid', 'object_guid', 'annotation_id', 'action_type', 'type', 'subtype');
	$options = elgg_normalise_plural_options_array($options, $singulars);

	$wheres = $options['wheres'];

	$wheres[] = elgg_get_guid_based_where_sql('rv.subject_guid', $options['subject_guids']);
	$wheres[] = elgg_get_guid_based_where_sql('rv.object_guid', $options['object_guids']);
	$wheres[] = elgg_get_guid_based_where_sql('rv.annotation_id', $options['annotation_ids']);
	$wheres[] = elgg_river_get_action_where_sql($options['action_types']);
	$wheres[] = elgg_get_river_type_subtype_where_sql('rv', $options['types'],
		$options['subtypes'], $options['type_subtype_pairs']);

	if ($options['posted_time_lower'] && is_int($options['posted_time_lower'])) {
		$wheres[] = "rv.posted >= {$options['posted_time_lower']}";
	}

	if ($options['posted_time_upper'] && is_int($options['posted_time_upper'])) {
		$wheres[] = "rv.posted <= {$options['posted_time_upper']}";
	}

	$joins = $options['joins'];

	if ($options['relationship_guid']) {
		$clauses = elgg_get_entity_relationship_where_sql(
				'rv.subject_guid',
				$options['relationship'],
				$options['relationship_guid'],
				$options['inverse_relationship']);
		if ($clauses) {
			$wheres = array_merge($wheres, $clauses['wheres']);
			$joins = array_merge($joins, $clauses['joins']);
		}
	}

	// remove identical where clauses
	$wheres = array_unique($wheres);

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === FALSE) {
			return FALSE;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}

	if (!$options['count']) {
		$query = "SELECT DISTINCT rv.* FROM {$CONFIG->dbprefix}river rv ";
	} else {
		$query = "SELECT count(DISTINCT rv.id) as total FROM {$CONFIG->dbprefix}river rv ";
	}

	// add joins
	foreach ($joins as $j) {
		$query .= " $j ";
	}

	// add wheres
	$query .= ' WHERE ';

	foreach ($wheres as $w) {
		$query .= " $w AND ";
	}

	$query .= elgg_river_get_access_sql();

	if (!$options['count']) {
		$options['group_by'] = sanitise_string($options['group_by']);
		if ($options['group_by']) {
			$query .= " GROUP BY {$options['group_by']}";
		}

		$options['order_by'] = sanitise_string($options['order_by']);
		$query .= " ORDER BY {$options['order_by']}";

		if ($options['limit']) {
			$limit = sanitise_int($options['limit']);
			$offset = sanitise_int($options['offset']);
			$query .= " LIMIT $offset, $limit";
		}

		$river_items = get_data($query, 'elgg_row_to_elgg_river_item');

		return $river_items;
	} else {
		$total = get_data_row($query);
		return (int)$total->total;
	}
}

/**
 * List river items
 *
 * @param array $options Any options from elgg_get_river() plus:
 * 	 pagination => BOOL Display pagination links (true)

 * @return string
 * @since 1.8.0
 */
function elgg_list_river(array $options = array()) {

	$defaults = array(
		'offset'     => (int) max(get_input('offset', 0), 0),
		'limit'      => (int) max(get_input('limit', 20), 0),
		'pagination' => TRUE,
		'list_class' => 'elgg-river',
	);

	$options = array_merge($defaults, $options);

	$options['count'] = TRUE;
	$count = elgg_get_river($options);

	$options['count'] = FALSE;
	$items = elgg_get_river($options);

	$options['count'] = $count;
	$options['items'] = $items;
	return elgg_view('layout/objects/list', $options);
}

/**
 * Convert a database row to a new ElggRiverItem
 *
 * @param stdClass $row Database row from the river table
 *
 * @return ElggRiverItem
 * @since 1.8.0
 * @access private
 */
function elgg_row_to_elgg_river_item($row) {
	if (!($row instanceof stdClass)) {
		return NULL;
	}

	return new ElggRiverItem($row);
}

/**
 * Get the river's access where clause
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_river_get_access_sql() {
	// rewrite default access where clause to work with river table
	return str_replace("and enabled='yes'", '',
		str_replace('owner_guid', 'subject_guid', get_access_sql_suffix()));
}

/**
 * Returns SQL where clause for type and subtype on river table
 *
 * @internal This is a simplified version of elgg_get_entity_type_subtype_where_sql()
 * which could be used for all queries once the subtypes have been denormalized.
 * FYI: It allows types and subtypes to not be paired.
 *
 * @param string     $table    'rv'
 * @param NULL|array $types    Array of types or NULL if none.
 * @param NULL|array $subtypes Array of subtypes or NULL if none
 * @param NULL|array $pairs    Array of pairs of types and subtypes
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_get_river_type_subtype_where_sql($table, $types, $subtypes, $pairs) {
	// short circuit if nothing is requested
	if (!$types && !$subtypes && !$pairs) {
		return '';
	}

	$wheres = array();

	// if no pairs, use types and subtypes
	if (!is_array($pairs)) {
		if ($types) {
			if (!is_array($types)) {
				$types = array($types);
			}
			foreach ($types as $type) {
				$type = sanitise_string($type);
				$wheres[] = "({$table}.type = '$type')";
			}
		}

		if ($subtypes) {
			if (!is_array($subtypes)) {
				$subtypes = array($subtypes);
			}
			foreach ($subtypes as $subtype) {
				$subtype = sanitise_string($subtype);
				$wheres[] = "({$table}.subtype = '$subtype')";
			}
		}

		if (is_array($wheres) && count($wheres)) {
			$wheres = array(implode(' AND ', $wheres));
		}
	} else {
		// using type/subtype pairs
		foreach ($pairs as $paired_type => $paired_subtypes) {
			$paired_type = sanitise_string($paired_type);
			if (is_array($paired_subtypes)) {
				$paired_subtypes = array_map('sanitise_string', $paired_subtypes);
				$paired_subtype_str = implode("','", $paired_subtypes);
				if ($paired_subtype_str) {
					$wheres[] = "({$table}.type = '$paired_type'"
						. " AND {$table}.subtype IN ('$paired_subtype_str'))";
				}
			} else {
				$paired_subtype = sanitise_string($paired_subtypes);
				$wheres[] = "({$table}.type = '$paired_type'"
					. " AND {$table}.subtype = '$paired_subtype')";
			}
		}
	}

	if (is_array($wheres) && count($wheres)) {
		$where = implode(' OR ', $wheres);
		return "($where)";
	}

	return '';
}

/**
 * Get the where clause based on river action type strings
 *
 * @param array $types Array of action type strings
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_river_get_action_where_sql($types) {
	if (!$types) {
		return '';
	}

	if (!is_array($types)) {
		$types = sanitise_string($types);
		return "'(rv.action_type = '$types')";
	}

	// sanitize types array
	$types_sanitized = array();
	foreach ($types as $type) {
		$types_sanitized[] = sanitise_string($type);
	}

	$type_str = implode("','", $types_sanitized);
	return "(rv.action_type IN ('$type_str'))";
}

/**
 * Returns a human-readable representation of a river item
 *
 * @param ElggRiverItem $item A river item object
 *
 * @return string|false Depending on success
 */
function elgg_view_river_item($item) {
	if (!$item || !$item->getView() || !elgg_view_exists($item->getView())) {
		return '';
	}

	$subject = $item->getSubjectEntity();
	$object = $item->getObjectEntity();
	if (!$subject || !$object) {
		// subject is disabled or subject/object deleted
		return '';
	}

	$vars = array(
		'image' => elgg_view('core/river/image', array('item' => $item)),
		'body' => elgg_view('core/river/body', array('item' => $item)),
		'image_alt' => elgg_view('core/river/controls', array('item' => $item)),
		'class' => 'elgg-river-item',
	);
	return elgg_view('layout/objects/image_block', $vars);
}

/**
 * Sets the access ID on river items for a particular object
 *
 * @param int $object_guid The GUID of the entity
 * @param int $access_id   The access ID
 *
 * @return bool Depending on success
 */
function update_river_access_by_object($object_guid, $access_id) {
	// Sanitise
	$object_guid = (int) $object_guid;
	$access_id = (int) $access_id;

	// Load config
	global $CONFIG;

	// Remove
	$query = "update {$CONFIG->dbprefix}river
		set access_id = {$access_id}
		where object_guid = {$object_guid}";
	return update_data($query);
}

/**
 * Retrieves items from the river. All parameters are optional.
 *
 * @param int|array $subject_guid         Acting entity to restrict to. Default: all
 * @param int|array $object_guid          Entity being acted on to restrict to. Default: all
 * @param string    $subject_relationship If set to a relationship type, this will use
 * 	                                      $subject_guid as the starting point and set the
 *                                        subjects to be all users this
 *                                        entity has this relationship with (eg 'friend').
 *                                        Default: blank
 * @param string    $type                 The type of entity to restrict to. Default: all
 * @param string    $subtype              The subtype of entity to restrict to. Default: all
 * @param string    $action_type          The type of river action to restrict to. Default: all
 * @param int       $limit                The number of items to retrieve. Default: 20
 * @param int       $offset               The page offset. Default: 0
 * @param int       $posted_min           The minimum time period to look at. Default: none
 * @param int       $posted_max           The maximum time period to look at. Default: none
 *
 * @return array|false Depending on success
 * @deprecated 1.8
 */
function get_river_items($subject_guid = 0, $object_guid = 0, $subject_relationship = '',
$type = '',	$subtype = '', $action_type = '', $limit = 20, $offset = 0, $posted_min = 0,
$posted_max = 0) {
	elgg_deprecated_notice("get_river_items deprecated by elgg_get_river", 1.8);

	$options = array();

	if ($subject_guid) {
		$options['subject_guid'] = $subject_guid;
	}

	if ($object_guid) {
		$options['object_guid'] = $object_guid;
	}

	if ($subject_relationship) {
		$options['relationship'] = $subject_relationship;
		unset($options['subject_guid']);
		$options['relationship_guid'] = $subject_guid;
	}

	if ($type) {
		$options['type'] = $type;
	}

	if ($subtype) {
		$options['subtype'] = $subtype;
	}

	if ($action_type) {
		$options['action_type'] = $action_type;
	}

	$options['limit'] = $limit;
	$options['offset'] = $offset;

	if ($posted_min) {
		$options['posted_time_lower'] = $posted_min;
	}

	if ($posted_max) {
		$options['posted_time_upper'] = $posted_max;
	}

	return elgg_get_river($options);
}

/**
 * Returns a human-readable version of the river.
 *
 * @param int|array $subject_guid         Acting entity to restrict to. Default: all
 * @param int|array $object_guid          Entity being acted on to restrict to. Default: all
 * @param string    $subject_relationship If set to a relationship type, this will use
 * 	                                      $subject_guid as the starting point and set
 *                                        the subjects to be all users this entity has this
 *                                        relationship with (eg 'friend'). Default: blank
 * @param string    $type                 The type of entity to restrict to. Default: all
 * @param string    $subtype              The subtype of entity to restrict to. Default: all
 * @param string    $action_type          The type of river action to restrict to. Default: all
 * @param int       $limit                The number of items to retrieve. Default: 20
 * @param int       $posted_min           The minimum time period to look at. Default: none
 * @param int       $posted_max           The maximum time period to look at. Default: none
 * @param bool      $pagination           Show pagination?
 *
 * @return string Human-readable river.
 * @deprecated 1.8
 */
function elgg_view_river_items($subject_guid = 0, $object_guid = 0, $subject_relationship = '',
$type = '', $subtype = '', $action_type = '', $limit = 20, $posted_min = 0,
$posted_max = 0, $pagination = true) {
	elgg_deprecated_notice("elgg_view_river_items deprecated for elgg_list_river", 1.8);

	$river_items = get_river_items($subject_guid, $object_guid, $subject_relationship,
			$type, $subtype, $action_type, $limit + 1, $posted_min, $posted_max);

	// Get input from outside world and sanitise it
	$offset = (int) get_input('offset', 0);

	// view them
	$params = array(
		'items' => $river_items,
		'count' => count($river_items),
		'offset' => $offset,
		'limit' => $limit,
		'pagination' => $pagination,
		'list-class' => 'elgg-river-list',
	);

	return elgg_view('layout/objects/list', $params);
}

/**
 * Construct and execute the query required for the activity stream.
 *
 * @deprecated 1.8
 */
function get_activity_stream_data($limit = 10, $offset = 0, $type = "", $subtype = "",
$owner_guid = "", $owner_relationship = "") {
	elgg_deprecated_notice("get_activity_stream_data was deprecated", 1.8);

	global $CONFIG;

	$limit = (int)$limit;
	$offset = (int)$offset;

	if ($type) {
		if (!is_array($type)) {
			$type = array(sanitise_string($type));
		} else {
			foreach ($type as $k => $v) {
				$type[$k] = sanitise_string($v);
			}
		}
	}

	if ($subtype) {
		if (!is_array($subtype)) {
			$subtype = array(sanitise_string($subtype));
		} else {
			foreach ($subtype as $k => $v) {
				$subtype[$k] = sanitise_string($v);
			}
		}
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			foreach ($owner_guid as $k => $v) {
				$owner_guid[$k] = (int)$v;
			}
		} else {
			$owner_guid = array((int)$owner_guid);
		}
	}

	$owner_relationship = sanitise_string($owner_relationship);

	// Get a list of possible views
	$activity_events = array();
	$activity_views = array_merge(elgg_view_tree('activity', 'default'),
		elgg_view_tree('river', 'default'));

	$done = array();

	foreach ($activity_views as $view) {
		$fragments = explode('/', $view);
		$tmp = explode('/', $view, 2);
		$tmp = $tmp[1];

		if ((isset($fragments[0])) && (($fragments[0] == 'river') || ($fragments[0] == 'activity'))
			&& (!in_array($tmp, $done))) {

			if (isset($fragments[1])) {
				$f = array();
				for ($n = 1; $n < count($fragments); $n++) {
					$val = sanitise_string($fragments[$n]);
					switch($n) {
						case 1: $key = 'type'; break;
						case 2: $key = 'subtype'; break;
						case 3: $key = 'event'; break;
					}
					$f[$key] = $val;
				}

				// Filter result based on parameters
				$add = true;
				if ($type) {
					if (!in_array($f['type'], $type)) {
						$add = false;
					}
				}
				if (($add) && ($subtype)) {
					if (!in_array($f['subtype'], $subtype)) {
						$add = false;
					}
				}
				if (($add) && ($event)) {
					if (!in_array($f['event'], $event)) {
						$add = false;
					}
				}

				if ($add) {
					$activity_events[] = $f;
				}
			}

			$done[] = $tmp;
		}
	}

	$n = 0;
	foreach ($activity_events as $details) {
		// Get what we're talking about
		if ($details['subtype'] == 'default') {
			$details['subtype'] = '';
		}

		if (($details['type']) && ($details['event'])) {
			if ($n > 0) {
				$obj_query .= " or ";
			}

			$access = "";
			if ($details['type'] != 'relationship') {
				$access = " and " . get_access_sql_suffix('sl');
			}

			$obj_query .= "( sl.object_type='{$details['type']}'
				AND sl.object_subtype='{$details['subtype']}'
				AND sl.event='{$details['event']}' $access )";

			$n++;
		}
	}

	// User
	if ((count($owner_guid)) &&  ($owner_guid[0] != 0)) {
		$user = " and sl.performed_by_guid in (" . implode(',', $owner_guid) . ")";

		if ($owner_relationship) {
			$friendsarray = "";
			if ($friends = elgg_get_entities_from_relationship(array(
				'relationship' => $owner_relationship,
				'relationship_guid' => $owner_guid[0],
				'inverse_relationship' => FALSE,
				'types' => 'user',
				'subtypes' => $subtype,
				'limit' => 9999))
			) {

				$friendsarray = array();
				foreach ($friends as $friend) {
					$friendsarray[] = $friend->getGUID();
				}

				$user = " and sl.performed_by_guid in (" . implode(',', $friendsarray) . ")";
			}
		}
	}

	$query = "SELECT sl.* FROM {$CONFIG->dbprefix}system_log sl
		WHERE 1 $user AND ($obj_query)
		ORDER BY sl.time_created desc limit $offset, $limit";
	return get_data($query);
}

/**
 * Page handler for activiy
 *
 * @param array $page
 */
function elgg_river_page_handler($page) {
	global $CONFIG;

	elgg_set_page_owner_guid(get_loggedin_userid());

	$page_type = elgg_get_array_value(0, $page, 'all');
	if ($page_type == 'owner') {
		$page_type = 'mine';
	}

	// content filter code here
	$entity_type = '';
	$entity_subtype = '';

	require_once("{$CONFIG->path}pages/river.php");
}

/**
 * Initialize river library
 */
function elgg_river_init() {
	register_page_handler('activity', 'elgg_river_page_handler');
	$item = new ElggMenuItem('activity', elgg_echo('activity'), 'pg/activity');
	elgg_register_menu_item('site', $item);
}

elgg_register_event_handler('init', 'system', 'elgg_river_init');
