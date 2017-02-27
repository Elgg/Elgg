<?php
/**
 * Elgg river.
 * Activity stream functions.
 *
 * @package    Elgg.Core
 * @subpackage River
 */

/**
 * Adds an item to the river.
 *
 * @tip Read the item like "Lisa (subject) posted (action)
 * a comment (object) on John's blog (target)".
 *
 * @param array $options Array in format:
 *
 * 	view => STR The view that will handle the river item (must exist)
 *
 * 	action_type => STR An arbitrary string to define the action (eg 'comment', 'create')
 *
 *  subject_guid => INT The GUID of the entity doing the action
 *
 *  object_guid => INT The GUID of the entity being acted upon
 *
 *  target_guid => INT The GUID of the the object entity's container
 *
 *  access_id => INT The access ID of the river item (default: same as the object)
 *
 *  posted => INT The UNIX epoch timestamp of the river item (default: now)
 *
 *  annotation_id INT The annotation ID associated with this river entry
 *
 *  return_item => BOOL set to true to return the ElggRiverItem created
 *
 * @return int|ElggRiverItem|bool River ID/item or false on failure
 * @since 1.9
 */
function elgg_create_river_item(array $options = array()) {
	$view = elgg_extract('view', $options);
	// use default viewtype for when called from web services api
	if (empty($view) || !(elgg_view_exists($view, 'default'))) {
		return false;
	}

	$action_type = elgg_extract('action_type', $options);
	if (empty($action_type)) {
		return false;
	}

	$subject_guid = elgg_extract('subject_guid', $options, 0);
	if (!($subject = get_entity($subject_guid))) {
		return false;
	}

	$object_guid = elgg_extract('object_guid', $options, 0);
	if (!($object = get_entity($object_guid))) {
		return false;
	}

	$target_guid = elgg_extract('target_guid', $options, 0);
	if ($target_guid) {
		// target_guid is not a required parameter so check
		// it only if it is included in the parameters
		if (!($target = get_entity($target_guid))) {
			return false;
		}
	}

	$access_id = elgg_extract('access_id', $options, $object->access_id);

	$posted = elgg_extract('posted', $options, time());

	$annotation_id = elgg_extract('annotation_id', $options, 0);
	if ($annotation_id) {
		if (!elgg_get_annotation_from_id($annotation_id)) {
			return false;
		}
	}

	$return_item = elgg_extract('return_item', $options, false);

	$values = array(
		'type' => $object->getType(),
		'subtype' => $object->getSubtype(),
		'action_type' => $action_type,
		'access_id' => $access_id,
		'view' => $view,
		'subject_guid' => $subject_guid,
		'object_guid' => $object_guid,
		'target_guid' => $target_guid,
		'annotation_id' => $annotation_id,
		'posted' => $posted,
	);
	$col_types = array(
		'type' => 'string',
		'subtype' => 'string',
		'action_type' => 'string',
		'access_id' => 'int',
		'view' => 'string',
		'subject_guid' => 'int',
		'object_guid' => 'int',
		'target_guid' => 'int',
		'annotation_id' => 'int',
		'posted' => 'int',
	);

	// return false to stop insert
	$values = elgg_trigger_plugin_hook('creating', 'river', null, $values);
	if ($values == false) {
		// inserting did not fail - it was just prevented
		return true;
	}

	$dbprefix = elgg_get_config('dbprefix');

	foreach ($values as $name => $value) {
		$sql_columns[] = $name;
		$sql_values[] = ":$name";
		$query_params[":$name"] = ($col_types[$name] === 'int') ? (int)$value : $value;
	}
	$sql = "
		INSERT INTO {$dbprefix}river (" . implode(',', $sql_columns) . ")
		VALUES (" . implode(',', $sql_values) . ")
	";
	$id = insert_data($sql, $query_params);
	if (!$id) {
		return false;
	}

	// update the entities which had the action carried out on it
	// @todo shouldn't this be done elsewhere? Like when an annotation is saved?
	$object->updateLastAction($values['posted']);

	$ia = elgg_set_ignore_access(true);
	$river_items = elgg_get_river(array('id' => $id));
	elgg_set_ignore_access($ia);

	if (!$river_items) {
		return false;
	}

	elgg_trigger_event('created', 'river', $river_items[0]);

	return $return_item ? $river_items[0] : $id;
}

/**
 * Get river items
 *
 * @note If using types and subtypes in a query, they are joined with an AND.
 *
 * @param array $options Parameters:
 *   ids                  => INT|ARR River item id(s)
 *   subject_guids        => INT|ARR Subject guid(s)
 *   object_guids         => INT|ARR Object guid(s)
 *   target_guids         => INT|ARR Target guid(s)
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
 *   distinct             => BOOL    If set to false, Elgg will drop the DISTINCT
 *                                   clause from the MySQL query, which will improve
 *                                   performance in some situations. Avoid setting this
 *                                   option without a full understanding of the
 *                                   underlying SQL query Elgg creates. (true)
 *
 *   batch                => BOOL    If set to true, an Elgg\BatchResult object will be returned
 *                                   instead of an array. (false) Since 2.3.
 *
 *   batch_inc_offset     => BOOL    If "batch" is used, this tells the batch to increment the offset
 *                                   on each fetch. This must be set to false if you delete the batched
 *                                   results. (true)
 *
 *   batch_size           => INT     If "batch" is used, this is the number of entities/rows to pull
 *                                   in before requesting more. (25)
 *
 * @return \ElggRiverItem[]|\Elgg\BatchResult|array|int
 * @since 1.8.0
 */
function elgg_get_river(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'ids'                  => ELGG_ENTITIES_ANY_VALUE,

		'subject_guids'	       => ELGG_ENTITIES_ANY_VALUE,
		'object_guids'         => ELGG_ENTITIES_ANY_VALUE,
		'target_guids'         => ELGG_ENTITIES_ANY_VALUE,
		'annotation_ids'       => ELGG_ENTITIES_ANY_VALUE,
		'action_types'         => ELGG_ENTITIES_ANY_VALUE,

		'relationship'         => null,
		'relationship_guid'    => null,
		'inverse_relationship' => false,

		'types'	               => ELGG_ENTITIES_ANY_VALUE,
		'subtypes'             => ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'   => ELGG_ENTITIES_ANY_VALUE,

		'posted_time_lower'	   => ELGG_ENTITIES_ANY_VALUE,
		'posted_time_upper'	   => ELGG_ENTITIES_ANY_VALUE,

		'limit'                => 20,
		'offset'               => 0,
		'count'                => false,
		'distinct'             => true,

		'batch'                => false,
		'batch_inc_offset'     => true,
		'batch_size'           => 25,

		'order_by'             => 'rv.posted desc',
		'group_by'             => ELGG_ENTITIES_ANY_VALUE,

		'wheres'               => array(),
		'joins'                => array(),
	);

	if (isset($options['view']) || isset($options['views'])) {
		$msg = __FUNCTION__ . ' does not support the "views" option, though you may specify values for'
			. ' the "rv.view" column using the "wheres" option.';
		elgg_log($msg, 'WARNING');
	}

	$options = array_merge($defaults, $options);

	if ($options['batch'] && !$options['count']) {
		$batch_size = $options['batch_size'];
		$batch_inc_offset = $options['batch_inc_offset'];

		// clean batch keys from $options.
		unset($options['batch'], $options['batch_size'], $options['batch_inc_offset']);

		return new \ElggBatch('elgg_get_river', $options, null, $batch_size, $batch_inc_offset);
	}

	$singulars = array('id', 'subject_guid', 'object_guid', 'target_guid', 'annotation_id', 'action_type', 'type', 'subtype');
	$options = _elgg_normalize_plural_options_array($options, $singulars);

	$wheres = $options['wheres'];

	$wheres[] = _elgg_get_guid_based_where_sql('rv.id', $options['ids']);
	$wheres[] = _elgg_get_guid_based_where_sql('rv.subject_guid', $options['subject_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('rv.object_guid', $options['object_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('rv.target_guid', $options['target_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('rv.annotation_id', $options['annotation_ids']);
	$wheres[] = _elgg_river_get_action_where_sql($options['action_types']);
	$wheres[] = _elgg_get_river_type_subtype_where_sql('rv', $options['types'],
		$options['subtypes'], $options['type_subtype_pairs']);

	if ($options['posted_time_lower'] && is_int($options['posted_time_lower'])) {
		$wheres[] = "rv.posted >= {$options['posted_time_lower']}";
	}

	if ($options['posted_time_upper'] && is_int($options['posted_time_upper'])) {
		$wheres[] = "rv.posted <= {$options['posted_time_upper']}";
	}

	if (!access_get_show_hidden_status()) {
		$wheres[] = "rv.enabled = 'yes'";
	}

	$dbprefix = elgg_get_config('dbprefix');

	// joins
	$joins = array();
	$joins[] = "JOIN {$dbprefix}entities oe ON rv.object_guid = oe.guid";

	// LEFT JOIN is used because all river items do not necessarily have target
	$joins[] = "LEFT JOIN {$dbprefix}entities te ON rv.target_guid = te.guid";

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

	// add optional joins
	$joins = array_merge($joins, $options['joins']);

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === false) {
			return false;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}

	// remove identical where clauses
	$wheres = array_unique($wheres);

	if (!$options['count']) {
		$distinct = $options['distinct'] ? "DISTINCT" : "";

		$query = "SELECT $distinct rv.* FROM {$CONFIG->dbprefix}river rv ";
	} else {
		// note: when DISTINCT unneeded, it's slightly faster to compute COUNT(*) than IDs
		$count_expr = $options['distinct'] ? "DISTINCT rv.id" : "*";

		$query = "SELECT COUNT($count_expr) as total FROM {$CONFIG->dbprefix}river rv ";
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

	// Make sure that user has access to all the entities referenced by each river item
	$object_access_where = _elgg_get_access_where_sql(array('table_alias' => 'oe'));
	$target_access_where = _elgg_get_access_where_sql(array('table_alias' => 'te'));

	// We use LEFT JOIN with entities table but the WHERE clauses are used
	// regardless if a JOIN is successfully made. The "te.guid IS NULL" is
	// needed because of this.
	$query .= "$object_access_where AND ($target_access_where OR te.guid IS NULL) ";

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

		$river_items = get_data($query, '_elgg_row_to_elgg_river_item');
		_elgg_prefetch_river_entities($river_items);

		return $river_items;
	} else {
		$total = get_data_row($query);
		return (int)$total->total;
	}
}

/**
 * Prefetch entities that will be displayed in the river.
 *
 * @param \ElggRiverItem[] $river_items
 * @access private
 */
function _elgg_prefetch_river_entities(array $river_items) {
	// prefetch objects, subjects and targets
	$guids = array();
	foreach ($river_items as $item) {
		if ($item->subject_guid && !_elgg_services()->entityCache->get($item->subject_guid)) {
			$guids[$item->subject_guid] = true;
		}
		if ($item->object_guid && !_elgg_services()->entityCache->get($item->object_guid)) {
			$guids[$item->object_guid] = true;
		}
		if ($item->target_guid && !_elgg_services()->entityCache->get($item->target_guid)) {
			$guids[$item->target_guid] = true;
		}
	}
	if ($guids) {
		// The entity cache only holds 256. We don't want to bump out any plugins.
		$guids = array_slice($guids, 0, 200, true);
		// return value unneeded, just priming cache
		elgg_get_entities(array(
			'guids' => array_keys($guids),
			'limit' => 0,
			'distinct' => false,
		));
	}

	// prefetch object containers, in case they were not in the targets
	$guids = array();
	foreach ($river_items as $item) {
		$object = $item->getObjectEntity();
		if ($object->container_guid && !_elgg_services()->entityCache->get($object->container_guid)) {
			$guids[$object->container_guid] = true;
		}
	}
	if ($guids) {
		$guids = array_slice($guids, 0, 200, true);
		elgg_get_entities(array(
			'guids' => array_keys($guids),
			'limit' => 0,
			'distinct' => false,

			// Why specify? user containers are likely already loaded via the owners, and
			// specifying groups allows ege() to auto-join the groups_entity table
			'type' => 'group',
		));
	}

	// Note: We've tried combining the above ege() calls into one (pulling containers at the same time).
	// Although it seems like it would reduce queries, it added some. o_O
}

/**
 * List river items
 *
 * @param array $options Any options from elgg_get_river() plus:
 *   item_view  => STR         Alternative view to render list items
 *   pagination => BOOL        Display pagination links (true)
 *   no_results => STR|Closure Message to display if no items
 *
 * @return string
 * @since 1.8.0
 */
function elgg_list_river(array $options = array()) {
	elgg_register_rss_link();

	$defaults = array(
		'offset'     => (int) max(get_input('offset', 0), 0),
		'limit'      => (int) max(get_input('limit', max(20, elgg_get_config('default_limit'))), 0),
		'pagination' => true,
		'list_class' => 'elgg-list-river',
		'no_results' => '',
	);

	$options = array_merge($defaults, $options);

	if (!$options["limit"] && !$options["offset"]) {
		// no need for pagination if listing is unlimited
		$options["pagination"] = false;
	}

	$options['count'] = true;
	$count = elgg_get_river($options);

	if ($count > 0) {
		$options['count'] = false;
		$items = elgg_get_river($options);
	} else {
		$items = array();
	}

	$options['count'] = $count;
	$options['items'] = $items;

	return elgg_view('page/components/list', $options);
}

/**
 * Convert a database row to a new \ElggRiverItem
 *
 * @param \stdClass $row Database row from the river table
 *
 * @return \ElggRiverItem
 * @since 1.8.0
 * @access private
 */
function _elgg_row_to_elgg_river_item($row) {
	if (!($row instanceof \stdClass)) {
		return null;
	}

	return new \ElggRiverItem($row);
}

/**
 * Returns SQL where clause for type and subtype on river table
 *
 * @internal This is a simplified version of elgg_get_entity_type_subtype_where_sql()
 * which could be used for all queries once the subtypes have been denormalized.
 *
 * @param string     $table    'rv'
 * @param null|array $types    Array of types or null if none.
 * @param null|array $subtypes Array of subtypes or null if none
 * @param null|array $pairs    Array of pairs of types and subtypes
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function _elgg_get_river_type_subtype_where_sql($table, $types, $subtypes, $pairs) {
	// short circuit if nothing is requested
	if (!$types && !$subtypes && !$pairs) {
		return '';
	}

	$wheres = array();
	$types_wheres = array();
	$subtypes_wheres = array();

	// if no pairs, use types and subtypes
	if (!is_array($pairs)) {
		if ($types) {
			if (!is_array($types)) {
				$types = array($types);
			}
			foreach ($types as $type) {
				$type = sanitise_string($type);
				$types_wheres[] = "({$table}.type = '$type')";
			}
		}

		if ($subtypes) {
			if (!is_array($subtypes)) {
				$subtypes = array($subtypes);
			}
			foreach ($subtypes as $subtype) {
				$subtype = sanitise_string($subtype);
				$subtypes_wheres[] = "({$table}.subtype = '$subtype')";
			}
		}

		if (is_array($types_wheres) && count($types_wheres)) {
			$types_wheres = array(implode(' OR ', $types_wheres));
		}

		if (is_array($subtypes_wheres) && count($subtypes_wheres)) {
			$subtypes_wheres = array('(' . implode(' OR ', $subtypes_wheres) . ')');
		}

		$wheres = array(implode(' AND ', array_merge($types_wheres, $subtypes_wheres)));

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
function _elgg_river_get_action_where_sql($types) {
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
 * @param array $views Array of view strings
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function _elgg_river_get_view_where_sql($views) {
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
	
	$dbprefix = elgg_get_config('dbprefix');
	$query = "
		UPDATE {$dbprefix}river
		SET access_id = :access_id
		WHERE object_guid = :object_guid
	";

	$params = [
		':access_id' => (int) $access_id,
		':object_guid' => (int) $object_guid,
	];

	return update_data($query, $params);
}

/**
 * Page handler for activity
 *
 * @param array $segments URL segments
 * @return \Elgg\Http\ResponseBuilder
 * @access private
 */
function _elgg_river_page_handler($segments) {
	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

	// make a URL segment available in page handler script
	$page_type = elgg_extract(0, $segments, 'all');
	$page_type = preg_replace('[\W]', '', $page_type);

	if ($page_type == 'owner') {
		elgg_gatekeeper();
		$page_username = elgg_extract(1, $segments, '');
		if ($page_username == elgg_get_logged_in_user_entity()->username) {
			$page_type = 'mine';
		} else {
			$vars['subject_username'] = $page_username;
		}
	}

	$vars['page_type'] = $page_type;

	return elgg_ok_response(elgg_view_resource("river", $vars));
}

/**
 * Register river unit tests
 * @access private
 */
function _elgg_river_test($hook, $type, $value) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreRiverAPITest.php';
	return $value;
}

/**
 * Disable river entries that reference a disabled entity as subject/object/target
 *
 * @param string $event The event 'disable'
 * @param string $type Type of entity being disabled 'all'
 * @param mixed $entity The entity being disabled
 * @return boolean
 * @access private
 */
function _elgg_river_disable($event, $type, $entity) {

	if (!elgg_instanceof($entity)) {
		return true;
	}

	$dbprefix = elgg_get_config('dbprefix');
	$query = <<<QUERY
	UPDATE {$dbprefix}river AS rv
	SET rv.enabled = 'no'
	WHERE (rv.subject_guid = {$entity->guid} OR rv.object_guid = {$entity->guid} OR rv.target_guid = {$entity->guid});
QUERY;

	update_data($query);
	return true;
}


/**
 * Enable river entries that reference a re-enabled entity as subject/object/target
 *
 * @param string $event The event 'enable'
 * @param string $type Type of entity being enabled 'all'
 * @param mixed $entity The entity being enabled
 * @return boolean
 * @access private
 */
function _elgg_river_enable($event, $type, $entity) {

	if (!elgg_instanceof($entity)) {
		return true;
	}

	$dbprefix = elgg_get_config('dbprefix');
	$query = <<<QUERY
	UPDATE {$dbprefix}river AS rv
	LEFT JOIN {$dbprefix}entities AS se ON se.guid = rv.subject_guid
	LEFT JOIN {$dbprefix}entities AS oe ON oe.guid = rv.object_guid
	LEFT JOIN {$dbprefix}entities AS te ON te.guid = rv.target_guid
	SET rv.enabled = 'yes'
	WHERE (
			(se.enabled = 'yes' OR se.guid IS NULL) AND
			(oe.enabled = 'yes' OR oe.guid IS NULL) AND
			(te.enabled = 'yes' OR te.guid IS NULL)
		)
		AND (se.guid = {$entity->guid} OR oe.guid = {$entity->guid} OR te.guid = {$entity->guid});
QUERY;

	update_data($query);
	return true;
}

/**
 * Initialize river library
 * @access private
 */
function _elgg_river_init() {
	elgg_register_page_handler('activity', '_elgg_river_page_handler');
	$item = new \ElggMenuItem('activity', elgg_echo('activity'), 'activity');
	elgg_register_menu_item('site', $item);

	elgg_register_widget_type('river_widget', elgg_echo('river:widget:title'), elgg_echo('river:widget:description'));

	elgg_register_action('river/delete', '', 'admin');

	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_river_test');

	// For BC, we want required AMD modules to be loaded even if plugins
	// overwrite these views
	elgg_extend_view('core/river/filter', 'core/river/filter_deps');
	elgg_extend_view('forms/comment/save', 'forms/comment/save_deps');
	
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_river_init');
	$events->registerHandler('disable:after', 'all', '_elgg_river_disable', 600);
	$events->registerHandler('enable:after', 'all', '_elgg_river_enable', 600);
};
