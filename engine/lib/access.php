<?php
/**
 * Primary function for Elgg's entity and metadata access systems.
 *
 * Access is generally saved in the database as access_id.  This corresponds to
 * one of the ACCESS_* constants defined in {@link elgglib.php}, or the ID of an
 * access collection.
 *
 * @package Elgg.Core
 * @subpackage Access
 * @link http://docs.elgg.org/Access
 */

/**
 * Return a string of access_ids for $user_id appropriate for inserting into an SQL IN clause.
 *
 * @uses get_access_array
 *
 * @return string A list of access collections suitable for injection in an SQL call
 * @link http://docs.elgg.org/Access
 * @see get_access_array()
 *
 * @param int  $user_id User ID; defaults to currently logged in user
 * @param int  $site_id Site ID; defaults to current site
 * @param bool $flush   If set to true, will refresh the access list from the database
 *
 * @return string
 */
function get_access_list($user_id = 0, $site_id = 0, $flush = false) {
	global $CONFIG, $init_finished;
	static $access_list;

	if (!isset($access_list) || !$init_finished) {
		$access_list = array();
	}

	if ($user_id == 0) {
		$user_id = elgg_get_logged_in_user_guid();
	}

	if (($site_id == 0) && (isset($CONFIG->site_id))) {
		$site_id = $CONFIG->site_id;
	}
	$user_id = (int) $user_id;
	$site_id = (int) $site_id;

	if (isset($access_list[$user_id]) && $flush == false) {
		return $access_list[$user_id];
	}

	$access_list[$user_id] = "(" . implode(",", get_access_array($user_id, $site_id, $flush)) . ")";

	return $access_list[$user_id];
}

/**
 * Returns an array of access IDs a user is permitted to see.
 *
 * Can be overridden with the access:collections:read, user plugin hook.
 *
 * @param int     $user_id User ID; defaults to currently logged in user
 * @param int     $site_id Site ID; defaults to current site
 * @param boolean $flush   If set to true, will refresh the access list from the database
 *
 * @return array An array of access collections ids
 * @see get_access_list()
 */
function get_access_array($user_id = 0, $site_id = 0, $flush = false) {
	global $CONFIG, $init_finished;

	// @todo everything from the db is cached.
	// this cache might be redundant. But cache is flushed on every db write.
	static $access_array;

	if (!isset($access_array) || (!isset($init_finished)) || (!$init_finished)) {
		$access_array = array();
	}

	if ($user_id == 0) {
		$user_id = elgg_get_logged_in_user_guid();
	}

	if (($site_id == 0) && (isset($CONFIG->site_guid))) {
		$site_id = $CONFIG->site_guid;
	}

	$user_id = (int) $user_id;
	$site_id = (int) $site_id;

	if (empty($access_array[$user_id]) || $flush == true) {
		$tmp_access_array = array(ACCESS_PUBLIC);

		// The following can only return sensible data if the user is logged in.
		if (elgg_is_logged_in()) {
			$tmp_access_array[] = ACCESS_LOGGED_IN;

			// Get ACL memberships
			$query = "SELECT am.access_collection_id"
				. " FROM {$CONFIG->dbprefix}access_collection_membership am"
				. " LEFT JOIN {$CONFIG->dbprefix}access_collections ag ON ag.id = am.access_collection_id"
				. " WHERE am.user_guid = {$user_id} AND (ag.site_guid = {$site_id} OR ag.site_guid = 0)";

			if ($collections = get_data($query)) {
				foreach ($collections as $collection) {
					if (!empty($collection->access_collection_id)) {
						$tmp_access_array[] = $collection->access_collection_id;
					}
				}
			}

			// Get ACLs owned.
			$query = "SELECT ag.id FROM {$CONFIG->dbprefix}access_collections ag ";
			$query .= "WHERE ag.owner_guid = {$user_id} AND (ag.site_guid = {$site_id} OR ag.site_guid = 0)";

			if ($collections = get_data($query)) {
				foreach ($collections as $collection) {
					if (!empty($collection->id)) {
						$tmp_access_array[] = $collection->id;
					}
				}
			}

			$ignore_access = elgg_check_access_overrides($user_id);

			if ($ignore_access == true) {
				$tmp_access_array[] = ACCESS_PRIVATE;
			}

			$access_array[$user_id] = $tmp_access_array;
		} else {
			// No user id logged in so we can only access public info
			$tmp_return = $tmp_access_array;
		}

	} else {
		$tmp_access_array = $access_array[$user_id];
	}

	$options = array('user_id' => $user_id, 'site_id' => $site_id);
	return elgg_trigger_plugin_hook('access:collections:read', 'user', $options, $tmp_access_array);
}

/**
 * Gets the default access permission.
 *
 * This returns the default access level for the site or optionally for the user.
 *
 * @param ElggUser $user Get the user's default access. Defaults to logged in user.
 *
 * @return int default access id (see ACCESS defines in elgglib.php)
 * @link http://docs.elgg.org/Access
 */
function get_default_access(ElggUser $user = null) {
	global $CONFIG;

	if (!$CONFIG->allow_user_default_access) {
		return $CONFIG->default_access;
	}

	if (!($user) && (!$user = elgg_get_logged_in_user_entity())) {
		return $CONFIG->default_access;
	}

	if (false !== ($default_access = $user->getPrivateSetting('elgg_default_access'))) {
		return $default_access;
	} else {
		return $CONFIG->default_access;
	}
}

/**
 * Allow disabled entities and metadata to be returned by getter functions
 *
 * @todo Replace this with query object!
 * @global bool $ENTITY_SHOW_HIDDEN_OVERRIDE
 * @access private
 */
$ENTITY_SHOW_HIDDEN_OVERRIDE = false;

/**
 * Show or hide disabled entities.
 *
 * @access private
 *
 * @param bool $show_hidden Show disabled entities.
 *
 * @return void
 */
function access_show_hidden_entities($show_hidden) {
	global $ENTITY_SHOW_HIDDEN_OVERRIDE;
	$ENTITY_SHOW_HIDDEN_OVERRIDE = $show_hidden;
}

/**
 * Return current status of showing disabled entities.
 *
 * @access private
 * @return bool
 */
function access_get_show_hidden_status() {
	global $ENTITY_SHOW_HIDDEN_OVERRIDE;
	return $ENTITY_SHOW_HIDDEN_OVERRIDE;
}

/**
 * Add annotation restriction
 *
 * Returns an SQL fragment that is true (or optionally false) if the given user has
 * added an annotation with the given name to the given entity.
 *
 * @todo This is fairly generic so perhaps it could be moved to annotations.php
 *
 * @param string  $annotation_name Name of the annotation
 * @param string  $entity_guid     SQL GUID of entity the annotation is attached to.
 * @param string  $owner_guid      SQL string that evaluates to the GUID of the annotation owner
 * @param boolean $exists          If true, returns BOOL if the annotation exists
 *
 * @return string An SQL fragment suitable for inserting into a WHERE clause
 * @todo Document and maybe even remove.  At least rename to something that makes sense.
 */
function get_annotation_sql($annotation_name, $entity_guid, $owner_guid, $exists) {
	global $CONFIG;

	if ($exists) {
		$not = '';
	} else {
		$not = 'NOT';
	}

	$sql = <<<END
$not EXISTS (SELECT * FROM {$CONFIG->dbprefix}annotations a
INNER JOIN {$CONFIG->dbprefix}metastrings ms ON (a.name_id = ms.id)
WHERE ms.string = '$annotation_name'
AND a.entity_guid = $entity_guid
AND a.owner_guid = $owner_guid)
END;
	return $sql;
}

/**
 * Returns the SQL where clause for a table with a access_id and enabled columns.
 *
 * This handles returning where clauses for ACCESS_FRIENDS, and the currently
 * unused block and filter lists.
 *
 * @warning If an admin is logged in or {@link elgg_set_ignore_access()} is true,
 * this will return blank.
 *
 * @param string $table_prefix Optional table. prefix for the access code.
 * @param int    $owner        The guid to check access for. Defaults to logged in user.
 *
 * @return string The SQL for a where clause
 * @access private
 */
function get_access_sql_suffix($table_prefix = '', $owner = null) {
	global $ENTITY_SHOW_HIDDEN_OVERRIDE, $CONFIG;

	$sql = "";
	$friends_bit = "";
	$enemies_bit = "";

	if ($table_prefix) {
			$table_prefix = sanitise_string($table_prefix) . ".";
	}

	if (!isset($owner)) {
		$owner = elgg_get_logged_in_user_guid();
	}

	if (!$owner) {
		$owner = -1;
	}

	$ignore_access = elgg_check_access_overrides($owner);
	$access = get_access_list($owner);

	if ($ignore_access) {
		$sql = " (1 = 1) ";
	} else if ($owner != -1) {
		$friends_bit = "{$table_prefix}access_id = " . ACCESS_FRIENDS . "
			AND {$table_prefix}owner_guid IN (
				SELECT guid_one FROM {$CONFIG->dbprefix}entity_relationships
				WHERE relationship='friend' AND guid_two=$owner
			)";

		$friends_bit = '(' . $friends_bit . ') OR ';

		if ((isset($CONFIG->user_block_and_filter_enabled)) && ($CONFIG->user_block_and_filter_enabled)) {
			// check to see if the user is in the entity owner's block list
			// or if the entity owner is in the user's filter list
			// if so, disallow access
			$enemies_bit = get_annotation_sql('elgg_block_list', "{$table_prefix}owner_guid", $owner, false);
			$enemies_bit = '('
				. $enemies_bit
				. '	AND ' . get_annotation_sql('elgg_filter_list', $owner, "{$table_prefix}owner_guid", false)
			. ')';
		}
	}

	if (empty($sql)) {
		$sql = " $friends_bit ({$table_prefix}access_id IN {$access}
			OR ({$table_prefix}owner_guid = {$owner})
			OR (
				{$table_prefix}access_id = " . ACCESS_PRIVATE . "
				AND {$table_prefix}owner_guid = $owner
			)
		)";
	}

	if ($enemies_bit) {
		$sql = "$enemies_bit AND ($sql)";
	}

	if (!$ENTITY_SHOW_HIDDEN_OVERRIDE) {
		$sql .= " and {$table_prefix}enabled='yes'";
	}

	return '(' . $sql . ')';
}

/**
 * Can $user access $entity.
 *
 * @warning If a logged in user doesn't have access to an entity, the
 * core engine will not load that entity.
 *
 * @tip This is mostly useful for checking if a 3rd user has access
 * to an entity that is currently loaded.
 *
 * @param ElggEntity $entity The entity to check access for.
 * @param ElggUser   $user   Optionally user to check access for. Defaults to
 *                           logged in user (which doesn't make sense).
 *
 * @return boolean True if the user can access the entity
 * @link http://docs.elgg.org/Access
 */
function has_access_to_entity($entity, $user = null) {
	global $CONFIG;

	if (!isset($user)) {
		$access_bit = get_access_sql_suffix("e");
	} else {
		$access_bit = get_access_sql_suffix("e", $user->getGUID());
	}

	$query = "SELECT guid from {$CONFIG->dbprefix}entities e WHERE e.guid = " . $entity->getGUID();
	// Add access controls
	$query .= " AND " . $access_bit;
	if (get_data($query)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Returns an array of access permissions that the user is allowed to save objects with.
 * Permissions are of the form ('id' => 'Description')
 *
 * @param int  $user_id The user's GUID.
 * @param int  $site_id The current site.
 * @param bool $flush   If this is set to true, this will ignore any cached version
 *
 * @return array List of access permissions
 * @link http://docs.elgg.org/Access
 */
function get_write_access_array($user_id = 0, $site_id = 0, $flush = false) {
	global $CONFIG;
	//@todo this is probably not needed since caching happens at the DB level.
	static $access_array;

	if ($user_id == 0) {
		$user_id = elgg_get_logged_in_user_guid();
	}

	if (($site_id == 0) && (isset($CONFIG->site_id))) {
		$site_id = $CONFIG->site_id;
	}

	$user_id = (int) $user_id;
	$site_id = (int) $site_id;

	if (empty($access_array[$user_id]) || $flush == true) {
		$query = "SELECT ag.* FROM {$CONFIG->dbprefix}access_collections ag ";
		$query .= " WHERE (ag.site_guid = {$site_id} OR ag.site_guid = 0)";
		$query .= " AND (ag.owner_guid = {$user_id})";
		$query .= " AND ag.id >= 3";

		$tmp_access_array = array(
									ACCESS_PRIVATE => elgg_echo("PRIVATE"),
									ACCESS_FRIENDS => elgg_echo("access:friends:label"),
									ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
									ACCESS_PUBLIC => elgg_echo("PUBLIC")
								);
		if ($collections = get_data($query)) {
			foreach ($collections as $collection) {
				$tmp_access_array[$collection->id] = $collection->name;
			}
		}

		$access_array[$user_id] = $tmp_access_array;
	} else {
		$tmp_access_array = $access_array[$user_id];
	}

	$options = array('user_id' => $user_id, 'site_id' => $site_id);
	$tmp_access_array = elgg_trigger_plugin_hook('access:collections:write', 'user',
		$options, $tmp_access_array);

	return $tmp_access_array;
}

/**
 * Creates a new access collection.
 *
 * Access colletions allow plugins and users to create granular access
 * for entities.
 *
 * @internal Access collections are stored in the access_collections table.
 * Memberships to collections are in access_collections_membership.
 *
 * @param string $name       The name of the collection.
 * @param int    $owner_guid The GUID of the owner (default: currently logged in user).
 * @param int    $site_guid  The GUID of the site (default: current site).
 *
 * @return int|false Depending on success (the collection ID if successful).
 * @link http://docs.elgg.org/Access/Collections
 * @see update_access_collection()
 * @see delete_access_collection()
 */
function create_access_collection($name, $owner_guid = 0, $site_guid = 0) {
	global $CONFIG;

	$name = trim($name);
	if (empty($name)) {
		return false;
	}

	if ($owner_guid == 0) {
		$owner_guid = elgg_get_logged_in_user_guid();
	}
	if (($site_guid == 0) && (isset($CONFIG->site_guid))) {
		$site_guid = $CONFIG->site_guid;
	}
	$name = sanitise_string($name);

	$q = "INSERT INTO {$CONFIG->dbprefix}access_collections
		SET name = '{$name}',
			owner_guid = {$owner_guid},
			site_guid = {$site_guid}";
	if (!$id = insert_data($q)) {
		return false;
	}

	$params = array(
		'collection_id' => $id
	);

	if (!elgg_trigger_plugin_hook('access:collections:addcollection', 'collection', $params, true)) {
		return false;
	}

	return $id;
}

/**
 * Updates the membership in an access collection.
 *
 * @warning Expects a full list of all members that should
 * be part o the access collection
 *
 * @note This will run all hooks associated with adding or removing
 * members to access collections.
 *
 * @param int   $collection_id The ID of the collection.
 * @param array $members       Array of member GUIDs
 *
 * @return true|false Depending on success
 * @link http://docs.elgg.org/Access/Collections
 * @see add_user_to_access_collection()
 * @see remove_user_from_access_collection()
 */
function update_access_collection($collection_id, $members) {
	global $CONFIG;

	$collection_id = (int) $collection_id;
	$members = (is_array($members)) ? $members : array();

	$collections = get_write_access_array();

	if (array_key_exists($collection_id, $collections)) {
		$cur_members = get_members_of_access_collection($collection_id, true);
		$cur_members = (is_array($cur_members)) ? $cur_members : array();

		$remove_members = array_diff($cur_members, $members);
		$add_members = array_diff($members, $cur_members);

		$params = array(
			'collection_id' => $collection_id,
			'members' => $members,
			'add_members' => $add_members,
			'remove_members' => $remove_members
		);

		foreach ($add_members as $guid) {
			add_user_to_access_collection($guid, $collection_id);
		}

		foreach ($remove_members as $guid) {
			remove_user_from_access_collection($guid, $collection_id);
		}

		return true;
	}

	return false;
}

/**
 * Deletes a specified access collection and its membership.
 *
 * @param int $collection_id The collection ID
 *
 * @return bool
 * @link http://docs.elgg.org/Access/Collections
 * @see create_access_collection()
 * @see update_access_collection()
 */
function delete_access_collection($collection_id) {
	$collection_id = (int) $collection_id;
	$collections = get_write_access_array(null, null, TRUE);
	$params = array('collection_id' => $collection_id);

	if (!elgg_trigger_plugin_hook('access:collections:deletecollection', 'collection', $params, true)) {
		return false;
	}

	if (array_key_exists($collection_id, $collections)) {
		global $CONFIG;
		$query = "delete from {$CONFIG->dbprefix}access_collection_membership"
			. " where access_collection_id = {$collection_id}";
		delete_data($query);

		$query = "delete from {$CONFIG->dbprefix}access_collections where id = {$collection_id}";
		delete_data($query);
		return true;
	} else {
		return false;
	}

}

/**
 * Get a specified access collection
 *
 * @note This doesn't return the members of an access collection,
 * just the database row of the actual collection.
 *
 * @param int $collection_id The collection ID
 *
 * @return object|false
 */
function get_access_collection($collection_id) {
	global $CONFIG;
	$collection_id = (int) $collection_id;

	$query = "SELECT * FROM {$CONFIG->dbprefix}access_collections WHERE id = {$collection_id}";
	$get_collection = get_data_row($query);

	return $get_collection;
}

/**
 * Adds a user to an access collection.
 *
 * Emits the access:collections:add_user, collection plugin hook.
 *
 * @param int $user_guid     The GUID of the user to add
 * @param int $collection_id The ID of the collection to add them to
 *
 * @return true|false Depending on success
 * @link http://docs.elgg.org/Access/Collections
 * @see update_access_collection()
 * @see remove_user_from_access_collection()
 */
function add_user_to_access_collection($user_guid, $collection_id) {
	$collection_id = (int) $collection_id;
	$user_guid = (int) $user_guid;
	$collections = get_write_access_array();

	if (!($collection = get_access_collection($collection_id))) {
		return false;
	}

	$user = get_user($user_guid);
	if (!$user) {
		return false;
	}

	// to add someone to a collection, the user must be a member of the collection or
	// no one must own it
	if ((array_key_exists($collection_id, $collections) || $collection->owner_guid == 0)) {
		$result = true;
	} else {
		$result = false;
	}
	
	$params = array(
		'collection_id' => $collection_id,
		'collection' => $collection,
		'user_guid' => $user_guid
	);

	$result = elgg_trigger_plugin_hook('access:collections:add_user', 'collection', $params, $result);
	if ($result == false) {
		return false;
	}

	try {
		global $CONFIG;
		$query = "insert into {$CONFIG->dbprefix}access_collection_membership"
				. " set access_collection_id = {$collection_id}, user_guid = {$user_guid}";
		insert_data($query);
	} catch (DatabaseException $e) {
		// nothing.
		return false;
	}

	return true;
}

/**
 * Removes a user from an access collection.
 *
 * Emits the access:collections:remove_user, collection plugin hook.
 *
 * @param int $user_guid     The user GUID
 * @param int $collection_id The access collection ID
 *
 * @return true|false Depending on success
 */
function remove_user_from_access_collection($user_guid, $collection_id) {
	$collection_id = (int) $collection_id;
	$user_guid = (int) $user_guid;
	$collections = get_write_access_array();
	$user = $user = get_user($user_guid);

	if (!($collection = get_access_collection($collection_id))) {
		return false;
	}

	if ((array_key_exists($collection_id, $collections) || $collection->owner_guid == 0) && $user) {
		global $CONFIG;
		$params = array(
			'collection_id' => $collection_id,
			'user_guid' => $user_guid
		);

		if (!elgg_trigger_plugin_hook('access:collections:remove_user', 'collection', $params, true)) {
			return false;
		}

		delete_data("delete from {$CONFIG->dbprefix}access_collection_membership "
			. "where access_collection_id = {$collection_id} and user_guid = {$user_guid}");

		return true;

	}

	return false;
}

/**
 * Returns an array of database row objects of the access collections owned by $owner_guid.
 *
 * @param int $owner_guid The entity guid
 * @param int $site_guid  The GUID of the site (default: current site).
 *
 * @return array|false
 * @see add_access_collection()
 * @see get_members_of_access_collection()
 * @link http://docs.elgg.org/Access/Collections
 */
function get_user_access_collections($owner_guid, $site_guid = 0) {
	global $CONFIG;
	$owner_guid = (int) $owner_guid;
	$site_guid = (int) $site_guid;

	if (($site_guid == 0) && (isset($CONFIG->site_guid))) {
		$site_guid = $CONFIG->site_guid;
	}

	$query = "SELECT * FROM {$CONFIG->dbprefix}access_collections
			WHERE owner_guid = {$owner_guid}
			AND site_guid = {$site_guid}";

	$collections = get_data($query);

	return $collections;
}

/**
 * Get all of members of an access collection
 *
 * @param int  $collection The collection's ID
 * @param bool $idonly     If set to true, will only return the members' GUIDs (default: false)
 *
 * @return array ElggUser guids or entities if successful, false if not
 * @see add_user_to_access_collection()
 * @see http://docs.elgg.org/Access/Collections
 */
function get_members_of_access_collection($collection, $idonly = FALSE) {
	global $CONFIG;
	$collection = (int)$collection;

	if (!$idonly) {
		$query = "SELECT e.* FROM {$CONFIG->dbprefix}access_collection_membership m"
			. " JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.user_guid"
			. " WHERE m.access_collection_id = {$collection}";
		$collection_members = get_data($query, "entity_row_to_elggstar");
	} else {
		$query = "SELECT e.guid FROM {$CONFIG->dbprefix}access_collection_membership m"
			. " JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.user_guid"
			. " WHERE m.access_collection_id = {$collection}";
		$collection_members = get_data($query);
		if (!$collection_members) {
			return FALSE;
		}
		foreach ($collection_members as $key => $val) {
			$collection_members[$key] = $val->guid;
		}
	}

	return $collection_members;
}

/**
 * Displays a user's access collections, using the core/friends/collections view
 *
 * @param int $owner_guid The GUID of the owning user
 *
 * @return string A formatted rendition of the collections
 * @todo Move to the friends/collection.php page.
 */
function elgg_view_access_collections($owner_guid) {
	if ($collections = get_user_access_collections($owner_guid)) {
		foreach ($collections as $key => $collection) {
			$collections[$key]->members = get_members_of_access_collection($collection->id, true);
			$collections[$key]->entities = get_user_friends($owner_guid, "", 9999);
		}
	}

	return elgg_view('core/friends/collections', array('collections' => $collections));
}

/**
 * Return entities based upon access id.
 *
 * @param array $options Any options accepted by {@link elgg_get_entities()} and:
 * 	access_id => int The access ID of the entity.
 *
 * @see elgg_get_entities()
 * @return array
 * @since 1.7.0
 */
function elgg_get_entities_from_access_id(array $options = array()) {
	// restrict the resultset to access collection provided
	if (!isset($options['access_id'])) {
		return FALSE;
	}

	// @todo add support for an array of collection_ids
	$where = "e.access_id = '{$options['access_id']}'";
	if (isset($options['wheres'])) {
		if (is_array($options['wheres'])) {
			$options['wheres'][] = $where;
		} else {
			$options['wheres'] = array($options['wheres'], $where);
		}
	} else {
		$options['wheres'] = array($where);
	}

	// return entities with the desired options
	return elgg_get_entities($options);
}

/**
 * Lists entities from an access collection
 *
 * @param array $options See elgg_list_entities() and elgg_get_entities_from_access_id()
 * 
 * @see elgg_list_entities()
 * @see elgg_get_entities_from_access_id()
 * 
 * @return str
 */
function elgg_list_entities_from_access_id(array $options = array()) {
	return elgg_list_entities($options, 'elgg_get_entities_from_access_id');
}

/**
 * Return the name of an ACCESS_* constant or a access collection,
 * but only if the user has write access on that ACL.
 *
 * @warning This function probably doesn't work how it's meant to.
 *
 * @param int $entity_accessid The entity's access id
 *
 * @return string e.g. Public, Private etc
 * @since 1.7.0
 * @todo I think this probably wants get_access_array() instead of get_write_access_array(),
 * but those two functions return different types of arrays.
 */
function get_readable_access_level($entity_accessid) {
	$access = (int) $entity_accessid;

	//get the access level for object in readable string
	$options = get_write_access_array();

	//@todo Really?  Use array_key_exists()
	foreach ($options as $key => $option) {
		if ($key == $access) {
			$entity_acl = htmlentities($option, ENT_QUOTES, 'UTF-8');
			return $entity_acl;
			break;
		}
	}
	return false;
}

/**
 * Set if entity access system should be ignored.
 *
 * The access system will not return entities in any getter
 * functions if the user doesn't have access.
 *
 * @internal For performance reasons this is done at the database level.
 *
 * @tip Use this to access entities in automated scripts
 * when no user is logged in.
 *
 * @warning This will not show disabled entities.  Use {@link $ENTITY_SHOW_HIDDEN_OVERRIDE}
 * for that.
 *
 * @param bool $ignore If true, disables all access checks.
 *
 * @return bool Previous ignore_access setting.
 * @since 1.7.0
 * @see http://docs.elgg.org/Access/IgnoreAccess
 * @see elgg_get_ignore_access()
 */
function elgg_set_ignore_access($ignore = true) {
	$elgg_access = elgg_get_access_object();
	return $elgg_access->setIgnoreAccess($ignore);
}

/**
 * Get current ignore access setting.
 *
 * @return bool
 * @since 1.7.0
 * @see http://docs.elgg.org/Access/IgnoreAccess
 * @see elgg_set_ignore_access()
 */
function elgg_get_ignore_access() {
	return elgg_get_access_object()->getIgnoreAccess();
}

/**
 * Decides if the access system is being ignored.
 *
 * The access system can be ignored if 1) an admin user is logged in
 * or 2) {@link elgg_set_ignore_access()} was called with true.
 *
 * @param mixed $user_guid The user to check against. Defaults to logged in.
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_check_access_overrides($user_guid = null) {
	if (!$user_guid || $user_guid <= 0) {
		$is_admin = false;
	} else {
		$is_admin = elgg_is_admin_user($user_guid);
	}

	return ($is_admin || elgg_get_ignore_access());
}

/**
 * Returns the ElggAccess object.
 *
 * This is used to
 *
 * @return ElggAccess
 * @since 1.7.0
 * @access private
 */
function elgg_get_access_object() {
	static $elgg_access;

	if (!$elgg_access) {
		$elgg_access = new ElggAccess();
	}

	return $elgg_access;
}

/**
 * A flag to set if Elgg's access initialization is finished.
 *
 * @global bool $init_finished
 * @access private
 * @todo investigate why this is needed
 */
$init_finished = false;

/**
 * A quick and dirty way to make sure the access permissions have been correctly set up
 *
 * @elgg_event_handler init system
 * @todo Invesigate
 *
 * @return void
 */
function access_init() {
	global $init_finished;
	$init_finished = true;
}

/**
 * Check if the access system should be overridden.
 *
 * Allows admin users and calls after {@link elgg_set_ignore_access} to
 * by pass the access system.
 *
 * @return true|null
 * @since 1.7.0
 * @elgg_event_handler permissions_check all
 */
function elgg_override_permissions_hook() {
	$user_guid = elgg_get_logged_in_user_guid();

	// check for admin
	if ($user_guid && elgg_is_admin_user($user_guid)) {
		return true;
	}

	// check access overrides
	if ((elgg_check_access_overrides($user_guid))) {
		return true;
	}

	// consult other hooks
	return NULL;
}

// This function will let us know when 'init' has finished
elgg_register_event_handler('init', 'system', 'access_init', 9999);

// For overrided permissions
elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions_hook');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions_hook');
