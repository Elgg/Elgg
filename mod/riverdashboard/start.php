<?php
/**
 * Elgg river dashboard plugin
 *
 * @package RiverDashboard
 */

elgg_register_event_handler('init', 'system', 'riverdashboard_init');

function riverdashboard_init() {
	global $CONFIG;
	// Register and optionally replace the dashboard
	register_page_handler('dashboard', 'riverdashboard_page_handler');

	// Page handler
	register_page_handler('activity', 'riverdashboard_page_handler');
	elgg_extend_view('css/screen', 'riverdashboard/css');

	// add an activity stream ECML keyword
	// we'll restrict it to use in sitepages's custom_frontpage
	elgg_register_plugin_hook_handler('get_keywords', 'ecml', 'riverdashboard_ecml_keywords_hook');

	elgg_register_plugin_hook_handler('get_views', 'ecml', 'riverdashboard_ecml_views_hook');
}

/**
 * Page handler for riverdash
 *
 * @param unknown_type $page
 */
function riverdashboard_page_handler($page){
	include(dirname(__FILE__) . "/index.php");
	return TRUE;
}

/**
 * For users to make a comment on a river item
 *
 * @param ElggEntity $entity The entity to comment on
 * @return string|false The HTML (etc) for the comment form, or false on failure
 */
function elgg_make_river_comment($entity){
	if (!($entity instanceof ElggEntity)) {
		return FALSE;
	} else {
		//display the comment form
		$comments = elgg_view('riverdashboard/rivercomment', array('entity' => $entity));
		return $comments;
	}
}


/**
 * Register activity keyword.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function riverdashboard_ecml_keywords_hook($hook, $type, $value, $params) {
	$value['activity'] = array(
		'view' => "riverdashboard/ecml/activity",
		'description' => elgg_echo('riverdashboard:ecml:desc:activity'),
		'usage' => elgg_echo('riverdashboard:ecml:usage:activity'),
		'restricted' => array('sitepages/custom_frontpage')
	);

	return $value;
}

/**
 * Register the activity front page with ECML.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 */
function riverdashboard_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['riverdashboard/container'] = elgg_echo('riverdashboard:ecml:riverdashboard');

	return $return_value;
}

/**
 * Retrieves items from the river. All parameters are optional.
 *
 * @param int|array $subject_guid         Acting entity to restrict to. Default: all
 * @param int|array $object_guid          Entity being acted on to restrict to. Default: all
 * @param string    $subject_relationship If set to a relationship type, this will use
 * 	                                      $subject_guid as the starting point and set the
 *                                        subjects to be all users this entity has this
 *                                        relationship with (eg 'friend'). Default: blank
 * @param string    $type                 The type of entity to restrict to. Default: all
 * @param string    $subtype              The subtype of entity to restrict to. Default: all
 * @param string    $action_type          The type of river action to restrict to. Default: all
 * @param int       $limit                The number of items to retrieve. Default: 20
 * @param int       $offset               The page offset. Default: 0
 * @param int       $posted_min           The minimum time period to look at. Default: none
 * @param int       $posted_max           The maximum time period to look at. Default: none
 *
 * @return array|false Depending on success
 */
function riverdashboard_get_river_items($subject_guid = 0, $object_guid = 0, $subject_relationship = '',
$type = '', $subtype = '', $action_type = '', $limit = 10, $offset = 0, $posted_min = 0,
$posted_max = 0) {

	// Get config
	global $CONFIG;

	// Sanitise variables
	if (!is_array($subject_guid)) {
		$subject_guid = (int) $subject_guid;
	} else {
		foreach ($subject_guid as $key => $temp) {
			$subject_guid[$key] = (int) $temp;
		}
	}
	if (!is_array($object_guid)) {
		$object_guid = (int) $object_guid;
	} else {
		foreach ($object_guid as $key => $temp) {
			$object_guid[$key] = (int) $temp;
		}
	}
	if (!empty($type)) {
		$type = sanitise_string($type);
	}
	if (!empty($subtype)) {
		$subtype = sanitise_string($subtype);
	}
	if (!empty($action_type)) {
		$action_type = sanitise_string($action_type);
	}
	$limit = (int) $limit;
	$offset = (int) $offset;
	$posted_min = (int) $posted_min;
	$posted_max = (int) $posted_max;

	// Construct 'where' clauses for the river
	$where = array();
	// river table does not have columns expected by get_access_sql_suffix so we modify its output
	$where[] = str_replace("and enabled='yes'", '',
		str_replace('owner_guid', 'subject_guid', riverdashboard_get_access_sql_suffix('er', 'e')));

	if (empty($subject_relationship)) {
		if (!empty($subject_guid)) {
			if (!is_array($subject_guid)) {
				$where[] = " subject_guid = {$subject_guid} ";
			} else {
				$where[] = " subject_guid in (" . implode(',', $subject_guid) . ") ";
			}
		}
	} else {
		if (!is_array($subject_guid)) {
			$entities = elgg_get_entities_from_relationship(array(
				'relationship' => $subject_relationship,
				'relationship_guid' => $subject_guid,
				'limit' => 9999,
			));
			if (is_array($entities) && !empty($entities)) {
				$guids = array();
				foreach ($entities as $entity) {
					$guids[] = (int) $entity->guid;
				}
				// $guids[] = $subject_guid;
				$where[] = " subject_guid in (" . implode(',', $guids) . ") ";
			} else {
				return array();
			}
		}
	}
	if (!empty($object_guid)) {
		if (!is_array($object_guid)) {
			$where[] = " object_guid = {$object_guid} ";
		} else {
			$where[] = " object_guid in (" . implode(',', $object_guid) . ") ";
		}
	}
	if (!empty($type)) {
		$where[] = " er.type = '{$type}' ";
	}
	if (!empty($subtype)) {
		$where[] = " er.subtype = '{$subtype}' ";
	}
	if (!empty($action_type)) {
		$where[] = " action_type = '{$action_type}' ";
	}
	if (!empty($posted_min)) {
		$where[] = " posted > {$posted_min} ";
	}
	if (!empty($posted_max)) {
		$where[] = " posted < {$posted_max} ";
	}

	$whereclause = implode(' and ', $where);

	// Construct main SQL
	$sql = "select er.*" .
			" from {$CONFIG->dbprefix}river er, {$CONFIG->dbprefix}entities e " .
			" where {$whereclause} AND er.object_guid = e.guid GROUP BY object_guid " .
			" ORDER BY e.last_action desc LIMIT {$offset}, {$limit}";

	// Get data
	return get_data($sql, 'elgg_row_to_elgg_river_item');
}

/**
 * This function has been added here until we decide if it is going to roll into core or not
 * Add access restriction sql code to a given query.
 * Note that if this code is executed in privileged mode it will return blank.
 *
 * @TODO: DELETE once Query classes are fully integrated
 *
 * @param string $table_prefix_one Optional table. prefix for the access code.
 * @param string $table_prefix_two Another optiona table prefix?
 * @param int    $owner            Owner GUID
 *
 * @return string
 */
function riverdashboard_get_access_sql_suffix($table_prefix_one = '', $table_prefix_two = '', $owner = null) {
	global $ENTITY_SHOW_HIDDEN_OVERRIDE, $CONFIG;

	$sql = "";
	$friends_bit = "";
	$enemies_bit = "";

	if ($table_prefix_one) {
			$table_prefix_one = sanitise_string($table_prefix_one) . ".";
	}

	if ($table_prefix_two) {
			$table_prefix_two = sanitise_string($table_prefix_two) . ".";
	}

	if (!isset($owner)) {
		$owner = get_loggedin_userid();
	}

	if (!$owner) {
		$owner = -1;
	}

	$ignore_access = elgg_check_access_overrides($owner);
	$access = get_access_list($owner);

	if ($ignore_access) {
		$sql = " (1 = 1) ";
	} else if ($owner != -1) {
		$friends_bit = "{$table_prefix_one}access_id = " . ACCESS_FRIENDS . "
			AND {$table_prefix_one}owner_guid IN (
				SELECT guid_one FROM {$CONFIG->dbprefix}entity_relationships
				WHERE relationship='friend' AND guid_two=$owner
			)";

		$friends_bit = '(' . $friends_bit . ') OR ';

		if ((isset($CONFIG->user_block_and_filter_enabled)) && ($CONFIG->user_block_and_filter_enabled)) {
			// check to see if the user is in the entity owner's block list
			// or if the entity owner is in the user's filter list
			// if so, disallow access
			$enemies_bit = get_annotation_sql('elgg_block_list', "{$table_prefix_one}owner_guid",
				$owner, false);

			$enemies_bit = '('
				. $enemies_bit
				. '	AND ' . get_annotation_sql('elgg_filter_list', $owner, "{$table_prefix_one}owner_guid",
					false)
			. ')';
		}
	}

	if (empty($sql)) {
		$sql = " $friends_bit ({$table_prefix_one}access_id IN {$access}
			OR ({$table_prefix_one}owner_guid = {$owner})
			OR (
				{$table_prefix_one}access_id = " . ACCESS_PRIVATE . "
				AND {$table_prefix_one}owner_guid = $owner
			)
		)";
	}

	if ($enemies_bit) {
		$sql = "$enemies_bit AND ($sql)";
	}

	if (!$ENTITY_SHOW_HIDDEN_OVERRIDE) {
		$sql .= " and {$table_prefix_two}enabled='yes'";
	}

	return '(' . $sql . ')';
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
 * @param $bool     $chronological        Show in chronological order?
 *
 * @return string Human-readable river.
 */
function riverdashboard_view_river_items($subject_guid = 0, $object_guid = 0, $subject_relationship = '',
$type = '', $subtype = '', $action_type = '', $limit = 20, $posted_min = 0,
$posted_max = 0, $pagination = true) {

	// Get input from outside world and sanitise it
	$offset = (int) get_input('offset', 0);

	$riveritems = riverdashboard_get_river_items($subject_guid, $object_guid, $subject_relationship, $type,
			$subtype, $action_type, ($limit + 1), $offset, $posted_min, $posted_max);

	// Get river items, if they exist
	if ($riveritems) {

		return elgg_view('river/item/list', array(
			'limit' => $limit,
			'offset' => $offset,
			'items' => $riveritems,
			'pagination' => $pagination
		));

	}

	return '';
}

/**
 * Returns a human-readable representation of a river item
 *
 * @param ElggRiverItem $item A river item object
 *
 * @return string|false Depending on success
 */
function riverdashboard_view_river_item($item) {

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
		'pict' => elgg_view('core/river/image', array('item' => $item)),
		'body' => elgg_view('riverdashboard/river/body', array('item' => $item)),
		'class' => 'elgg-river-item',
		'id' => "river-entity-{$object->guid}",
	);
	return elgg_view('layout/objects/media', $vars);
/*
	if (isset($item->view)) {
		$object = get_entity($item->object_guid);
		$subject = get_entity($item->subject_guid);
		if (!$object || !$subject) {
			// probably means an entity is disabled
			return false;
		} else {
			if (elgg_view_exists($item->view)) {
				$body = elgg_view($item->view, array(
					'item' => $item
				));
			}
		}
		return elgg_view('river/item/wrapper', array(
			'item' => $item,
			'body' => $body
		));
	}
	return false;
 * 
 */
}
