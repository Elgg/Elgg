<?php
/**
 * Elgg access permissions
 * For users, objects, collections and all metadata
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Temporary class used to determing if access is being ignored
 */
class ElggAccess {
	/**
	 * Bypass Elgg's access control if true.
	 * @var bool
	 */
	private $ignore_access;

	/**
	 * Get current ignore access setting.
	 * @return bool
	 */
	public function get_ignore_access() {
		return $this->ignore_access;
	}

	/**
	 * Set ignore access.
	 *
	 * @param $ignore bool true || false to ignore
	 * @return bool Previous setting
	 */
	public function set_ignore_access($ignore = true) {
		$prev = $this->ignore_access;
		$this->ignore_access = $ignore;

		return $prev;
	}
}


/**
 * Return a string of access_ids for $user_id appropriate for inserting into an SQL IN clause.
 *
 * @uses get_access_array
 * @param int $user_id User ID; defaults to currently logged in user
 * @param int $site_id Site ID; defaults to current site
 * @param boolean $flush If set to true, will refresh the access list from the database
 * @return string A list of access collections suitable for injection in an SQL call
 */
function get_access_list($user_id = 0, $site_id = 0, $flush = false) {
	global $CONFIG, $init_finished;
	static $access_list;

	if (!isset($access_list) || !$init_finished) {
		$access_list = array();
	}

	if ($user_id == 0) {
		$user_id = get_loggedin_userid();
	}

	if (($site_id == 0) && (isset($CONFIG->site_id))) {
		$site_id = $CONFIG->site_id;
	}
	$user_id = (int) $user_id;
	$site_id = (int) $site_id;

	if (isset($access_list[$user_id])) {
		return $access_list[$user_id];
	}

	$access_list[$user_id] = "(" . implode(",", get_access_array($user_id, $site_id, $flush)) . ")";

	return $access_list[$user_id];
}

/**
 * Gets an array of access restrictions the given user is allowed to see on this site
 *
 * @param int $user_id User ID; defaults to currently logged in user
 * @param int $site_id Site ID; defaults to current site
 * @param boolean $flush If set to true, will refresh the access list from the database
 * @return array An array of access collections suitable for injection in an SQL call
 */
function get_access_array($user_id = 0, $site_id = 0, $flush = false) {
	global $CONFIG, $init_finished;

	// @todo everything from the db is cached.
	// this cache might be redundant.
	static $access_array;

	if (!isset($access_array) || (!isset($init_finished)) || (!$init_finished)) {
		$access_array = array();
	}

	if ($user_id == 0) {
		$user_id = get_loggedin_userid();
	}

	if (($site_id == 0) && (isset($CONFIG->site_guid))) {
		$site_id = $CONFIG->site_guid;
	}

	$user_id = (int) $user_id;
	$site_id = (int) $site_id;

	if (empty($access_array[$user_id]) || $flush == true) {
		$tmp_access_array = array(ACCESS_PUBLIC);
		if (isloggedin()) {
			$tmp_access_array[] = ACCESS_LOGGED_IN;

			// The following can only return sensible data if the user is logged in.

			// Get ACL memberships
			$query = "SELECT am.access_collection_id FROM {$CONFIG->dbprefix}access_collection_membership am ";
			$query .= " LEFT JOIN {$CONFIG->dbprefix}access_collections ag ON ag.id = am.access_collection_id ";
			$query .= " WHERE am.user_guid = {$user_id} AND (ag.site_guid = {$site_id} OR ag.site_guid = 0)";

			if ($collections = get_data($query)) {
				foreach($collections as $collection) {
					if (!empty($collection->access_collection_id)) {
						$tmp_access_array[] = $collection->access_collection_id;
					}
				}
			}

			// Get ACLs owned.
			$query = "SELECT ag.id FROM {$CONFIG->dbprefix}access_collections ag  ";
			$query .= " WHERE ag.owner_guid = {$user_id} AND (ag.site_guid = {$site_id} OR ag.site_guid = 0)";

			if ($collections = get_data($query)) {
				foreach($collections as $collection) {
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

	return trigger_plugin_hook('access:collections:read','user',array('user_id' => $user_id, 'site_id' => $site_id),$tmp_access_array);
}

/**
 * Gets the default access permission for new content
 *
 * @return int default access id (see ACCESS defines in elgglib.php)
 */
function get_default_access(ElggUser $user = null) {
	global $CONFIG;

	if (!$CONFIG->allow_user_default_access) {
		return $CONFIG->default_access;
	}

	if (!($user) && (!$user = get_loggedin_user())) {
		return $CONFIG->default_access;
	}

	if (false !== ($default_access = $user->getPrivateSetting('elgg_default_access'))) {
		return $default_access;
	} else {
		return $CONFIG->default_access;
	}
}

/**
 * Override the default behaviour and allow results to show hidden entities as well.
 * THIS IS A HACK.
 *
 * @todo Replace this with query object!
 */
$ENTITY_SHOW_HIDDEN_OVERRIDE = false;

/**
 * This will be replaced. Do not use in plugins!
 *
 * @param bool $show
 */
function access_show_hidden_entities($show_hidden) {
	global $ENTITY_SHOW_HIDDEN_OVERRIDE;
	$ENTITY_SHOW_HIDDEN_OVERRIDE = $show_hidden;
}

/**
 * This will be replaced. Do not use in plugins!
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
 * @param string $annotation_name name of the annotation
 * @param string $entity_guid SQL string that evaluates to the GUID of the entity the annotation should be attached to
 * @param string $owner_guid SQL string that evaluates to the GUID of the owner of the annotation	 	 *
 * @param boolean $exists If set to true, will return true if the annotation exists, otherwise returns false
 * @return string An SQL fragment suitable for inserting into a WHERE clause
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
 * Add access restriction sql code to a given query.
 * Note that if this code is executed in privileged mode it will return blank.
 * @todo DELETE once Query classes are fully integrated
 *
 * @param string $table_prefix Optional table. prefix for the access code.
 * @param int $owner
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
		$friends_bit = "{$table_prefix}access_id = " . ACCESS_FRIENDS . "
			AND {$table_prefix}owner_guid IN (
				SELECT guid_one FROM {$CONFIG->dbprefix}entity_relationships
				WHERE relationship='friend' AND guid_two=$owner
			)";

		$friends_bit = '('.$friends_bit.') OR ';

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

	if (!$ENTITY_SHOW_HIDDEN_OVERRIDE)
		$sql .= " and {$table_prefix}enabled='yes'";
	return '('.$sql.')';
}

/**
 * Determines whether the given user has access to the given entity
 *
 * @param ElggEntity $entity The entity to check access for.
 * @param ElggUser $user Optionally the user to check access for.
 *
 * @return boolean True if the user can access the entity
 */
function has_access_to_entity($entity, $user = null) {
	global $CONFIG;

	if (!isset($user)) {
		$access_bit = get_access_sql_suffix("e");
	} else {
		$access_bit = get_access_sql_suffix("e", $user->getGUID());
	}

	$query = "SELECT guid from {$CONFIG->dbprefix}entities e WHERE e.guid = " . $entity->getGUID();
	$query .= " AND " . $access_bit; // Add access controls
	if (get_data($query)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Returns an array of access permissions that the specified user is allowed to save objects with.
 * Permissions are of the form ('id' => 'Description')
 *
 * @param int $user_id The user's GUID.
 * @param int $site_id The current site.
 * @param true|false $flush If this is set to true, this will shun any cached version
 *
 * @return array List of access permissions
 */
function get_write_access_array($user_id = 0, $site_id = 0, $flush = false) {
	global $CONFIG;
	//@todo this is probably not needed since caching happens at the DB level.
	static $access_array;

	if ($user_id == 0) {
		$user_id = get_loggedin_userid();
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

		$tmp_access_array = array(	ACCESS_PRIVATE => elgg_echo("PRIVATE"),
									ACCESS_FRIENDS => elgg_echo("access:friends:label"),
									ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
									ACCESS_PUBLIC => elgg_echo("PUBLIC"));
		if ($collections = get_data($query)) {
			foreach($collections as $collection) {
				$tmp_access_array[$collection->id] = $collection->name;
			}
		}

		$access_array[$user_id] = $tmp_access_array;
	} else {
		$tmp_access_array = $access_array[$user_id];
	}

	$tmp_access_array = trigger_plugin_hook('access:collections:write','user',array('user_id' => $user_id, 'site_id' => $site_id),$tmp_access_array);

	return $tmp_access_array;
}

/**
 * Can the user write to the access collection?
 *
 * Hook into the access:collections:write, user to change this.
 *
 * Respects access control disabling for admin users and {@see elgg_set_ignore_access()}
 *
 * @see get_write_access_array()
 * 
 * @param int   $collection_id The collection id
 * @param mixed $user_guid     The user GUID to check for. Defaults to logged in user.
 * @return bool
 */
function can_edit_access_collection($collection_id, $user_guid = null) {
	if ($user_guid) {
		$user = get_entity((int) $user_guid);
	} else {
		$user = get_loggedin_user();
	}

	$collection = get_access_collection($collection_id);

	if (!($user instanceof ElggUser) || !$collection) {
		return false;
	}

	$write_access = get_write_access_array($user->getGUID(), null, true);

	// don't ignore access when checking users.
	if ($user_guid) {
		return array_key_exists($collection_id, $write_access);
	} else {
		return elgg_get_ignore_access() || array_key_exists($collection_id, $write_access);
	}
}

/**
 * Creates a new access control collection owned by the specified user.
 *
 * @param string $name The name of the collection.
 * @param int $owner_guid The GUID of the owner (default: currently logged in user).
 * @param int $site_guid The GUID of the site (default: current site).
 *
 * @return int|false Depending on success (the collection ID if successful).
 */
function create_access_collection($name, $owner_guid = 0, $site_guid = 0) {
	global $CONFIG;

	$name = trim($name);
	if (empty($name)) {
		return false;
	}

	if ($owner_guid == 0) {
		$owner_guid = get_loggedin_userid();
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

	if (!trigger_plugin_hook('access:collections:addcollection', 'collection', $params, true)) {
		return false;
	}

	return $id;
}

/**
 * Updates the membership in an access collection.
 *
 * @param int $collection_id The ID of the collection.
 * @param array $members Array of member GUIDs
 * @return true|false Depending on success
 */
function update_access_collection($collection_id, $members) {
	global $CONFIG;

	$acl = get_access_collection($collection_id);

	if (!$acl) {
		return false;
	}

	$members = (is_array($members)) ? $members : array();

	$cur_members = get_members_of_access_collection($collection_id, true);
	$cur_members = (is_array($cur_members)) ? $cur_members : array();

	$remove_members = array_diff($cur_members, $members);
	$add_members = array_diff($members, $cur_members);

	$result = true;

	foreach ($add_members as $guid) {
		$result = $result && add_user_to_access_collection($guid, $collection_id);
	}

	foreach ($remove_members as $guid) {
		$result = $result && remove_user_from_access_collection($guid, $collection_id);
	}

	return $result;
}

/**
 * Deletes a specified access collection
 *
 * @param int $collection_id The collection ID
 * @return true|false Depending on success
 */
function delete_access_collection($collection_id) {
	global $CONFIG;
	
	$collection_id = (int) $collection_id;
	$params = array('collection_id' => $collection_id);

	if (!trigger_plugin_hook('access:collections:deletecollection', 'collection', $params, true)) {
		return false;
	}

	// Deleting membership doesn't affect result of deleting ACL.
	$q = "DELETE FROM {$CONFIG->dbprefix}access_collection_membership
		WHERE access_collection_id = {$collection_id}";
	delete_data($q);
	
	$q = "DELETE FROM {$CONFIG->dbprefix}access_collections
		WHERE id = {$collection_id}";
	$result = delete_data($q);

	return $result;
}

/**
 * Get a specified access collection
 *
 * @param int $collection_id The collection ID
 * @return object|false Depending on success
 */
function get_access_collection($collection_id) {
	global $CONFIG;
	$collection_id = (int) $collection_id;

	$get_collection = get_data_row("SELECT * FROM {$CONFIG->dbprefix}access_collections WHERE id = {$collection_id}");

	return $get_collection;
}

/**
 * Adds a user to the specified user collection
 *
 * @param int $user_guid The GUID of the user to add
 * @param int $collection_id The ID of the collection to add them to
 * @return true|false Depending on success
 */
function add_user_to_access_collection($user_guid, $collection_id) {
	global $CONFIG;
	
	$collection_id = (int) $collection_id;
	$user_guid = (int) $user_guid;
	$user = get_user($user_guid);

	$collection = get_access_collection($collection_id);

	if (!($user instanceof Elgguser) || !$collection) {
		return false;
	}

	$params = array(
		'collection_id' => $collection_id,
		'user_guid' => $user_guid
	);

	if (!trigger_plugin_hook('access:collections:add_user', 'collection', $params, true)) {
		return false;
	}

	try {
		$q = "INSERT INTO {$CONFIG->dbprefix}access_collection_membership
			SET access_collection_id = {$collection_id},
				user_guid = {$user_guid}";
		insert_data($q);
	} catch (DatabaseException $e) {
		return false;
	}

	return true;
}

/**
 * Removes a user from an access collection
 *
 * @param int $user_guid The user GUID
 * @param int $collection_id The access collection ID
 * @return true|false Depending on success
 */
function remove_user_from_access_collection($user_guid, $collection_id) {
	global $CONFIG;
	
	$collection_id = (int) $collection_id;
	$user_guid = (int) $user_guid;
	$user = get_user($user_guid);

	$collection = get_access_collection($collection_id);

	if (!($user instanceof Elgguser) || !$collection) {
		return false;
	}

	$params = array(
		'collection_id' => $collection_id,
		'user_guid' => $user_guid
	);

	if (!trigger_plugin_hook('access:collections:remove_user', 'collection', $params, true)) {
		return false;
	}

	$q = "DELETE FROM {$CONFIG->dbprefix}access_collection_membership
		WHERE access_collection_id = {$collection_id}
			AND user_guid = {$user_guid}";

	return delete_data($q);
}

/**
 * Get all of a users collections
 *
 * @param int $owner_guid The user ID
 * @param int $site_guid The GUID of the site (default: current site).
 * @return true|false Depending on success
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
 * Get all of members of a friend collection
 *
 * @param int $collection The collection's ID
 * @param true|false $idonly If set to true, will only return the members' IDs (default: false)
 * @return ElggUser entities if successful, false if not
 */
function get_members_of_access_collection($collection, $idonly = FALSE) {
	global $CONFIG;
	$collection = (int)$collection;

	if (!$idonly) {
		$query = "SELECT e.* FROM {$CONFIG->dbprefix}access_collection_membership m JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.user_guid WHERE m.access_collection_id = {$collection}";
		$collection_members = get_data($query, "entity_row_to_elggstar");
	} else {
		$query = "SELECT e.guid FROM {$CONFIG->dbprefix}access_collection_membership m JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.user_guid WHERE m.access_collection_id = {$collection}";
		$collection_members = get_data($query);
		if (!$collection_members) {
			return FALSE;
		}
		foreach($collection_members as $key => $val) {
			$collection_members[$key] = $val->guid;
		}
	}

	return $collection_members;
}

/**
 * Displays a user's access collections, using the friends/collections view
 *
 * @param int $owner_guid The GUID of the owning user
 * @return string A formatted rendition of the collections
 */
function elgg_view_access_collections($owner_guid) {
	if ($collections = get_user_access_collections($owner_guid)) {
		foreach($collections as $key => $collection) {
			$collections[$key]->members = get_members_of_access_collection($collection->id, true);
			$collections[$key]->entities = get_user_friends($owner_guid,"",9999);
		}
	}

	return elgg_view('friends/collections',array('collections' => $collections));
}

/**
 * Get entities with the specified access collection id.
 *
 * @deprecated 1.7. Use elgg_get_entities_from_access_id()
 *
 * @param $collection_id
 * @param $entity_type
 * @param $entity_subtype
 * @param $owner_guid
 * @param $limit
 * @param $offset
 * @param $order_by
 * @param $site_guid
 * @param $count
 * @return unknown_type
 */
function get_entities_from_access_id($collection_id, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {
	// log deprecated warning
	elgg_deprecated_notice('get_entities_from_access_id() was deprecated by elgg_get_entities()!', 1.7);

	if (!$collection_id) {
		return FALSE;
	}

	// build the options using given parameters
	$options = array();
	$options['limit'] = $limit;
	$options['offset'] = $offset;
	$options['count'] = $count;

	if ($entity_type) {
		$options['type'] = sanitise_string($entity_type);
	}

	if ($entity_subtype) {
		$options['subtype'] = $entity_subtype;
	}

	if ($site_guid) {
		$options['site_guid'] = $site_guid;
	}

	if ($order_by) {
		$options['order_by'] = sanitise_string("e.time_created, $order_by");
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($site_guid) {
		$options['site_guid'] = $site_guid;
	}

	$options['access_id'] = $collection_id;

	return elgg_get_entities_from_access_id($options);
}

/**
 * @deprecated 1.7
 */
function get_entities_from_access_collection($collection_id, $entity_type = "", $entity_subtype = "",
	$owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {

	elgg_deprecated_notice('get_entities_from_access_collection() was deprecated by elgg_get_entities()', 1.7);

	return get_entities_from_access_id($collection_id, $entity_type, $entity_subtype,
			$owner_guid, $limit, $offset, $order_by, $site_guid, $count);
}

/**
 * Retrieve entities for a given access collection
 *
 * @param int $collection_id
 * @param array $options @see elgg_get_entities()
 * @return array
 * @since 1.7.0
 */
function elgg_get_entities_from_access_id(array $options=array()) {
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
 * @param $collection_id
 * @param $entity_type
 * @param $entity_subtype
 * @param $owner_guid
 * @param $limit
 * @param $fullview
 * @param $viewtypetoggle
 * @param $pagination
 * @return str
 */
function list_entities_from_access_id($collection_id, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = true, $pagination = true) {
	$offset = (int) get_input('offset');
	$limit = (int) $limit;
	$count = get_entities_from_access_id($collection_id, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, "", 0, true);
	$entities = get_entities_from_access_id($collection_id, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, "", 0, false);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle, $pagination);
}

/**
 * Return a humanreadable version of an entity's access level
 *
 * @param $entity_accessid (int) The entity's access id
 * @return string e.g. Public, Private etc
 * @since 1.7.0
 */
function get_readable_access_level($entity_accessid){
	$access = (int) $entity_accessid;
	//get the access level for object in readable string
	$options = get_write_access_array();
	foreach($options as $key => $option) {
		if($key == $access){
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
 * @return bool Previous ignore_access setting.
 * @since 1.7.0
 */
function elgg_set_ignore_access($ignore = true) {
	$elgg_access = elgg_get_access_object();
	return $elgg_access->set_ignore_access($ignore);
}

/**
 * Get current ignore access setting.
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_get_ignore_access() {
	return elgg_get_access_object()->get_ignore_access();
}

/**
 * Decides if the access system is being ignored.
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
 * @return ElggAccess
 * @since 1.7.0
 */
function elgg_get_access_object() {
	static $elgg_access;

	if (!$elgg_access) {
		$elgg_access = new ElggAccess();
	}

	return $elgg_access;
}

global $init_finished;
$init_finished = false;

/**
 * A quick and dirty way to make sure the access permissions have been correctly set up
 *
 */
function access_init() {
	global $init_finished;
	$init_finished = true;
}

/**
 * Override permissions system
 *
 * @return true|null
 * @since 1.7.0
 */
function elgg_override_permissions_hook($hook, $type, $returnval, $params) {
	$user_guid = get_loggedin_userid();

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

/**
 * Runs unit tests for the entities object.
 */
function access_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/api/access_collections.php';
	return $value;
}

// This function will let us know when 'init' has finished
register_elgg_event_handler('init', 'system', 'access_init', 9999);

// For overrided permissions
register_plugin_hook('permissions_check', 'all', 'elgg_override_permissions_hook');
register_plugin_hook('container_permissions_check', 'all', 'elgg_override_permissions_hook');

register_plugin_hook('unit_test', 'system', 'access_test');
