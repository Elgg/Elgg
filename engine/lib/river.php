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
 * @return int/bool River ID or false on failure
 */
function add_to_river($view, $action_type, $subject_guid, $object_guid, $access_id = "",
$posted = 0, $annotation_id = 0) {

	global $CONFIG;

	// use default viewtype for when called from web services api
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

	$params = array(
		'type' => $type,
		'subtype' => $subtype,
		'action_type' => $action_type,
		'access_id' => $access_id,
		'view' => $view,
		'subject_guid' => $subject_guid,
		'object_guid' => $object_guid,
		'annotation_id' => $annotation_id,
		'posted' => $posted,
	);

	// return false to stop insert
	$params = elgg_trigger_plugin_hook('creating', 'river', null, $params);
	if ($params == false) {
		// inserting did not fail - it was just prevented
		return true;
	}

	extract($params);

	// Attempt to save river item; return success status
	$id = insert_data("insert into {$CONFIG->dbprefix}river " .
		" set type = '$type', " .
		" subtype = '$subtype', " .
		" action_type = '$action_type', " .
		" access_id = $access_id, " .
		" view = '$view', " .
		" subject_guid = $subject_guid, " .
		" object_guid = $object_guid, " .
		" annotation_id = $annotation_id, " .
		" posted = $posted");

	// update the entities which had the action carried out on it
	// @todo shouldn't this be down elsewhere? Like when an annotation is saved?
	if ($id) {
		update_entity_last_action($object_guid, $posted);
		
		$river_items = elgg_get_river(array('id' => $id));
		if ($river_items) {
			elgg_trigger_event('created', 'river', $river_items[0]);
		}
		return $id;
	} else {
		return false;
	}
}

/**
 * Delete river items
 *
 * @warning not checking access (should we?)
 *
 * @param array $options
 *   ids                  => INT|ARR River item id(s)
 *   subject_guids        => INT|ARR Subject guid(s)
 *   object_guids         => INT|ARR Object guid(s)
 *   annotation_ids       => INT|ARR The identifier of the annotation(s)
 *   action_types         => STR|ARR The river action type(s) identifier
 *   views                => STR|ARR River view(s)
 *
 *   types                => STR|ARR Entity type string(s)
 *   subtypes             => STR|ARR Entity subtype string(s)
 *   type_subtype_pairs   => ARR     Array of type => subtype pairs where subtype
 *                                   can be an array of subtype strings
 * 
 *   posted_time_lower    => INT     The lower bound on the time posted
 *   posted_time_upper    => INT     The upper bound on the time posted
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_delete_river(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'ids'                  => ELGG_ENTITIES_ANY_VALUE,

		'subject_guids'	       => ELGG_ENTITIES_ANY_VALUE,
		'object_guids'         => ELGG_ENTITIES_ANY_VALUE,
		'annotation_ids'       => ELGG_ENTITIES_ANY_VALUE,

		'views'                => ELGG_ENTITIES_ANY_VALUE,
		'action_types'         => ELGG_ENTITIES_ANY_VALUE,

		'types'	               => ELGG_ENTITIES_ANY_VALUE,
		'subtypes'             => ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'   => ELGG_ENTITIES_ANY_VALUE,

		'posted_time_lower'	   => ELGG_ENTITIES_ANY_VALUE,
		'posted_time_upper'	   => ELGG_ENTITIES_ANY_VALUE,

		'wheres'               => array(),
		'joins'                => array(),

	);

	$options = array_merge($defaults, $options);

	$singulars = array('id', 'subject_guid', 'object_guid', 'annotation_id', 'action_type', 'view', 'type', 'subtype');
	$options = elgg_normalise_plural_options_array($options, $singulars);

	$wheres = $options['wheres'];

	$wheres[] = elgg_get_guid_based_where_sql('rv.id', $options['ids']);
	$wheres[] = elgg_get_guid_based_where_sql('rv.subject_guid', $options['subject_guids']);
	$wheres[] = elgg_get_guid_based_where_sql('rv.object_guid', $options['object_guids']);
	$wheres[] = elgg_get_guid_based_where_sql('rv.annotation_id', $options['annotation_ids']);
	$wheres[] = elgg_river_get_action_where_sql($options['action_types']);
	$wheres[] = elgg_river_get_view_where_sql($options['views']);
	$wheres[] = elgg_get_river_type_subtype_where_sql('rv', $options['types'],
		$options['subtypes'], $options['type_subtype_pairs']);

	if ($options['posted_time_lower'] && is_int($options['posted_time_lower'])) {
		$wheres[] = "rv.posted >= {$options['posted_time_lower']}";
	}

	if ($options['posted_time_upper'] && is_int($options['posted_time_upper'])) {
		$wheres[] = "rv.posted <= {$options['posted_time_upper']}";
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

	$query = "DELETE rv.* FROM {$CONFIG->dbprefix}river rv ";

	// add joins
	foreach ($joins as $j) {
		$query .= " $j ";
	}

	// add wheres
	$query .= ' WHERE ';

	foreach ($wheres as $w) {
		$query .= " $w AND ";
	}
	$query .= "1=1";

	return delete_data($query);
}

/**
 * Get river items
 *
 * @param array $options
 *   ids                  => INT|ARR River item id(s)
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
		'ids'                  => ELGG_ENTITIES_ANY_VALUE,

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

	$singulars = array('id', 'subject_guid', 'object_guid', 'annotation_id', 'action_type', 'type', 'subtype');
	$options = elgg_normalise_plural_options_array($options, $singulars);

	$wheres = $options['wheres'];

	$wheres[] = elgg_get_guid_based_where_sql('rv.id', $options['ids']);
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
			$offset = sanitise_int($options['offset'], false);
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
	global $autofeed;
	$autofeed = true;

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
	return elgg_view('page/components/list', $options);
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
		str_replace('owner_guid', 'rv.subject_guid',
		str_replace('access_id', 'rv.access_id', get_access_sql_suffix())));
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
		return "(rv.action_type = '$types')";
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
 * Get the where clause based on river view strings
 *
 * @param array $types Array of view strings
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_river_get_view_where_sql($views) {
	if (!$views) {
		return '';
	}

	if (!is_array($views)) {
		$views = sanitise_string($views);
		return "(rv.view = '$views')";
	}

	// sanitize views array
	$views_sanitized = array();
	foreach ($views as $view) {
		$views_sanitized[] = sanitise_string($view);
	}

	$view_str = implode("','", $views_sanitized);
	return "(rv.view IN ('$view_str'))";
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
 * Page handler for activiy
 *
 * @param array $page
 */
function elgg_river_page_handler($page) {
	global $CONFIG;

	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

	$page_type = elgg_extract(0, $page, 'all');
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
	elgg_register_page_handler('activity', 'elgg_river_page_handler');
	$item = new ElggMenuItem('activity', elgg_echo('activity'), 'activity');
	elgg_register_menu_item('site', $item);

	elgg_register_widget_type('river_widget', elgg_echo('river:widget:title'), elgg_echo('river:widget:description'));
}

elgg_register_event_handler('init', 'system', 'elgg_river_init');
