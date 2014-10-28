<?php
/**
 * Functions for Elgg's access system for entities, metadata, and annotations.
 *
 * Access is generally saved in the database as access_id.  This corresponds to
 * one of the ACCESS_* constants defined in {@link elgglib.php} or the ID of an
 * access collection.
 *
 * @package Elgg.Core
 * @subpackage Access
 */

/**
 * Set if Elgg's access system should be ignored.
 *
 * The access system will not return entities in any getter functions if the
 * user doesn't have access. This removes this restriction.
 *
 * When the access system is being ignored, all checks for create, retrieve,
 * update, and delete should pass. This affects all the canEdit() and related
 * methods.
 *
 * @tip Use this to access entities in automated scripts
 * when no user is logged in.
 *
 * @warning This will not show disabled entities.
 * Use {@link access_show_hidden_entities()} to access disabled entities.
 *
 * @internal The access override is checked in elgg_override_permissions(). It is
 * registered for the 'permissions_check' hooks to override the access system for
 * the canEdit() and canWriteToContainer() methods.
 *
 * @internal This clears the access cache.
 *
 * @internal For performance reasons this is done at the database access clause level.
 *
 * @param bool $ignore If true, disables all access checks.
 *
 * @return bool Previous ignore_access setting.
 * @since 1.7.0
 * @see elgg_get_ignore_access()
 */
function elgg_set_ignore_access($ignore = true) {
	$cache = _elgg_get_access_cache();
	$cache->clear();
	$elgg_access = elgg_get_access_object();
	return $elgg_access->setIgnoreAccess($ignore);
}

/**
 * Get current ignore access setting.
 *
 * @return bool
 * @since 1.7.0
 * @see elgg_set_ignore_access()
 */
function elgg_get_ignore_access() {
	return elgg_get_access_object()->getIgnoreAccess();
}

/**
 * Return an ElggCache static variable cache for the access caches
 *
 * @staticvar ElggStaticVariableCache $access_cache
 * @return \ElggStaticVariableCache
 * @access private
 */
function _elgg_get_access_cache() {
	/**
	 * A default filestore cache using the dataroot.
	 */
	static $access_cache;

	if (!$access_cache) {
		$access_cache = new ElggStaticVariableCache('access');
	}

	return $access_cache;
}

/**
 * Return a string of access_ids for $user_guid appropriate for inserting into an SQL IN clause.
 *
 * @uses get_access_array
 *
 * @see get_access_array()
 *
 * @param int  $user_guid User ID; defaults to currently logged in user
 * @param int  $site_guid Site ID; defaults to current site
 * @param bool $flush     If set to true, will refresh the access list from the
 *                        database rather than using this function's cache.
 *
 * @return string A list of access collections suitable for using in an SQL call
 * @access private
 */
function get_access_list($user_guid = 0, $site_guid = 0, $flush = false) {
	global $CONFIG, $init_finished;
	$cache = _elgg_get_access_cache();

	if ($flush) {
		$cache->clear();
	}

	if ($user_guid == 0) {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	if (($site_guid == 0) && (isset($CONFIG->site_id))) {
		$site_guid = $CONFIG->site_id;
	}
	$user_guid = (int) $user_guid;
	$site_guid = (int) $site_guid;

	$hash = $user_guid . $site_guid . 'get_access_list';

	if ($cache[$hash]) {
		return $cache[$hash];
	}

	$access_array = get_access_array($user_guid, $site_guid, $flush);
	$access = "(" . implode(",", $access_array) . ")";

	if ($init_finished) {
		$cache[$hash] = $access;
	}

	return $access;
}

/**
 * Returns an array of access IDs a user is permitted to see.
 *
 * Can be overridden with the 'access:collections:read', 'user' plugin hook.
 * @warning A callback for that plugin hook needs to either not retrieve data
 * from the database that would use the access system (triggering the plugin again)
 * or ignore the second call. Otherwise, an infinite loop will be created.
 *
 * This returns a list of all the collection ids a user owns or belongs
 * to plus public and logged in access levels. If the user is an admin, it includes
 * the private access level.
 *
 * @internal this is only used in core for creating the SQL where clause when
 * retrieving content from the database. The friends access level is handled by
 * _elgg_get_access_where_sql().
 *
 * @see get_write_access_array() for the access levels that a user can write to.
 *
 * @param int  $user_guid User ID; defaults to currently logged in user
 * @param int  $site_guid Site ID; defaults to current site
 * @param bool $flush     If set to true, will refresh the access ids from the
 *                        database rather than using this function's cache.
 *
 * @return array An array of access collections ids
 */
function get_access_array($user_guid = 0, $site_guid = 0, $flush = false) {
	global $CONFIG, $init_finished;

	$cache = _elgg_get_access_cache();

	if ($flush) {
		$cache->clear();
	}

	if ($user_guid == 0) {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	if (($site_guid == 0) && (isset($CONFIG->site_guid))) {
		$site_guid = $CONFIG->site_guid;
	}

	$user_guid = (int) $user_guid;
	$site_guid = (int) $site_guid;

	$hash = $user_guid . $site_guid . 'get_access_array';

	if ($cache[$hash]) {
		$access_array = $cache[$hash];
	} else {
		$access_array = array(ACCESS_PUBLIC);

		// The following can only return sensible data for a known user.
		if ($user_guid) {
			$access_array[] = ACCESS_LOGGED_IN;

			// Get ACL memberships
			$query = "SELECT am.access_collection_id"
				. " FROM {$CONFIG->dbprefix}access_collection_membership am"
				. " LEFT JOIN {$CONFIG->dbprefix}access_collections ag ON ag.id = am.access_collection_id"
				. " WHERE am.user_guid = $user_guid AND (ag.site_guid = $site_guid OR ag.site_guid = 0)";

			$collections = get_data($query);
			if ($collections) {
				foreach ($collections as $collection) {
					if (!empty($collection->access_collection_id)) {
						$access_array[] = (int)$collection->access_collection_id;
					}
				}
			}

			// Get ACLs owned.
			$query = "SELECT ag.id FROM {$CONFIG->dbprefix}access_collections ag ";
			$query .= "WHERE ag.owner_guid = $user_guid AND (ag.site_guid = $site_guid OR ag.site_guid = 0)";

			$collections = get_data($query);
			if ($collections) {
				foreach ($collections as $collection) {
					if (!empty($collection->id)) {
						$access_array[] = (int)$collection->id;
					}
				}
			}

			$ignore_access = elgg_check_access_overrides($user_guid);

			if ($ignore_access == true) {
				$access_array[] = ACCESS_PRIVATE;
			}
		}

		if ($init_finished) {
			$cache[$hash] = $access_array;
		}
	}

	$options = array(
		'user_id' => $user_guid,
		'site_id' => $site_guid
	);

	// see the warning in the docs for this function about infinite loop potential
	return elgg_trigger_plugin_hook('access:collections:read', 'user', $options, $access_array);
}

/**
 * Gets the default access permission.
 *
 * This returns the default access level for the site or optionally of the user.
 * If want you to change the default access based on group of other information,
 * use the 'default', 'access' plugin hook.
 *
 * @param ElggUser $user Get the user's default access. Defaults to logged in user.
 *
 * @return int default access id (see ACCESS defines in elgglib.php)
 */
function get_default_access(ElggUser $user = null) {
	global $CONFIG;

	// site default access
	$default_access = $CONFIG->default_access;

	// user default access if enabled
	if ($CONFIG->allow_user_default_access) {
		$user = $user ? $user : elgg_get_logged_in_user_entity();
		if ($user) {
			$user_access = $user->getPrivateSetting('elgg_default_access');
			if ($user_access !== null) {
				$default_access = $user_access;
			}
		}
	}

	$params = array(
		'user' => $user,
		'default_access' => $default_access,
	);
	return elgg_trigger_plugin_hook('default', 'access', $params, $default_access);
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
 * @param bool $show_hidden Show disabled entities.
 * @return bool
 * @access private
 */
function access_show_hidden_entities($show_hidden) {
	global $ENTITY_SHOW_HIDDEN_OVERRIDE;
	$current_value = $ENTITY_SHOW_HIDDEN_OVERRIDE;
	$ENTITY_SHOW_HIDDEN_OVERRIDE = $show_hidden;
	return $current_value;
}

/**
 * Return current status of showing disabled entities.
 *
 * @return bool
 * @access private
 */
function access_get_show_hidden_status() {
	global $ENTITY_SHOW_HIDDEN_OVERRIDE;
	return $ENTITY_SHOW_HIDDEN_OVERRIDE;
}

/**
 * Returns the SQL where clause for enforcing read access to data.
 *
 * Note that if this code is executed in privileged mode it will return (1=1).
 *
 * Otherwise it returns a where clause to retrieve the data that a user has
 * permission to read.
 *
 * Plugin authors can hook into the 'get_sql', 'access' plugin hook to modify,
 * remove, or add to the where clauses. The plugin hook will pass an array with the current
 * ors and ands to the function in the form:
 *  array(
 *      'ors' => array(),
 *      'ands' => array()
 *  )
 *
 * The results will be combined into an SQL where clause in the form:
 *  ((or1 OR or2 OR orN) AND (and1 AND and2 AND andN))
 *
 * @param array $options Array in format:
 *
 * 	table_alias => STR Optional table alias. This is based on the select and join clauses.
 *                     Default is 'e'.
 *
 *  user_guid => INT Optional GUID for the user that we are retrieving data for.
 *                   Defaults to the logged in user.
 *
 *  use_enabled_clause => BOOL Optional. Should we append the enabled clause? The default
 *                             is set by access_show_hidden_entities().
 *
 *  access_column => STR Optional access column name. Default is 'access_id'.
 *
 *  owner_guid_column => STR Optional owner_guid column. Default is 'owner_guid'.
 *
 *  guid_column => STR Optional guid_column. Default is 'guid'.
 *
 * @return string
 * @access private
 */
function _elgg_get_access_where_sql(array $options = array()) {
	global $ENTITY_SHOW_HIDDEN_OVERRIDE, $CONFIG;

	$defaults = array(
		'table_alias' => 'e',
		'user_guid' => elgg_get_logged_in_user_guid(),
		'use_enabled_clause' => !$ENTITY_SHOW_HIDDEN_OVERRIDE,
		'access_column' => 'access_id',
		'owner_guid_column' => 'owner_guid',
		'guid_column' => 'guid',
	);

	$options = array_merge($defaults, $options);

	// just in case someone passes a . at the end
	$options['table_alias'] = rtrim($options['table_alias'], '.');

	foreach (array('table_alias', 'access_column', 'owner_guid_column', 'guid_column') as $key) {
		$options[$key] = sanitize_string($options[$key]);
	}
	$options['user_guid'] = sanitize_int($options['user_guid'], false);

	// only add dot if we have an alias or table name
	$table_alias = $options['table_alias'] ? $options['table_alias'] . '.' : '';

	$options['ignore_access'] = elgg_check_access_overrides($options['user_guid']);

	$clauses = array(
		'ors' => array(),
		'ands' => array()
	);

	if ($options['ignore_access']) {
		$clauses['ors'][] = '1 = 1';
	} else if ($options['user_guid']) {
		// include content of user's friends
		$clauses['ors'][] = "$table_alias{$options['access_column']} = " . ACCESS_FRIENDS . "
			AND $table_alias{$options['owner_guid_column']} IN (
				SELECT guid_one FROM {$CONFIG->dbprefix}entity_relationships
				WHERE relationship = 'friend' AND guid_two = {$options['user_guid']}
			)";

		// include user's content
		$clauses['ors'][] = "$table_alias{$options['owner_guid_column']} = {$options['user_guid']}";
	}

	// include standard accesses (public, logged in, access collections)
	if (!$options['ignore_access']) {
		$access_list = get_access_list($options['user_guid']);
		$clauses['ors'][] = "$table_alias{$options['access_column']} IN {$access_list}";
	}

	if ($options['use_enabled_clause']) {
		$clauses['ands'][] = "{$table_alias}enabled = 'yes'";
	}

	$clauses = elgg_trigger_plugin_hook('get_sql', 'access', $options, $clauses);

	$clauses_str = '';
	if (is_array($clauses['ors']) && $clauses['ors']) {
		$clauses_str = '(' . implode(' OR ', $clauses['ors']) . ')';
	}

	if (is_array($clauses['ands']) && $clauses['ands']) {
		if ($clauses_str) {
			$clauses_str .= ' AND ';
		}
		$clauses_str .= '(' . implode(' AND ', $clauses['ands']) . ')';
	}

	return "($clauses_str)";
}

/**
 * Can a user access an entity.
 *
 * @warning If a logged in user doesn't have access to an entity, the
 * core engine will not load that entity.
 *
 * @tip This is mostly useful for checking if a user other than the logged in
 * user has access to an entity that is currently loaded.
 *
 * @todo This function would be much more useful if we could pass the guid of the
 * entity to test access for. We need to be able to tell whether the entity exists
 * and whether the user has access to the entity.
 *
 * @param ElggEntity $entity The entity to check access for.
 * @param ElggUser   $user   Optionally user to check access for. Defaults to
 *                           logged in user (which is a useless default).
 *
 * @return bool
 */
function has_access_to_entity($entity, $user = null) {
	global $CONFIG;

	// See #7159. Must not allow ignore access to affect query
	$ia = elgg_set_ignore_access(false);

	if (!isset($user)) {
		$access_bit = _elgg_get_access_where_sql();
	} else {
		$access_bit = _elgg_get_access_where_sql(array('user_guid' => $user->getGUID()));
	}

	elgg_set_ignore_access($ia);

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
 * Returns an array of access permissions that the user is allowed to save content with.
 * Permissions returned are of the form (id => 'name').
 *
 * Example return value in English:
 * array(
 *     0 => 'Private',
 *    -2 => 'Friends',
 *     1 => 'Logged in users',
 *     2 => 'Public',
 *    34 => 'My favorite friends',
 * );
 *
 * Plugin hook of 'access:collections:write', 'user'
 *
 * @warning this only returns access collections that the user owns plus the
 * standard access levels. It does not return access collections that the user
 * belongs to such as the access collection for a group.
 *
 * @param int  $user_guid The user's GUID.
 * @param int  $site_guid The current site.
 * @param bool $flush     If this is set to true, this will ignore a cached access array
 *
 * @return array List of access permissions
 */
function get_write_access_array($user_guid = 0, $site_guid = 0, $flush = false) {
	global $CONFIG, $init_finished;
	$cache = _elgg_get_access_cache();

	if ($flush) {
		$cache->clear();
	}

	if ($user_guid == 0) {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	if (($site_guid == 0) && (isset($CONFIG->site_id))) {
		$site_guid = $CONFIG->site_id;
	}

	$user_guid = (int) $user_guid;
	$site_guid = (int) $site_guid;

	$hash = $user_guid . $site_guid . 'get_write_access_array';

	if ($cache[$hash]) {
		$access_array = $cache[$hash];
	} else {
		// @todo is there such a thing as public write access?
		$access_array = array(
			ACCESS_PRIVATE => elgg_echo("PRIVATE"),
			ACCESS_FRIENDS => elgg_echo("access:friends:label"),
			ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
			ACCESS_PUBLIC => elgg_echo("PUBLIC")
		);

		$query = "SELECT ag.* FROM {$CONFIG->dbprefix}access_collections ag ";
		$query .= " WHERE (ag.site_guid = $site_guid OR ag.site_guid = 0)";
		$query .= " AND (ag.owner_guid = $user_guid)";

		$collections = get_data($query);
		if ($collections) {
			foreach ($collections as $collection) {
				$access_array[$collection->id] = $collection->name;
			}
		}

		if ($init_finished) {
			$cache[$hash] = $access_array;
		}
	}

	$options = array(
		'user_id' => $user_guid,
		'site_id' => $site_guid
	);
	return elgg_trigger_plugin_hook('access:collections:write', 'user',
		$options, $access_array);
}

/**
 * Can the user change this access collection?
 *
 * Use the plugin hook of 'access:collections:write', 'user' to change this.
 * @see get_write_access_array() for details on the hook.
 *
 * Respects access control disabling for admin users and {@link elgg_set_ignore_access()}
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
		$user = elgg_get_logged_in_user_entity();
	}

	$collection = get_access_collection($collection_id);

	if (!($user instanceof ElggUser) || !$collection) {
		return false;
	}

	$write_access = get_write_access_array($user->getGUID(), 0, true);

	// don't ignore access when checking users.
	if ($user_guid) {
		return array_key_exists($collection_id, $write_access);
	} else {
		return elgg_get_ignore_access() || array_key_exists($collection_id, $write_access);
	}
}

/**
 * Creates a new access collection.
 *
 * Access colletions allow plugins and users to create granular access
 * for entities.
 *
 * Triggers plugin hook 'access:collections:addcollection', 'collection'
 *
 * @internal Access collections are stored in the access_collections table.
 * Memberships to collections are in access_collections_membership.
 *
 * @param string $name       The name of the collection.
 * @param int    $owner_guid The GUID of the owner (default: currently logged in user).
 * @param int    $site_guid  The GUID of the site (default: current site).
 *
 * @return int|false The collection ID if successful and false on failure.
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
	$id = insert_data($q);
	if (!$id) {
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
 * be part of the access collection
 *
 * @note This will run all hooks associated with adding or removing
 * members to access collections.
 *
 * @param int   $collection_id The ID of the collection.
 * @param array $members       Array of member GUIDs
 *
 * @return bool
 * @see add_user_to_access_collection()
 * @see remove_user_from_access_collection()
 */
function update_access_collection($collection_id, $members) {
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
 * Deletes a specified access collection and its membership.
 *
 * @param int $collection_id The collection ID
 *
 * @return bool
 * @see create_access_collection()
 * @see update_access_collection()
 */
function delete_access_collection($collection_id) {
	global $CONFIG;

	$collection_id = (int) $collection_id;
	$params = array('collection_id' => $collection_id);

	if (!elgg_trigger_plugin_hook('access:collections:deletecollection', 'collection', $params, true)) {
		return false;
	}

	// Deleting membership doesn't affect result of deleting ACL.
	$q = "DELETE FROM {$CONFIG->dbprefix}access_collection_membership
		WHERE access_collection_id = {$collection_id}";
	delete_data($q);

	$q = "DELETE FROM {$CONFIG->dbprefix}access_collections
		WHERE id = {$collection_id}";
	$result = delete_data($q);

	return (bool)$result;
}

/**
 * Get a specified access collection
 *
 * @note This doesn't return the members of an access collection,
 * just the database row of the actual collection.
 *
 * @see get_members_of_access_collection()
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
 * Triggers the 'access:collections:add_user', 'collection' plugin hook.
 *
 * @param int $user_guid     The GUID of the user to add
 * @param int $collection_id The ID of the collection to add them to
 *
 * @return bool
 * @see update_access_collection()
 * @see remove_user_from_access_collection()
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

	$result = elgg_trigger_plugin_hook('access:collections:add_user', 'collection', $params, true);
	if ($result == false) {
		return false;
	}

	// if someone tries to insert the same data twice, we do a no-op on duplicate key
	$q = "INSERT INTO {$CONFIG->dbprefix}access_collection_membership
			SET access_collection_id = $collection_id, user_guid = $user_guid
			ON DUPLICATE KEY UPDATE user_guid = user_guid";
	$result = insert_data($q);

	return $result !== false;
}

/**
 * Removes a user from an access collection.
 *
 * Triggers the 'access:collections:remove_user', 'collection' plugin hook.
 *
 * @param int $user_guid     The user GUID
 * @param int $collection_id The access collection ID
 *
 * @return bool
 * @see update_access_collection()
 * @see remove_user_from_access_collection()
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

	if (!elgg_trigger_plugin_hook('access:collections:remove_user', 'collection', $params, true)) {
		return false;
	}

	$q = "DELETE FROM {$CONFIG->dbprefix}access_collection_membership
		WHERE access_collection_id = {$collection_id}
			AND user_guid = {$user_guid}";

	return (bool)delete_data($q);
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
			AND site_guid = {$site_guid}
			ORDER BY name ASC";

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
 */
function get_members_of_access_collection($collection, $idonly = false) {
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
			return false;
		}
		foreach ($collection_members as $key => $val) {
			$collection_members[$key] = $val->guid;
		}
	}

	return $collection_members;
}

/**
 * Return entities based upon access id.
 *
 * @param array $options Any options accepted by {@link elgg_get_entities()} and
 * 	access_id => int The access ID of the entity.
 *
 * @see elgg_get_entities()
 * @return mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 */
function elgg_get_entities_from_access_id(array $options = array()) {
	// restrict the resultset to access collection provided
	if (!isset($options['access_id'])) {
		return false;
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
 * @return string
 */
function elgg_list_entities_from_access_id(array $options = array()) {
	return elgg_list_entities($options, 'elgg_get_entities_from_access_id');
}

/**
 * Return the name of an ACCESS_* constant or an access collection,
 * but only if the logged in user has write access to it.
 * Write access requirement prevents us from exposing names of access collections
 * that current user has been added to by other members and may contain
 * sensitive classification of the current user (e.g. close friends vs acquaintances).
 *
 * Returns a string in the language of the user for global access levels, e.g.'Public, 'Friends', 'Logged in', 'Public';
 * or a name of the owned access collection, e.g. 'My work colleagues';
 * or a name of the group or other access collection, e.g. 'Group: Elgg technical support';
 * or 'Limited' if the user access is restricted to read-only, e.g. a friends collection the user was added to
 *
 * @uses get_write_access_array()
 *
 * @param int $entity_access_id The entity's access id
 * @return string
 * @since 1.7.0
 */
function get_readable_access_level($entity_access_id) {
	$access = (int) $entity_access_id;

	// Check if entity access id is a defined global constant
	$access_array = array(
		ACCESS_PRIVATE => elgg_echo("PRIVATE"),
		ACCESS_FRIENDS => elgg_echo("access:friends:label"),
		ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
		ACCESS_PUBLIC => elgg_echo("PUBLIC")
	);

	if (array_key_exists($access, $access_array)) {
		return $access_array[$access];
	}

	// Entity access id is a custom access collection
	// Check if the user has write access to it and can see it's label
	$write_access_array = get_write_access_array();

	if (array_key_exists($access, $write_access_array)) {
		return $write_access_array[$access];
	}

	// return 'Limited' if the user does not have access to the access collection
	return elgg_echo('access:limited:label');
}

/**
 * Decides if the access system should be ignored for a user.
 *
 * Returns true (meaning ignore access) if either of these 2 conditions are true:
 *   1) an admin user guid is passed to this function.
 *   2) {@link elgg_get_ignore_access()} returns true.
 *
 * @see elgg_set_ignore_access()
 *
 * @param int $user_guid The user to check against.
 *
 * @return bool
 * @since 1.7.0
 * @todo should this be a private function?
 */
function elgg_check_access_overrides($user_guid = 0) {
	if (!$user_guid || $user_guid <= 0) {
		$is_admin = false;
	} else {
		$is_admin = elgg_is_admin_user($user_guid);
	}

	return ($is_admin || elgg_get_ignore_access());
}

/**
 * Returns the Elgg_Access object.
 *
 * // @todo comment is incomplete
 * This is used to
 *
 * @return Elgg_Access
 * @since 1.7.0
 * @access private
 */
function elgg_get_access_object() {
	static $elgg_access;

	if (!$elgg_access) {
		$elgg_access = new Elgg_Access();
	}

	return $elgg_access;
}

/**
 * A flag to set if Elgg's access initialization is finished.
 *
 * @global bool $init_finished
 * @access private
 * @todo This is required to tell the access system to start caching because
 * calls are made while in ignore access mode and before the user is logged in.
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
 * Overrides the access system if appropriate.
 *
 * Allows admin users and calls after {@link elgg_set_ignore_access} to
 * bypass the access system.
 *
 * Registered for the 'permissions_check', 'all' and the
 * 'container_permissions_check', 'all' plugin hooks.
 *
 * Returns true to override the access system or null if no change is needed.
 *
 * @internal comment upgrade depends on this
 *
 * @param string $hook
 * @param string $type
 * @param bool $value
 * @param array $params
 * @return true|null
 * @access private
 */
function elgg_override_permissions($hook, $type, $value, $params) {
	$user = elgg_extract('user', $params);
	if ($user) {
		$user_guid = $user->getGUID();
	} else {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	// don't do this so ignore access still works with no one logged in
	//if (!$user instanceof ElggUser) {
	//	return false;
	//}

	// check for admin
	if ($user_guid && elgg_is_admin_user($user_guid)) {
		return true;
	}

	// check access overrides
	if ((elgg_check_access_overrides($user_guid))) {
		return true;
	}

	// consult other hooks
	return null;
}

/**
 * Runs unit tests for the access library
 *
 * @param string $hook
 * @param string $type
 * @param array $value
 * @param array $params
 * @return array
 *
 * @access private
 */
function access_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreAccessCollectionsTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreAccessSQLTest.php';
	return $value;
}

// Tell the access functions the system has booted, plugins are loaded,
// and the user is logged in so it can start caching
elgg_register_event_handler('ready', 'system', 'access_init');

// For overrided permissions
elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');

elgg_register_plugin_hook_handler('unit_test', 'system', 'access_test');