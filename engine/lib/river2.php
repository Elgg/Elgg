<?php
/**
 * Elgg river 2.0.
 * Functions for listening for and generating the river separately from the system log.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/**
 * Adds an item to the river.
 *
 * @param string $view The view that will handle the river item (must exist)
 * @param string $action_type An arbitrary one-word string to define the action (eg 'comment', 'create')
 * @param int $subject_guid The GUID of the entity doing the action
 * @param int $object_guid The GUID of the entity being acted upon
 * @param int $access_id The access ID of the river item (default: same as the object)
 * @param int $posted The UNIX epoch timestamp of the river item (default: now)
 * @return true|false Depending on success
 */
function add_to_river($view,$action_type,$subject_guid,$object_guid,$access_id = "",$posted = 0, $annotation_id = 0) {
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
	if($insert_data){
		update_entity_last_action($object_guid, $posted);
		return $insert_data;
	}
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
function elgg_get_river_items($subject_guid = 0, $object_guid = 0, $subject_relationship = '', $type = '',
	$subtype = '', $action_type = '', $limit = 10, $offset = 0, $posted_min = 0, $posted_max = 0) {

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
	$where[] = str_replace("and enabled='yes'",'',str_replace('owner_guid','subject_guid',get_access_sql_suffix_new('er','e')));

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
			if ($entities = get_entities_from_relationship($subject_relationship,$subject_guid,false,'','',0,'',9999)) {
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
	 		" ORDER BY e.last_action desc LIMIT {$offset},{$limit}";

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
		if (!$object) {
			$body = elgg_view('river/item/noaccess');
		} else {
			if (elgg_view_exists($item->view)) {
				$body = elgg_view($item->view,array(
					'item' => $item
				));
			}
		}
		if (get_plugin_setting('activitytype', 'riverdashboard') == 'classic'){
			return elgg_view('river/item/wrapper_classic',array(
				'item' => $item,
				'body' => $body
			));
		}else{
			return elgg_view('river/item/wrapper',array(
				'item' => $item,
				'body' => $body
			));
		}
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
	$type = '', $subtype = '', $action_type = '', $limit = 20, $posted_min = 0, $posted_max = 0, $pagination = true, $chronological = true) {

	// Get input from outside world and sanitise it
	$offset = (int) get_input('offset',0);

	// Get the correct function
	if($chronological == true){
		$riveritems = get_river_items($subject_guid,$object_guid,$subject_relationship,$type,$subtype,$action_type,($limit + 1),$offset,$posted_min,$posted_max);
	}else{
		$riveritems = elgg_get_river_items($subject_guid,$object_guid,$subject_relationship,$type,$subtype,$action_type,($limit + 1),$offset,$posted_min,$posted_max);
	}
	
	// Get river items, if they exist
	if ($riveritems) {

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
 * Update last_action on the given entity.
 *
 * @param int $guid Entity annotation|relationship action carried out on
 * @param int $posted Timestamp of last action
 **/
function update_entity_last_action($guid, $posted){
	global $CONFIG;
	if(!$posted)
		$posted = time();
	$guid = (int)$guid;
	if($guid){
		//now add to the river updated table
		$query = update_data("UPDATE {$CONFIG->dbprefix}entities SET last_action = {$posted} WHERE guid = {$guid}");
		if($query)
			return true;
		else
			return false;
	}else{
		return false;
	}
}
/**
 * This function has been added here until we decide if it is going to roll into core or not
 * Add access restriction sql code to a given query.
 * Note that if this code is executed in privileged mode it will return blank.
 * @TODO: DELETE once Query classes are fully integrated
 *
 * @param string $table_prefix Optional table. prefix for the access code.
 * @param int $owner
 */
function get_access_sql_suffix_new($table_prefix_one = '', $table_prefix_two = '', $owner = null) {
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

		$friends_bit = '('.$friends_bit.') OR ';

		if ((isset($CONFIG->user_block_and_filter_enabled)) && ($CONFIG->user_block_and_filter_enabled)) {
			// check to see if the user is in the entity owner's block list
			// or if the entity owner is in the user's filter list
			// if so, disallow access
			$enemies_bit = get_annotation_sql('elgg_block_list', "{$table_prefix_one}owner_guid", $owner, false);
			$enemies_bit = '('
				. $enemies_bit
				. '	AND ' . get_annotation_sql('elgg_filter_list', $owner, "{$table_prefix_one}owner_guid", false)
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

	if (!$ENTITY_SHOW_HIDDEN_OVERRIDE)
		$sql .= " and {$table_prefix_two}enabled='yes'";
	return '('.$sql.')';
}