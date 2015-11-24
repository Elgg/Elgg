<?php

namespace Elgg\Database;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class AccessCollections {
	/**
	 * @var int
	 */
	private $site_guid;

	/**
	 * Constructor
	 *
	 * @param int $site_guid The GUID of the default Elgg site
	 */
	public function __construct($site_guid) {
		$this->site_guid = $site_guid;
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
	function getAccessList($user_guid = 0, $site_guid = 0, $flush = false) {
		global $init_finished;
		$cache = _elgg_services()->accessCache;
		
		if ($flush) {
			$cache->clear();
		}
	
		if ($user_guid == 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
	
		if (($site_guid == 0) && $this->site_guid) {
			$site_guid = $this->site_guid;
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
	function getAccessArray($user_guid = 0, $site_guid = 0, $flush = false) {
		global $init_finished;
	
		$cache = _elgg_services()->accessCache;
	
		if ($flush) {
			$cache->clear();
		}
	
		if ($user_guid == 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
	
		if (($site_guid == 0) && $this->site_guid) {
			$site_guid = $this->site_guid;
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
				$db = _elgg_services()->db;
				$prefix = $db->getTablePrefix();

				$access_array[] = ACCESS_LOGGED_IN;
	
				// Get ACL memberships
				$query = "SELECT am.access_collection_id"
					. " FROM {$prefix}access_collection_membership am"
					. " LEFT JOIN {$prefix}access_collections ag ON ag.id = am.access_collection_id"
					. " WHERE am.user_guid = $user_guid AND (ag.site_guid = $site_guid OR ag.site_guid = 0)";
	
				$collections = $db->getData($query);
				if ($collections) {
					foreach ($collections as $collection) {
						if (!empty($collection->access_collection_id)) {
							$access_array[] = (int)$collection->access_collection_id;
						}
					}
				}
	
				// Get ACLs owned.
				$query = "SELECT ag.id FROM {$prefix}access_collections ag ";
				$query .= "WHERE ag.owner_guid = $user_guid AND (ag.site_guid = $site_guid OR ag.site_guid = 0)";
	
				$collections = $db->getData($query);
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
		return _elgg_services()->hooks->trigger('access:collections:read', 'user', $options, $access_array);
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
	function getWhereSql(array $options = array()) {
		global $ENTITY_SHOW_HIDDEN_OVERRIDE;
	
		$defaults = array(
			'table_alias' => 'e',
			'user_guid' => _elgg_services()->session->getLoggedInUserGuid(),
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

		$prefix = _elgg_services()->db->getTablePrefix();
	
		if ($options['ignore_access']) {
			$clauses['ors'][] = '1 = 1';
		} else if ($options['user_guid']) {
			// include content of user's friends
			$clauses['ors'][] = "$table_alias{$options['access_column']} = " . ACCESS_FRIENDS . "
				AND $table_alias{$options['owner_guid_column']} IN (
					SELECT guid_one FROM {$prefix}entity_relationships
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
	
		$clauses = _elgg_services()->hooks->trigger('get_sql', 'access', $options, $clauses);
	
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
	 * @param \ElggEntity $entity The entity to check access for.
	 * @param \ElggUser   $user   Optionally user to check access for. Defaults to
	 *                           logged in user (which is a useless default).
	 *
	 * @return bool
	 */
	function hasAccessToEntity($entity, $user = null) {
		
	
		// See #7159. Must not allow ignore access to affect query
		$ia = elgg_set_ignore_access(false);
	
		if (!isset($user)) {
			$access_bit = _elgg_get_access_where_sql();
		} else {
			$access_bit = _elgg_get_access_where_sql(array('user_guid' => $user->getGUID()));
		}
	
		elgg_set_ignore_access($ia);

		$db = _elgg_services()->db;
		$prefix = $db->getTablePrefix();
	
		$query = "SELECT guid from {$prefix}entities e WHERE e.guid = {$entity->guid}";
		// Add access controls
		$query .= " AND " . $access_bit;
		if ($db->getData($query)) {
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
	 * @param int   $user_guid    The user's GUID.
	 * @param int   $site_guid    The current site.
	 * @param bool  $flush        If this is set to true, this will ignore a cached access array
	 * @param array $input_params Some parameters passed into an input/access view
	 *
	 * @return array List of access permissions
	 */
	function getWriteAccessArray($user_guid = 0, $site_guid = 0, $flush = false, array $input_params = array()) {
		global $init_finished;
		$cache = _elgg_services()->accessCache;
	
		if ($flush) {
			$cache->clear();
		}
	
		if ($user_guid == 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
	
		if (($site_guid == 0) && $this->site_guid) {
			$site_guid = $this->site_guid;
		}
	
		$user_guid = (int) $user_guid;
		$site_guid = (int) $site_guid;
	
		$hash = $user_guid . $site_guid . 'get_write_access_array';
	
		if ($cache[$hash]) {
			$access_array = $cache[$hash];
		} else {
			// @todo is there such a thing as public write access?
			$access_array = array(
				ACCESS_PRIVATE => $this->getReadableAccessLevel(ACCESS_PRIVATE),
				ACCESS_FRIENDS => $this->getReadableAccessLevel(ACCESS_FRIENDS),
				ACCESS_LOGGED_IN => $this->getReadableAccessLevel(ACCESS_LOGGED_IN),
				ACCESS_PUBLIC => $this->getReadableAccessLevel(ACCESS_PUBLIC)
			);

			$collections = $this->getEntityCollections($user_guid, $site_guid);
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
			'site_id' => $site_guid,
			'input_params' => $input_params,
		);
		return _elgg_services()->hooks->trigger('access:collections:write', 'user', $options, $access_array);
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
	function canEdit($collection_id, $user_guid = null) {
		if ($user_guid) {
			$user = _elgg_services()->entityTable->get((int) $user_guid);
		} else {
			$user = _elgg_services()->session->getLoggedInUser();
		}
	
		$collection = get_access_collection($collection_id);
	
		if (!($user instanceof \ElggUser) || !$collection) {
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
	 */
	function create($name, $owner_guid = 0, $site_guid = 0) {
		$name = trim($name);
		if (empty($name)) {
			return false;
		}
	
		if ($owner_guid == 0) {
			$owner_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
		if (($site_guid == 0) && $this->site_guid) {
			$site_guid = $this->site_guid;
		}

		$db = _elgg_services()->db;
		$prefix = $db->getTablePrefix();

		$name = $db->sanitizeString($name);
	
		$q = "INSERT INTO {$prefix}access_collections
			SET name = '{$name}',
				owner_guid = {$owner_guid},
				site_guid = {$site_guid}";
		$id = $db->insertData($q);
		if (!$id) {
			return false;
		}
	
		$params = array(
			'collection_id' => $id
		);
	
		if (!_elgg_services()->hooks->trigger('access:collections:addcollection', 'collection', $params, true)) {
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
	 */
	function update($collection_id, $members) {
		$acl = $this->get($collection_id);
	
		if (!$acl) {
			return false;
		}
		$members = (is_array($members)) ? $members : array();
	
		$cur_members = $this->getMembers($collection_id, true);
		$cur_members = (is_array($cur_members)) ? $cur_members : array();
	
		$remove_members = array_diff($cur_members, $members);
		$add_members = array_diff($members, $cur_members);
	
		$result = true;
	
		foreach ($add_members as $guid) {
			$result = $result && $this->addUser($guid, $collection_id);
		}
	
		foreach ($remove_members as $guid) {
			$result = $result && $this->removeUser($guid, $collection_id);
		}
	
		return $result;
	}
	
	/**
	 * Deletes a specified access collection and its membership.
	 *
	 * @param int $collection_id The collection ID
	 *
	 * @return bool
	 */
	function delete($collection_id) {
		$collection_id = (int) $collection_id;
		$params = array('collection_id' => $collection_id);
	
		if (!_elgg_services()->hooks->trigger('access:collections:deletecollection', 'collection', $params, true)) {
			return false;
		}

		$db = _elgg_services()->db;
		$prefix = $db->getTablePrefix();
	
		// Deleting membership doesn't affect result of deleting ACL.
		$q = "DELETE FROM {$prefix}access_collection_membership
			WHERE access_collection_id = {$collection_id}";
		$db->deleteData($q);
	
		$q = "DELETE FROM {$prefix}access_collections
			WHERE id = {$collection_id}";
		$result = $db->deleteData($q);
	
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
	function get($collection_id) {
		
		$collection_id = (int) $collection_id;

		$db = _elgg_services()->db;
		$prefix = $db->getTablePrefix();
	
		$query = "SELECT * FROM {$prefix}access_collections WHERE id = {$collection_id}";
		$get_collection = $db->getDataRow($query);
	
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
	 */
	function addUser($user_guid, $collection_id) {
		$collection_id = (int) $collection_id;
		$user_guid = (int) $user_guid;
		$user = get_user($user_guid);
	
		$collection = $this->get($collection_id);
	
		if (!($user instanceof \ElggUser) || !$collection) {
			return false;
		}
	
		$params = array(
			'collection_id' => $collection_id,
			'user_guid' => $user_guid
		);
	
		$result = _elgg_services()->hooks->trigger('access:collections:add_user', 'collection', $params, true);
		if ($result == false) {
			return false;
		}

		$db = _elgg_services()->db;
		$prefix = $db->getTablePrefix();
	
		// if someone tries to insert the same data twice, we do a no-op on duplicate key
		$q = "INSERT INTO {$prefix}access_collection_membership
				SET access_collection_id = $collection_id, user_guid = $user_guid
				ON DUPLICATE KEY UPDATE user_guid = user_guid";
		$result = $db->insertData($q);
	
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
	 */
	function removeUser($user_guid, $collection_id) {
		$collection_id = (int) $collection_id;
		$user_guid = (int) $user_guid;
		$user = get_user($user_guid);
	
		$collection = $this->get($collection_id);
	
		if (!($user instanceof \ElggUser) || !$collection) {
			return false;
		}
	
		$params = array(
			'collection_id' => $collection_id,
			'user_guid' => $user_guid,
		);
	
		if (!_elgg_services()->hooks->trigger('access:collections:remove_user', 'collection', $params, true)) {
			return false;
		}

		$db = _elgg_services()->db;
		$prefix = $db->getTablePrefix();
	
		$q = "DELETE FROM {$prefix}access_collection_membership
			WHERE access_collection_id = {$collection_id}
				AND user_guid = {$user_guid}";
	
		return (bool)$db->deleteData($q);
	}
	
	/**
	 * Returns an array of database row objects of the access collections owned by $owner_guid.
	 *
	 * @param int $owner_guid The entity guid
	 * @param int $site_guid  The GUID of the site (default: current site).
	 *
	 * @return array|false
	 */
	function getEntityCollections($owner_guid, $site_guid = 0) {
		$owner_guid = (int) $owner_guid;
		$site_guid = (int) $site_guid;
	
		if (($site_guid == 0) && $this->site_guid) {
			$site_guid = $this->site_guid;
		}

		$db = _elgg_services()->db;
		$prefix = $db->getTablePrefix();
	
		$query = "SELECT * FROM {$prefix}access_collections
				WHERE owner_guid = {$owner_guid}
				AND site_guid = {$site_guid}
				ORDER BY name ASC";
	
		$collections = $db->getData($query);
	
		return $collections;
	}
	
	/**
	* Get all of members of an access collection
	*
	* @param int  $collection_id The collection's ID
	* @param bool $guids_only    If set to true, will only return the members' GUIDs (default: false)
	*
	* @return ElggUser[]|int[]|false guids or entities if successful, false if not
	*/
	function getMembers($collection_id, $guids_only = false) {
		$collection_id = (int) $collection_id;

		$db = _elgg_services()->db;
		$prefix = $db->getTablePrefix();

		if (!$guids_only) {
			$query = "SELECT e.* FROM {$prefix}access_collection_membership m"
				. " JOIN {$prefix}entities e ON e.guid = m.user_guid"
				. " WHERE m.access_collection_id = {$collection_id}";
			$collection_members = $db->getData($query, "entity_row_to_elggstar");
		} else {
			$query = "SELECT e.guid FROM {$prefix}access_collection_membership m"
				. " JOIN {$prefix}entities e ON e.guid = m.user_guid"
				. " WHERE m.access_collection_id = {$collection_id}";
			$collection_members = $db->getData($query);
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
	 * Return an array of database row objects of the access collections $entity_guid is a member of.
	 * 
	 * @param int $member_guid The entity guid
	 * @param int $site_guid   The GUID of the site (default: current site).
	 * 
	 * @return array|false
	 */
	function getCollectionsByMember($member_guid, $site_guid = 0) {
		$member_guid = (int) $member_guid;
		$site_guid = (int) $site_guid;
		
		if (($site_guid == 0) && $this->site_guid) {
			$site_guid = $this->site_guid;
		}
		
		$db = _elgg_services()->db;
		$prefix = $db->getTablePrefix();
		
		$query = "SELECT ac.* FROM {$prefix}access_collections ac
				JOIN {$prefix}access_collection_membership m ON ac.id = m.access_collection_id
				WHERE m.user_guid = {$member_guid}
				AND ac.site_guid = {$site_guid}
				ORDER BY name ASC";
		
		$collections = $db->getData($query);
		
		return $collections;
	}
	
	/**
	 * Return the name of an ACCESS_* constant or an access collection,
	 * but only if the logged in user owns the access collection or is an admin.
	 * Ownership requirement prevents us from exposing names of access collections
	 * that current user has been added to by other members and may contain
	 * sensitive classification of the current user (e.g. close friends vs acquaintances).
	 *
	 * Returns a string in the language of the user for global access levels, e.g.'Public, 'Friends', 'Logged in', 'Private';
	 * or a name of the owned access collection, e.g. 'My work colleagues';
	 * or a name of the group or other access collection, e.g. 'Group: Elgg technical support';
	 * or 'Limited' if the user access is restricted to read-only, e.g. a friends collection the user was added to
	 *
	 * @param int $entity_access_id The entity's access id
	 * 
	 * @return string
	 * @since 1.11
	 */
	function getReadableAccessLevel($entity_access_id) {
		$access = (int) $entity_access_id;

		$translator = _elgg_services()->translator;
	
		// Check if entity access id is a defined global constant
		$access_array = array(
			ACCESS_PRIVATE => $translator->translate("PRIVATE"),
			ACCESS_FRIENDS => $translator->translate("access:friends:label"),
			ACCESS_LOGGED_IN => $translator->translate("LOGGED_IN"),
			ACCESS_PUBLIC => $translator->translate("PUBLIC"),
		);
	
		if (array_key_exists($access, $access_array)) {
			return $access_array[$access];
		}
	
		$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		if (!$user_guid) {
			// return 'Limited' if there is no logged in user
			return $translator->translate('access:limited:label');
		}
		
		// Entity access id is probably a custom access collection
		// Check if the user has write access to it and can see it's label
		// Admins should always be able to see the readable version	
		$collection = $this->get($access);
		
		if ($collection) {
			if (($collection->owner_guid == $user_guid) || _elgg_services()->session->isAdminLoggedIn()) {
				return $collection->name;
			}
		}
		
		// return 'Limited' if the user does not have access to the access collection
		return $translator->translate('access:limited:label');
	}
}
