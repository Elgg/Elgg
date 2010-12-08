<?php
/**
 * Elgg relationships.
 * Stub containing relationship functions, making import and export easier.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Relationship
 */

/**
 * Convert a database row to a new ElggRelationship
 *
 * @param stdClass $row Database row from the relationship table
 *
 * @return stdClass or ElggMetadata
 */
function row_to_elggrelationship($row) {
	if (!($row instanceof stdClass)) {
		return $row;
	}

	return new ElggRelationship($row);
}

/**
 * Return a relationship.
 *
 * @param int $id The ID of a relationship
 *
 * @return mixed
 */
function get_relationship($id) {
	global $CONFIG;

	$id = (int)$id;

	$query = "SELECT * from {$CONFIG->dbprefix}entity_relationships where id=$id";
	return row_to_elggrelationship(get_data_row($query));
}

/**
 * Delete a specific relationship.
 *
 * @param int $id The relationship ID
 *
 * @return bool
 */
function delete_relationship($id) {
	global $CONFIG;

	$id = (int)$id;

	$relationship = get_relationship($id);

	if (elgg_trigger_event('delete', 'relationship', $relationship)) {
		return delete_data("delete from {$CONFIG->dbprefix}entity_relationships where id=$id");
	}

	return FALSE;
}

/**
 * Define an arbitrary relationship between two entities.
 * This relationship could be a friendship, a group membership or a site membership.
 *
 * This function lets you make the statement "$guid_one is a $relationship of $guid_two".
 *
 * @param int    $guid_one     First GUID
 * @param string $relationship Relationship name
 * @param int    $guid_two     Second GUID
 *
 * @return bool
 */
function add_entity_relationship($guid_one, $relationship, $guid_two) {
	global $CONFIG;

	$guid_one = (int)$guid_one;
	$relationship = sanitise_string($relationship);
	$guid_two = (int)$guid_two;
	$time = time();

	// Check for duplicates
	if (check_entity_relationship($guid_one, $relationship, $guid_two)) {
		return false;
	}

	$result = insert_data("INSERT into {$CONFIG->dbprefix}entity_relationships
		(guid_one, relationship, guid_two, time_created)
		values ($guid_one, '$relationship', $guid_two, $time)");

	if ($result !== false) {
		$obj = get_relationship($result);
		if (elgg_trigger_event('create', $relationship, $obj)) {
			return true;
		} else {
			delete_relationship($result);
		}
	}

	return false;
}

/**
 * Determine if a relationship between two entities exists
 * and returns the relationship object if it does
 *
 * @param int    $guid_one     The GUID of the entity "owning" the relationship
 * @param string $relationship The type of relationship
 * @param int    $guid_two     The GUID of the entity the relationship is with
 *
 * @return object|false Depending on success
 */
function check_entity_relationship($guid_one, $relationship, $guid_two) {
	global $CONFIG;

	$guid_one = (int)$guid_one;
	$relationship = sanitise_string($relationship);
	$guid_two = (int)$guid_two;

	$query = "SELECT * FROM {$CONFIG->dbprefix}entity_relationships
		WHERE guid_one=$guid_one
			AND relationship='$relationship'
			AND guid_two=$guid_two limit 1";

	$row = $row = get_data_row($query);
	if ($row) {
		return $row;
	}

	return false;
}

/**
 * Remove an arbitrary relationship between two entities.
 *
 * @param int    $guid_one     First GUID
 * @param string $relationship Relationship name
 * @param int    $guid_two     Second GUID
 *
 * @return bool
 */
function remove_entity_relationship($guid_one, $relationship, $guid_two) {
	global $CONFIG;

	$guid_one = (int)$guid_one;
	$relationship = sanitise_string($relationship);
	$guid_two = (int)$guid_two;

	$obj = check_entity_relationship($guid_one, $relationship, $guid_two);
	if ($obj == false) {
		return false;
	}

	if (elgg_trigger_event('delete', $relationship, $obj)) {
		$query = "DELETE from {$CONFIG->dbprefix}entity_relationships
			where guid_one=$guid_one
			and relationship='$relationship'
			and guid_two=$guid_two";

		return delete_data($query);
	} else {
		return false;
	}
}

/**
 * Removes all arbitrary relationships originating from a particular entity
 *
 * @param int    $guid_one     The GUID of the entity
 * @param string $relationship The name of the relationship (optional)
 * @param bool   $inverse      Whether we're deleting inverse relationships (default false)
 * @param string $type         The type of entity to the delete to (defaults to all)
 *
 * @return bool Depending on success
 */
function remove_entity_relationships($guid_one, $relationship = "", $inverse = false, $type = '') {
	global $CONFIG;

	$guid_one = (int) $guid_one;

	if (!empty($relationship)) {
		$relationship = sanitise_string($relationship);
		$where = "and er.relationship='$relationship'";
	} else {
		$where = "";
	}

	if (!empty($type)) {
		$type = sanitise_string($type);
		if (!$inverse) {
			$join = " join {$CONFIG->dbprefix}entities e on e.guid = er.guid_two ";
		} else {
			$join = " join {$CONFIG->dbprefix}entities e on e.guid = er.guid_one ";
			$where .= " and ";
		}
		$where .= " and e.type = '{$type}' ";
	} else {
		$join = "";
	}

	if (!$inverse) {
		$sql = "DELETE er from {$CONFIG->dbprefix}entity_relationships as er
			{$join}
			where guid_one={$guid_one} {$where}";

		return delete_data($sql);
	} else {
		$sql = "DELETE er from {$CONFIG->dbprefix}entity_relationships as er
			{$join} where
			guid_two={$guid_one} {$where}";

		return delete_data($sql);
	}
}

/**
 * Get all the relationships for a given guid.
 *
 * @param int  $guid                 The GUID of the relationship owner
 * @param bool $inverse_relationship Inverse relationship owners?
 *
 * @return mixed
 */
function get_entity_relationships($guid, $inverse_relationship = FALSE) {
	global $CONFIG;

	$guid = (int)$guid;

	$where = ($inverse_relationship ? "guid_two='$guid'" : "guid_one='$guid'");

	$query = "SELECT * from {$CONFIG->dbprefix}entity_relationships where {$where}";

	return get_data($query, "row_to_elggrelationship");
}


/**
 * Return entities matching a given query joining against a relationship.
 *
 * @param array $options Array in format:
 *
 * 	relationship => NULL|STR relationship
 *
 * 	relationship_guid => NULL|INT Guid of relationship to test
 *
 * 	inverse_relationship => BOOL Inverse the relationship
 *
 * @return array
 * @since 1.7.0
 */
function elgg_get_entities_from_relationship($options) {
	$defaults = array(
		'relationship' => NULL,
		'relationship_guid' => NULL,
		'inverse_relationship' => FALSE
	);

	$options = array_merge($defaults, $options);

	$clauses = elgg_get_entity_relationship_where_sql('e', $options['relationship'],
		$options['relationship_guid'], $options['inverse_relationship']);

	if ($clauses) {
		// merge wheres to pass to get_entities()
		if (isset($options['wheres']) && !is_array($options['wheres'])) {
			$options['wheres'] = array($options['wheres']);
		} elseif (!isset($options['wheres'])) {
			$options['wheres'] = array();
		}

		$options['wheres'] = array_merge($options['wheres'], $clauses['wheres']);

		// merge joins to pass to get_entities()
		if (isset($options['joins']) && !is_array($options['joins'])) {
			$options['joins'] = array($options['joins']);
		} elseif (!isset($options['joins'])) {
			$options['joins'] = array();
		}

		$options['joins'] = array_merge($options['joins'], $clauses['joins']);
	}

	return elgg_get_entities_from_metadata($options);
}

/**
 * Returns sql appropriate for relationship joins and wheres
 *
 * @todo add support for multiple relationships and guids.
 *
 * @param string $table                Entities table name
 * @param string $relationship         Relationship string
 * @param int    $relationship_guid    Entity guid to check
 * @param string $inverse_relationship Inverse relationship check?
 *
 * @return mixed
 * @since 1.7.0
 */
function elgg_get_entity_relationship_where_sql($table, $relationship = NULL,
$relationship_guid = NULL, $inverse_relationship = FALSE) {

	if ($relationship == NULL && $entity_guid == NULL) {
		return '';
	}

	global $CONFIG;

	$wheres = array();
	$joins = array();

	if ($inverse_relationship) {
		$joins[] = "JOIN {$CONFIG->dbprefix}entity_relationships r on r.guid_one = e.guid";
	} else {
		$joins[] = "JOIN {$CONFIG->dbprefix}entity_relationships r on r.guid_two = e.guid";
	}

	if ($relationship) {
		$wheres[] = "r.relationship = '" . sanitise_string($relationship) . "'";
	}

	if ($relationship_guid) {
		if ($inverse_relationship) {
			$wheres[] = "r.guid_two = '$relationship_guid'";
		} else {
			$wheres[] = "r.guid_one = '$relationship_guid'";
		}
	}

	if ($where_str = implode(' AND ', $wheres)) {

		return array('wheres' => array("($where_str)"), 'joins' => $joins);
	}

	return '';
}

/**
 * Return entities from relationships
 *
 * @deprecated 1.7 Use elgg_get_entities_from_relationship()
 *
 * @param string $relationship         The relationship type
 * @param int    $relationship_guid    The GUID of the relationship owner
 * @param bool   $inverse_relationship Invert relationship?
 * @param string $type                 Entity type
 * @param string $subtype              Entity subtype
 * @param int    $owner_guid           Entity owner GUID
 * @param string $order_by             Order by clause
 * @param int    $limit                Limit
 * @param int    $offset               Offset
 * @param bool   $count                Return a count instead of entities?
 * @param int    $site_guid            Site GUID
 *
 * @return mixed
 */
function get_entities_from_relationship($relationship, $relationship_guid,
$inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0) {

	elgg_deprecated_notice('get_entities_from_relationship() was deprecated by elgg_get_entities_from_relationship()!', 1.7);

	$options = array();

	$options['relationship'] = $relationship;
	$options['relationship_guid'] = $relationship_guid;
	$options['inverse_relationship'] = $inverse_relationship;

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_relationship($options);
}

/**
 * Returns a viewable list of entities by relationship
 *
 * @param array $options
 *
 * @see elgg_list_entities()
 * @see elgg_get_entities_from_relationship()
 *
 * @return string The viewable list of entities
 */
function elgg_list_entities_from_relationship(array $options = array()) {
	return elgg_list_entities($options, 'elgg_get_entities_from_relationship');
}

/**
 * @deprecated 1.8 Use elgg_list_entities_from_relationship()
 */
function list_entities_from_relationship($relationship, $relationship_guid,
$inverse_relationship = false, $type = ELGG_ENTITIES_ANY_VALUE,
$subtype = ELGG_ENTITIES_ANY_VALUE, $owner_guid = 0, $limit = 10,
$fullview = true, $listtypetoggle = false, $pagination = true) {

	elgg_deprecated_notice("list_entities_from_relationship was deprecated by elgg_list_entities_from_relationship()!", 1.8);
	return elgg_list_entities_from_relationship(array(
		'relationship' => $relationship,
		'relationship_guid' => $relationship_guid,
		'inverse_relationship' => $inverse_relationship,
		'types' => $type,
		'subtypes' => $subtype,
		'owner_guid' => $owner_guid,
		'limit' => $limit,
		'full_view' => $fullview,
		'list_type_toggle' => $listtypetoggle,
		'pagination' => $pagination,
	));
}

/**
 * Gets the number of entities by a the number of entities related to them in a particular way.
 * This is a good way to get out the users with the most friends, or the groups with the
 * most members.
 *
 * @param array $options An options array compatible with
 *                       elgg_get_entities_from_relationship()
 * @return array
 */
function elgg_get_entities_from_relationship_count(array $options = array()) {
	$options['selects'][] = "COUNT(e.guid) as total";
	$options['group_by'] = 'r.guid_two';
	$options['order_by'] = 'total desc';
	return elgg_get_entities_from_relationship($options);
}

/**
 * Gets the number of entities by a the number of entities related to them in a particular way.
 * This is a good way to get out the users with the most friends, or the groups with the
 * most members.
 *
 * @param string $relationship         The relationship eg "friends_of"
 * @param bool   $inverse_relationship Inverse relationship owners
 * @param string $type                 The type of entity (default: all)
 * @param string $subtype              The entity subtype (default: all)
 * @param int    $owner_guid           The owner of the entities (default: none)
 * @param int    $limit                Limit
 * @param int    $offset               Offset
 * @param bool   $count                Return a count instead of entities
 * @param int    $site_guid            Site GUID
 *
 * @return array|int|false An array of entities, or the number of entities, or false on failure
 */

function get_entities_by_relationship_count($relationship, $inverse_relationship = true, $type = "",
$subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $count = false, $site_guid = 0) {

	global $CONFIG;

	$relationship = sanitise_string($relationship);
	$inverse_relationship = (bool)$inverse_relationship;
	$type = sanitise_string($type);
	if ($subtype AND !$subtype = get_subtype_id($type, $subtype)) {
		return false;
	}
	$owner_guid = (int)$owner_guid;
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

	if ($inverse_relationship) {
		$on = 'e.guid = r.guid_two';
	} else {
		$on = 'e.guid = r.guid_one';
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

	if ($count) {
		$query = "SELECT count(distinct e.guid) as total ";
	} else {
		$query = "SELECT e.*, count(e.guid) as total ";
	}

	$query .= " from {$CONFIG->dbprefix}entity_relationships r
		JOIN {$CONFIG->dbprefix}entities e on {$on} where ";

	if (!empty($where)) {
		foreach ($where as $w) {
			$query .= " $w and ";
		}
	}
	$query .= get_access_sql_suffix("e");

	if (!$count) {
		$query .= " group by e.guid ";
		$query .= " order by total desc limit {$offset}, {$limit}";
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($count = get_data_row($query)) {
			return $count->total;
		}
	}

	return false;
}

/**
 * Displays a human-readable list of entities
 *
 * @param string $relationship         The relationship eg "friends_of"
 * @param bool   $inverse_relationship Inverse relationship owners
 * @param string $type                 The type of entity (eg 'object')
 * @param string $subtype              The entity subtype
 * @param int    $owner_guid           The owner (default: all)
 * @param int    $limit                The number of entities to display on a page
 * @param bool   $fullview             Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle       Whether or not to allow gallery view
 * @param bool   $pagination           Whether to display pagination (default: true)
 *
 * @return string The viewable list of entities
 */

function list_entities_by_relationship_count($relationship, $inverse_relationship = true,
$type = "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true,
$listtypetoggle = false, $pagination = true) {

	$limit = (int) $limit;
	$offset = (int) get_input('offset');
	$count = get_entities_by_relationship_count($relationship, $inverse_relationship,
		$type, $subtype, $owner_guid, 0, 0, true);
	$entities = get_entities_by_relationship_count($relationship, $inverse_relationship,
		$type, $subtype, $owner_guid, $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $listtypetoggle, $pagination);
}

/**
 * Gets the number of entities by a the number of entities related to
 * them in a particular way also constrained by metadata.
 *
 * @deprecated 1.8
 *
 * @param string $relationship         The relationship eg "friends_of"
 * @param int    $relationship_guid    The guid of the entity to use query
 * @param bool   $inverse_relationship Inverse relationship owner
 * @param String $meta_name            The metadata name
 * @param String $meta_value           The metadata value
 * @param string $type                 The type of entity (default: all)
 * @param string $subtype              The entity subtype (default: all)
 * @param int    $owner_guid           The owner of the entities (default: none)
 * @param int    $limit                Limit
 * @param int    $offset               Offset
 * @param bool   $count                Return a count instead of entities
 * @param int    $site_guid            Site GUID
 *
 * @return array|int|false An array of entities, or the number of entities, or false on failure
 */
function get_entities_from_relationships_and_meta($relationship, $relationship_guid,
$inverse_relationship = false, $meta_name = "", $meta_value = "", $type = "",
$subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $count = false, $site_guid = 0) {

	elgg_deprecated_notice('get_entities_from_relationship_and_meta() was deprecated by elgg_get_entities_from_relationship()!', 1.7);

	$options = array();

	$options['relationship'] = $relationship;
	$options['relationship_guid'] = $relationship_guid;
	$options['inverse_relationship'] = $inverse_relationship;

	if ($meta_value) {
		$options['values'] = $meta_value;
	}

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_relationship($options);
}

/**
 * Sets the URL handler for a particular relationship type
 *
 * @param string $function_name     The function to register
 * @param string $relationship_type The relationship type.
 *
 * @return bool Depending on success
 */
function register_relationship_url_handler($function_name, $relationship_type = "all") {
	global $CONFIG;

	if (!is_callable($function_name)) {
		return false;
	}

	if (!isset($CONFIG->relationship_url_handler)) {
		$CONFIG->relationship_url_handler = array();
	}

	$CONFIG->relationship_url_handler[$relationship_type] = $function_name;

	return true;
}

/**
 * Get the url for a given relationship.
 *
 * @param int $id Relationship ID
 *
 * @return string
 */
function get_relationship_url($id) {
	global $CONFIG;

	$id = (int)$id;

	if ($relationship = get_relationship($id)) {
		$view = elgg_get_viewtype();

		$guid = $relationship->guid_one;
		$type = $relationship->relationship;

		$url = "";

		$function = "";
		if (isset($CONFIG->relationship_url_handler[$type])) {
			$function = $CONFIG->relationship_url_handler[$type];
		}
		if (isset($CONFIG->relationship_url_handler['all'])) {
			$function = $CONFIG->relationship_url_handler['all'];
		}

		if (is_callable($function)) {
			$url = $function($relationship);
		}

		if ($url == "") {
			$nameid = $relationship->id;

			$url = elgg_get_site_url()  . "export/$view/$guid/relationship/$nameid/";
		}

		return $url;
	}

	return false;
}

/**** HELPER FUNCTIONS FOR RELATIONSHIPS OF TYPE 'ATTACHED' ****/
// @todo what is this?

/**
 * Function to determine if the object trying to attach to other, has already done so
 *
 * @param int $guid_one This is the target object
 * @param int $guid_two This is the object trying to attach to $guid_one
 *
 * @return bool
 **/
function already_attached($guid_one, $guid_two) {
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
**/
function get_attachments($guid, $type = "") {
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
**/
function remove_attachment($guid_one, $guid_two) {
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
**/
function make_attachment($guid_one, $guid_two) {
	if (!(already_attached($guid_one, $guid_two))) {
		if (add_entity_relationship($guid_one, "attached", $guid_two)) {
			return true;
		}
	}
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
 *
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
 * Handler called by trigger_plugin_hook on the "export" event.
 *
 * @param string $hook        export
 * @param string $entity_type all
 * @param mixed  $returnvalue Previous hook return value
 * @param array  $params      Parameters
 *
 * @elgg_event_handler export all
 * @return mixed
 */
function export_relationship_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	global $CONFIG;

	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
	}

	if (!is_array($returnvalue)) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
	}

	$guid = (int)$params['guid'];

	$result = get_entity_relationships($guid);

	if ($result) {
		foreach ($result as $r) {
			$returnvalue[] = $r->export();
		}
	}

	return $returnvalue;
}

/**
 * An event listener which will notify users based on certain events.
 *
 * @param string $event       Event name
 * @param string $object_type Object type
 * @param mixed  $object      Object
 *
 * @return bool
 */
function relationship_notification_hook($event, $object_type, $object) {
	global $CONFIG;

	if (
		($object instanceof ElggRelationship) &&
		($event == 'create') &&
		($object_type == 'friend')
	) {
		$user_one = get_entity($object->guid_one);
		$user_two = get_entity($object->guid_two);

		// Notify target user
		return notify_user($object->guid_two, $object->guid_one,
			elgg_echo('friend:newfriend:subject', array($user_one->name)),
			elgg_echo("friend:newfriend:body", array($user_one->name, $user_one->getURL()))
		);
	}
}

/** Register the import hook */
elgg_register_plugin_hook_handler("import", "all", "import_relationship_plugin_hook", 3);

/** Register the hook, ensuring entities are serialised first */
elgg_register_plugin_hook_handler("export", "all", "export_relationship_plugin_hook", 3);

/** Register event to listen to some events **/
elgg_register_event_handler('create', 'friend', 'relationship_notification_hook');
