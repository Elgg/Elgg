<?php
/**
 * Elgg river 2.0.
 * Functions for listening for and generating the river separately from the system log.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Adds an item to the river.
 *
 * @param string $view The view that will handle the river item (must exist)
 * @param string $action_type   An arbitrary string to define the action (eg 'comment', 'create')
 * @param int $subject_guid The GUID of the entity doing the action
 * @param int $object_guid The GUID of the entity being acted upon
 * @param int $access_id The access ID of the river item (default: same as the object)
 * @param int $posted The UNIX epoch timestamp of the river item (default: now)
 * @param int    $annotation_id The annotation ID associated with this river entry
 *
 * @return bool Depending on success
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
	$params = trigger_plugin_hook('creating', 'river', null, $params);
	if ($params == false) {
		// inserting did not fail - it was just prevented
		return true;
	}

	extract($params);

	// Attempt to save river item; return success status
	$insert_data = insert_data("insert into {$CONFIG->dbprefix}river " .
		" set type = '$type', " .
		" subtype = '$subtype', " .
		" action_type = '$action_type', " .
		" access_id = $access_id, " .
		" view = '$view', " .
		" subject_guid = $subject_guid, " .
		" object_guid = $object_guid, " .
		" annotation_id = $annotation_id, " .
		" posted = $posted");

	return $insert_data;
}

/**
 * Removes all items relating to a particular acting entity from the river
 *
 * @param int $subject_guid The GUID of the entity
 * @return true|false Depending on success
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
 * @return true|false Depending on success
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
 * @param int annotation_id The ID of the annotation
 * @return true|false Depending on success
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
 * @return true|false Depending on success
 * @since 1.7.2
 */
function remove_from_river_by_id($id) {
	global $CONFIG;

	// Sanitise
	$id = (int) $id;

	return delete_data("delete from {$CONFIG->dbprefix}river where id = {$id}");
}


/**
 * Sets the access ID on river items for a particular object
 *
 * @param int $object_guid The GUID of the entity
 * @param int $access_id The access ID
 * @return true|false Depending on success
 */
function update_river_access_by_object($object_guid, $access_id) {
	// Sanitise
	$object_guid = (int) $object_guid;
	$access_id = (int) $access_id;

	// Load config
	global $CONFIG;

	// Remove
	return update_data("update {$CONFIG->dbprefix}river set access_id = {$access_id} where object_guid = {$object_guid}");
}

/**
 * Retrieves items from the river. All parameters are optional.
 *
 * @param int|array $subject_guid Acting entity to restrict to. Default: all
 * @param int|array $object_guid Entity being acted on to restrict to. Default: all
 * @param string $subject_relationship If set to a relationship type, this will use
 * 	$subject_guid as the starting point and set the subjects to be all users this
 * 	entity has this relationship with (eg 'friend'). Default: blank
 * @param string $type The type of entity to restrict to. Default: all
 * @param string $subtype The subtype of entity to restrict to. Default: all
 * @param string $action_type The type of river action to restrict to. Default: all
 * @param int $limit The number of items to retrieve. Default: 20
 * @param int $offset The page offset. Default: 0
 * @param int $posted_min The minimum time period to look at. Default: none
 * @param int $posted_max The maximum time period to look at. Default: none
 * @return array|false Depending on success
 */
function get_river_items($subject_guid = 0, $object_guid = 0, $subject_relationship = '', $type = '',
	$subtype = '', $action_type = '', $limit = 20, $offset = 0, $posted_min = 0, $posted_max = 0) {

	// Get config
	global $CONFIG;

	// Sanitise variables
	if (!is_array($subject_guid)) {
		$subject_guid = (int) $subject_guid;
	} else {
		foreach($subject_guid as $key => $temp) {
			$subject_guid[$key] = (int) $temp;
		}
	}
	if (!is_array($object_guid)) {
		$object_guid = (int) $object_guid;
	} else {
		foreach($object_guid as $key => $temp) {
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
	$where[] = str_replace("and enabled='yes'",'',str_replace('owner_guid','subject_guid',get_access_sql_suffix()));

	if (empty($subject_relationship)) {
		if (!empty($subject_guid)) {
			if (!is_array($subject_guid)) {
				$where[] = " subject_guid = {$subject_guid} ";
			} else {
				$where[] = " subject_guid in (" . implode(',',$subject_guid) . ") ";
			}
		}
	} else {
		if (!is_array($subject_guid)) {
			if ($entities = elgg_get_entities_from_relationship(array(
				'relationship' => $subject_relationship,
				'relationship_guid' => $subject_guid,
				'limit' => 9999))
			) {
				$guids = array();
				foreach($entities as $entity) {
					$guids[] = (int) $entity->guid;
				}
				// $guids[] = $subject_guid;
				$where[] = " subject_guid in (" . implode(',',$guids) . ") ";
			} else {
				return array();
			}
		}
	}
	if (!empty($object_guid))
		if (!is_array($object_guid)) {
			$where[] = " object_guid = {$object_guid} ";
		} else {
			$where[] = " object_guid in (" . implode(',',$object_guid) . ") ";
		}
	if (!empty($type)) {
		$where[] = " type = '{$type}' ";
	}
	if (!empty($subtype)) {
		$where[] = " subtype = '{$subtype}' ";
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
	$sql = "select id,type,subtype,action_type,access_id,view,subject_guid,object_guid,annotation_id,posted" .
	 		" from {$CONFIG->dbprefix}river where {$whereclause} order by posted desc limit {$offset},{$limit}";

	// Get data
	return get_data($sql);
}

/**
 * Returns a human-readable representation of a river item
 *
 * @see get_river_items
 *
 * @param stdClass $item A river item object as returned from get_river_items
 * @return string|false Depending on success
 */
function elgg_view_river_item($item) {
	if (isset($item->view)) {
		$object = get_entity($item->object_guid);
		$subject = get_entity($item->subject_guid);
		if (!$object || !$subject) {
			// probably means an entity is disabled
			return false;
		} else {
			if (elgg_view_exists($item->view)) {
				$body = elgg_view($item->view,array(
					'item' => $item
				));
			}
		}
		return elgg_view('river/item/wrapper',array(
			'item' => $item,
			'body' => $body
		));
	}
	return false;
}

/**
 * Returns a human-readable version of the river.
 *
 * @param int|array $subject_guid Acting entity to restrict to. Default: all
 * @param int|array $object_guid Entity being acted on to restrict to. Default: all
 * @param string $subject_relationship If set to a relationship type, this will use
 * 	$subject_guid as the starting point and set the subjects to be all users this
 * 	entity has this relationship with (eg 'friend'). Default: blank
 * @param string $type The type of entity to restrict to. Default: all
 * @param string $subtype The subtype of entity to restrict to. Default: all
 * @param string $action_type The type of river action to restrict to. Default: all
 * @param int $limit The number of items to retrieve. Default: 20
 * @param int $posted_min The minimum time period to look at. Default: none
 * @param int $posted_max The maximum time period to look at. Default: none
 * @return string Human-readable river.
 */
function elgg_view_river_items($subject_guid = 0, $object_guid = 0, $subject_relationship = '',
	$type = '', $subtype = '', $action_type = '', $limit = 20, $posted_min = 0, $posted_max = 0, $pagination = true) {

	// Get input from outside world and sanitise it
	$offset = (int) get_input('offset',0);

	// Get river items, if they exist
	if ($riveritems = get_river_items($subject_guid,$object_guid,$subject_relationship,$type,$subtype,$action_type,($limit + 1),$offset,$posted_min,$posted_max)) {

		return elgg_view('river/item/list',array(
			'limit' => $limit,
			'offset' => $offset,
			'items' => $riveritems,
			'pagination' => $pagination
		));

	}

	return '';
}

/**
 * Construct and execute the query required for the activity stream.
 *
 * @deprecated 1.8
 *
 * @param int $limit Limit the query.
 * @param int $offset Execute from the given object
 * @param mixed $type A type, or array of types to look for. Note: This is how they appear in the SYSTEM LOG.
 * @param mixed $subtype A subtype, or array of types to look for. Note: This is how they appear in the SYSTEM LOG.
 * @param mixed $owner_guid The guid or a collection of GUIDs
 * @param string $owner_relationship If defined, the relationship between $owner_guid and the entity owner_guid - so "is $owner_guid $owner_relationship with $entity->owner_guid"
 * @return array An array of system log entries.
 */
function get_activity_stream_data($limit = 10, $offset = 0, $type = "", $subtype = "", $owner_guid = "", $owner_relationship = "") {
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
	$activity_events= array();
	$activity_views = array_merge(elgg_view_tree('activity', 'default'), elgg_view_tree('river', 'default')); // Join activity with river

	$done = array();

	foreach ($activity_views as $view) {
		$fragments = explode('/', $view);
		$tmp = explode('/',$view, 2);
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
			if ($details['type']!='relationship') {
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
		$user = " and sl.performed_by_guid in (".implode(',', $owner_guid).")";

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
				foreach($friends as $friend) {
					$friendsarray[] = $friend->getGUID();
				}

				$user = " and sl.performed_by_guid in (" . implode(',', $friendsarray).")";
			}
		}
	}

	$query = "SELECT sl.* FROM {$CONFIG->dbprefix}system_log sl
		WHERE 1 $user AND ($obj_query)
		ORDER BY sl.time_created desc limit $offset, $limit";
	return get_data($query);
}
