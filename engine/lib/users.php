<?php
/**
 * Elgg users
 * Functions to manage multiple or single users in an Elgg install
 *
 * @package Elgg.Core
 * @subpackage DataModel.User
 */

/// Map a username to a cached GUID
$USERNAME_TO_GUID_MAP_CACHE = array();

/// Map a user code to a cached GUID
$CODE_TO_GUID_MAP_CACHE = array();

/**
 * Return the user specific details of a user by a row.
 *
 * @param int $guid The ElggUser guid
 *
 * @return mixed
 */
function get_user_entity_as_row($guid) {
	global $CONFIG;

	$guid = (int)$guid;
	return get_data_row("SELECT * from {$CONFIG->dbprefix}users_entity where guid=$guid");
}

/**
 * Create or update the extras table for a given user.
 * Call create_entity first.
 *
 * @param int    $guid     The user's GUID
 * @param string $name     The user's display name
 * @param string $username The username
 * @param string $password The password
 * @param string $salt     A salt for the password
 * @param string $email    The user's email address
 * @param string $language The user's default language
 * @param string $code     A code
 *
 * @return bool
 */
function create_user_entity($guid, $name, $username, $password, $salt, $email, $language, $code) {
	global $CONFIG;

	$guid = (int)$guid;
	$name = sanitise_string($name);
	$username = sanitise_string($username);
	$password = sanitise_string($password);
	$salt = sanitise_string($salt);
	$email = sanitise_string($email);
	$language = sanitise_string($language);
	$code = sanitise_string($code);

	$row = get_entity_as_row($guid);
	if ($row) {
		// Exists and you have access to it

		$query = "SELECT guid from {$CONFIG->dbprefix}users_entity where guid = {$guid}";
		if ($exists = get_data_row($query)) {
			$query = "UPDATE {$CONFIG->dbprefix}users_entity
				set name='$name', username='$username', password='$password', salt='$salt',
				email='$email', language='$language', code='$code', last_action = "
				. time() . " where guid = {$guid}";

			$result = update_data($query);
			if ($result != false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				if (trigger_elgg_event('update', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
				}
			}
		} else {
			// Update failed, attempt an insert.
			$query = "INSERT into {$CONFIG->dbprefix}users_entity
				(guid, name, username, password, salt, email, language, code)
				values ($guid, '$name', '$username', '$password', '$salt', '$email', '$language', '$code')";

			$result = insert_data($query);
			if ($result !== false) {
				$entity = get_entity($guid);
				if (trigger_elgg_event('create', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete(); //delete_entity($guid);
				}
			}
		}
	}

	return false;
}

/**
 * Disables all of a user's entities
 *
 * @param int $owner_guid The owner GUID
 *
 * @return bool Depending on success
 */
function disable_user_entities($owner_guid) {
	global $CONFIG;
	$owner_guid = (int) $owner_guid;
	if ($entity = get_entity($owner_guid)) {
		if (trigger_elgg_event('disable', $entity->type, $entity)) {
			if ($entity->canEdit()) {
				$query = "UPDATE {$CONFIG->dbprefix}entities
					set enabled='no' where owner_guid={$owner_guid}
					or container_guid = {$owner_guid}";

				$res = update_data($query);
				return $res;
			}
		}
	}

	return false;
}

/**
 * Ban a user
 *
 * @param int    $user_guid The user guid
 * @param string $reason    A reason
 *
 * @return bool
 */
function ban_user($user_guid, $reason = "") {
	global $CONFIG;

	$user_guid = (int)$user_guid;
	$reason = sanitise_string($reason);

	$user = get_entity($user_guid);

	if (($user) && ($user->canEdit()) && ($user instanceof ElggUser)) {
		if (trigger_elgg_event('ban', 'user', $user)) {
			// Add reason
			if ($reason) {
				create_metadata($user_guid, 'ban_reason', $reason, '', 0, ACCESS_PUBLIC);
			}

			// clear "remember me" cookie code so user cannot login in using it
			$user->code = "";
			$user->save();

			// invalidate memcache for this user
			static $newentity_cache;
			if ((!$newentity_cache) && (is_memcache_available())) {
				$newentity_cache = new ElggMemcache('new_entity_cache');
			}

			if ($newentity_cache) {
				$newentity_cache->delete($user_guid);
			}

			// Set ban flag
			$query = "UPDATE {$CONFIG->dbprefix}users_entity set banned='yes' where guid=$user_guid";
			return update_data($query);
		}

		return FALSE;
	}

	return FALSE;
}

/**
 * Unban a user.
 *
 * @param int $user_guid Unban a user.
 *
 * @return bool
 */
function unban_user($user_guid) {
	global $CONFIG;

	$user_guid = (int)$user_guid;

	$user = get_entity($user_guid);

	if (($user) && ($user->canEdit()) && ($user instanceof ElggUser)) {
		if (trigger_elgg_event('unban', 'user', $user)) {
			create_metadata($user_guid, 'ban_reason', '', '', 0, ACCESS_PUBLIC);

			// invalidate memcache for this user
			static $newentity_cache;
			if ((!$newentity_cache) && (is_memcache_available())) {
				$newentity_cache = new ElggMemcache('new_entity_cache');
			}

			if ($newentity_cache) {
				$newentity_cache->delete($user_guid);
			}


			$query = "UPDATE {$CONFIG->dbprefix}users_entity set banned='no' where guid=$user_guid";
			return update_data($query);
		}

		return FALSE;
	}

	return FALSE;
}

/**
 * Makes user $guid an admin.
 *
 * @param int $user_guid User guid
 *
 * @return bool
 */
function make_user_admin($user_guid) {
	global $CONFIG;

	$user = get_entity((int)$user_guid);

	if (($user) && ($user instanceof ElggUser) && ($user->canEdit())) {
		if (trigger_elgg_event('make_admin', 'user', $user)) {

			// invalidate memcache for this user
			static $newentity_cache;
			if ((!$newentity_cache) && (is_memcache_available())) {
				$newentity_cache = new ElggMemcache('new_entity_cache');
			}

			if ($newentity_cache) {
				$newentity_cache->delete($user_guid);
			}

			$r = update_data("UPDATE {$CONFIG->dbprefix}users_entity set admin='yes' where guid=$user_guid");
			invalidate_cache_for_entity($user_guid);
			return $r;
		}

		return FALSE;
	}

	return FALSE;
}

/**
 * Removes user $guid's admin flag.
 *
 * @param int $user_guid User GUID
 *
 * @return bool
 */
function remove_user_admin($user_guid) {
	global $CONFIG;

	$user = get_entity((int)$user_guid);

	if (($user) && ($user instanceof ElggUser) && ($user->canEdit())) {
		if (trigger_elgg_event('remove_admin', 'user', $user)) {

			// invalidate memcache for this user
			static $newentity_cache;
			if ((!$newentity_cache) && (is_memcache_available())) {
				$newentity_cache = new ElggMemcache('new_entity_cache');
			}

			if ($newentity_cache) {
				$newentity_cache->delete($user_guid);
			}

			$r = update_data("UPDATE {$CONFIG->dbprefix}users_entity set admin='no' where guid=$user_guid");
			invalidate_cache_for_entity($user_guid);
			return $r;
		}

		return FALSE;
	}

	return FALSE;
}

/**
 * THIS FUNCTION IS DEPRECATED.
 *
 * Delete a user's extra data.
 *
 * @todo remove
 *
 * @param int $guid User GUID
 *
 * @return 1
 */
function delete_user_entity($guid) {
	system_message(elgg_echo('deprecatedfunction', array('delete_user_entity')));

	return 1; // Always return that we have deleted one row in order to not break existing code.
}

/**
 * Get the sites this user is part of
 *
 * @param int $user_guid The user's GUID
 * @param int $limit     Number of results to return
 * @param int $offset    Any indexing offset
 *
 * @return false|array On success, an array of ElggSites
 */
function get_user_sites($user_guid, $limit = 10, $offset = 0) {
	$user_guid = (int)$user_guid;
	$limit = (int)$limit;
	$offset = (int)$offset;

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member_of_site',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => FALSE,
		'types' => 'site',
		'limit' => $limit,
		'offset' => $offset)
	);
}

/**
 * Adds a user to another user's friends list.
 *
 * @param int $user_guid   The GUID of the friending user
 * @param int $friend_guid The GUID of the user to friend
 *
 * @return bool Depending on success
 */
function user_add_friend($user_guid, $friend_guid) {
	$user_guid = (int) $user_guid;
	$friend_guid = (int) $friend_guid;
	if ($user_guid == $friend_guid) {
		return false;
	}
	if (!$friend = get_entity($friend_guid)) {
		return false;
	}
	if (!$user = get_entity($user_guid)) {
		return false;
	}
	if ((!($user instanceof ElggUser)) || (!($friend instanceof ElggUser))) {
		return false;
	}
	return add_entity_relationship($user_guid, "friend", $friend_guid);
}

/**
 * Removes a user from another user's friends list.
 *
 * @param int $user_guid   The GUID of the friending user
 * @param int $friend_guid The GUID of the user on the friends list
 *
 * @return bool Depending on success
 */
function user_remove_friend($user_guid, $friend_guid) {
	global $CONFIG;

	$user_guid = (int) $user_guid;
	$friend_guid = (int) $friend_guid;

	// perform cleanup for access lists.
	$collections = get_user_access_collections($user_guid);
	if ($collections) {
		foreach ($collections as $collection) {
			remove_user_from_access_collection($friend_guid, $collection->id);
		}
	}

	return remove_entity_relationship($user_guid, "friend", $friend_guid);
}

/**
 * Determines whether or not a user is another user's friend.
 *
 * @param int $user_guid   The GUID of the user
 * @param int $friend_guid The GUID of the friend
 *
 * @return bool
 */
function user_is_friend($user_guid, $friend_guid) {
	return check_entity_relationship($user_guid, "friend", $friend_guid);
}

/**
 * Obtains a given user's friends
 *
 * @param int    $user_guid The user's GUID
 * @param string $subtype   The subtype of users, if any
 * @param int    $limit     Number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 *
 * @return false|array Either an array of ElggUsers or false, depending on success
 */
function get_user_friends($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = 0) {

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'friend',
		'relationship_guid' => $user_guid,
		'types' => 'user',
		'subtypes' => $subtype,
		'limit' => $limit,
		'offset' => $offset
	));
}

/**
 * Obtains the people who have made a given user a friend
 *
 * @param int    $user_guid The user's GUID
 * @param string $subtype   The subtype of users, if any
 * @param int    $limit     Number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 *
 * @return false|array Either an array of ElggUsers or false, depending on success
 */
function get_user_friends_of($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = 0) {

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'friend',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => TRUE,
		'types' => 'user',
		'subtypes' => $subtype,
		'limit' => $limit,
		'offset' => $offset
	));
}

/**
 * Obtains a list of objects owned by a user
 *
 * @param int    $user_guid The GUID of the owning user
 * @param string $subtype   Optionally, the subtype of objects
 * @param int    $limit     The number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 * @param int    $timelower The earliest time the entity can have been created. Default: all
 * @param int    $timeupper The latest time the entity can have been created. Default: all
 *
 * @return false|array An array of ElggObjects or false, depending on success
 */
function get_user_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = 0, $timelower = 0, $timeupper = 0) {

	$ntt = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => $subtype,
		'owner_guid' => $user_guid,
		'limit' => $limit,
		'offset' => $offset,
		'container_guid' => $user_guid,
		'created_time_lower' => $timelower,
		'created_time_upper' => $timeupper
	));
	return $ntt;
}

/**
 * Counts the objects (optionally of a particular subtype) owned by a user
 *
 * @param int    $user_guid The GUID of the owning user
 * @param string $subtype   Optionally, the subtype of objects
 * @param int    $timelower The earliest time the entity can have been created. Default: all
 * @param int    $timeupper The latest time the entity can have been created. Default: all
 *
 * @return int The number of objects the user owns (of this subtype)
 */
function count_user_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $timelower = 0,
$timeupper = 0) {

	$total = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => $subtype,
		'owner_guid' => $user_guid,
		'count' => TRUE,
		'container_guid' => $user_guid,
		'created_time_lower' => $timelower,
		'created_time_upper' => $timeupper
	));
	return $total;
}

/**
 * Displays a list of user objects of a particular subtype, with navigation.
 *
 * @see elgg_view_entity_list
 *
 * @param int    $user_guid      The GUID of the user
 * @param string $subtype        The object subtype
 * @param int    $limit          The number of entities to display on a page
 * @param bool   $fullview       Whether or not to display the full view (default: true)
 * @param bool   $viewtypetoggle Whether or not to allow gallery view (default: true)
 * @param bool   $pagination     Whether to display pagination (default: true)
 * @param int    $timelower      The earliest time the entity can have been created. Default: all
 * @param int    $timeupper      The latest time the entity can have been created. Default: all
 *
 * @return string The list in a form suitable to display
 */
function list_user_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$fullview = true, $viewtypetoggle = true, $pagination = true, $timelower = 0, $timeupper = 0) {

	$offset = (int) get_input('offset');
	$limit = (int) $limit;
	$count = (int) count_user_objects($user_guid, $subtype, $timelower, $timeupper);
	$entities = get_user_objects($user_guid, $subtype, $limit, $offset, $timelower, $timeupper);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle,
		$pagination);
}

/**
 * Obtains a list of objects owned by a user's friends
 *
 * @param int    $user_guid The GUID of the user to get the friends of
 * @param string $subtype   Optionally, the subtype of objects
 * @param int    $limit     The number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 * @param int    $timelower The earliest time the entity can have been created. Default: all
 * @param int    $timeupper The latest time the entity can have been created. Default: all
 *
 * @return false|array An array of ElggObjects or false, depending on success
 */
function get_user_friends_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = 0, $timelower = 0, $timeupper = 0) {

	if ($friends = get_user_friends($user_guid, "", 999999, 0)) {
		$friendguids = array();
		foreach ($friends as $friend) {
			$friendguids[] = $friend->getGUID();
		}
		return elgg_get_entities(array(
			'type' => 'object',
			'subtype' => $subtype,
			'owner_guids' => $friendguids,
			'limit' => $limit,
			'offset' => $offset,
			'container_guids' => $friendguids,
			'created_time_lower' => $timelower,
			'created_time_upper' => $timeupper
		));
	}
	return FALSE;
}

/**
 * Counts the number of objects owned by a user's friends
 *
 * @param int    $user_guid The GUID of the user to get the friends of
 * @param string $subtype   Optionally, the subtype of objects
 * @param int    $timelower The earliest time the entity can have been created. Default: all
 * @param int    $timeupper The latest time the entity can have been created. Default: all
 *
 * @return int The number of objects
 */
function count_user_friends_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE,
$timelower = 0, $timeupper = 0) {

	if ($friends = get_user_friends($user_guid, "", 999999, 0)) {
		$friendguids = array();
		foreach ($friends as $friend) {
			$friendguids[] = $friend->getGUID();
		}
		return elgg_get_entities(array(
			'type' => 'object',
			'subtype' => $subtype,
			'owner_guids' => $friendguids,
			'count' => TRUE,
			'container_guids' => $friendguids,
			'created_time_lower' => $timelower,
			'created_time_upper' => $timeupper
		));
	}
	return 0;
}

/**
 * Displays a list of a user's friends' objects of a particular subtype, with navigation.
 *
 * @see elgg_view_entity_list
 *
 * @param int    $user_guid      The GUID of the user
 * @param string $subtype        The object subtype
 * @param int    $limit          The number of entities to display on a page
 * @param bool   $fullview       Whether or not to display the full view (default: true)
 * @param bool   $viewtypetoggle Whether or not to allow you to flip to gallery mode (default: true)
 * @param bool   $pagination     Whether to display pagination (default: true)
 * @param int    $timelower      The earliest time the entity can have been created. Default: all
 * @param int    $timeupper      The latest time the entity can have been created. Default: all
 *
 * @return string The list in a form suitable to display
 */
function list_user_friends_objects($user_guid, $subtype = "", $limit = 10, $fullview = true,
$viewtypetoggle = true, $pagination = true, $timelower = 0, $timeupper = 0) {

	$offset = (int) get_input('offset');
	$limit = (int) $limit;
	$count = (int) count_user_friends_objects($user_guid, $subtype, $timelower, $timeupper);

	$entities = get_user_friends_objects($user_guid, $subtype, $limit, $offset,
		$timelower, $timeupper);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview,
		$viewtypetoggle, $pagination);
}

/**
 * Get user objects by an array of metadata
 *
 * @param int    $user_guid The GUID of the owning user
 * @param string $subtype   Optionally, the subtype of objects
 * @param array  $metadata  An array of metadata
 * @param int    $limit     The number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 *
 * @return false|array An array of ElggObjects or false, depending on success
 */
function get_user_objects_by_metadata($user_guid, $subtype = "", $metadata = array(),
$limit = 0, $offset = 0) {
	return get_entities_from_metadata_multi($metadata, "object", $subtype, $user_guid,
		$limit, $offset);
}

/**
 * Get a user object from a GUID.
 *
 * This function returns an ElggUser from a given GUID.
 *
 * @param int $guid The GUID
 *
 * @return ElggUser|false
 */
function get_user($guid) {
	// Fixes "Exception thrown without stack frame" when db_select fails
	if (!empty($guid)) {
		$result = get_entity($guid);
	}

	if ((!empty($result)) && (!($result instanceof ElggUser))) {
		return false;
	}

	if (!empty($result)) {
		return $result;
	}

	return false;
}

/**
 * Get user by username
 *
 * @param string $username The user's username
 *
 * @return ElggUser|false Depending on success
 */
function get_user_by_username($username) {
	global $CONFIG, $USERNAME_TO_GUID_MAP_CACHE;

	$username = sanitise_string($username);
	$access = get_access_sql_suffix('e');

	// Caching
	if ((isset($USERNAME_TO_GUID_MAP_CACHE[$username]))
	&& (retrieve_cached_entity($USERNAME_TO_GUID_MAP_CACHE[$username]))) {
		return retrieve_cached_entity($USERNAME_TO_GUID_MAP_CACHE[$username]);
	}

	$query = "SELECT e.* from {$CONFIG->dbprefix}users_entity u
		join {$CONFIG->dbprefix}entities e on e.guid=u.guid
		where u.username='$username' and $access ";

	$row = get_data_row($query);
	if ($row) {
		$USERNAME_TO_GUID_MAP_CACHE[$username] = $row->guid;
		return new ElggUser($row);
	}

	return false;
}

/**
 * Get user by session code
 *
 * @param string $code The session code
 *
 * @return ElggUser|false Depending on success
 */
function get_user_by_code($code) {
	global $CONFIG, $CODE_TO_GUID_MAP_CACHE;

	$code = sanitise_string($code);

	$access = get_access_sql_suffix('e');

	// Caching
	if ((isset($CODE_TO_GUID_MAP_CACHE[$code]))
	&& (retrieve_cached_entity($CODE_TO_GUID_MAP_CACHE[$code]))) {

		return retrieve_cached_entity($CODE_TO_GUID_MAP_CACHE[$code]);
	}

	$query = "SELECT e.* from {$CONFIG->dbprefix}users_entity u
		join {$CONFIG->dbprefix}entities e on e.guid=u.guid
		where u.code='$code' and $access";

	$row = get_data_row($query);
	if ($row) {
		$CODE_TO_GUID_MAP_CACHE[$code] = $row->guid;
		return new ElggUser($row);
	}

	return false;
}

/**
 * Get an array of users from their
 *
 * @param string $email Email address.
 *
 * @return Array of users
 */
function get_user_by_email($email) {
	global $CONFIG;

	$email = sanitise_string($email);

	$access = get_access_sql_suffix('e');

	$query = "SELECT e.* from {$CONFIG->dbprefix}entities e
		join {$CONFIG->dbprefix}users_entity u on e.guid=u.guid
		where email='$email' and $access";

	return get_data($query, 'entity_row_to_elggstar');
}

/**
 * Searches for a user based on a complete or partial name or username.
 *
 * @param string  $criteria The partial or full name or username.
 * @param int     $limit    Limit of the search.
 * @param int     $offset   Offset.
 * @param string  $order_by The order.
 * @param boolean $count    Whether to return the count of results or just the results.
 *
 * @return mixed
 * @deprecated 1.7
 */
function search_for_user($criteria, $limit = 10, $offset = 0, $order_by = "", $count = false) {
	elgg_deprecated_notice('search_for_user() was deprecated by new search.', 1.7);
	global $CONFIG;

	$criteria = sanitise_string($criteria);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$order_by = sanitise_string($order_by);

	$access = get_access_sql_suffix("e");

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT e.* ";
	}
	$query .= "from {$CONFIG->dbprefix}entities e
		join {$CONFIG->dbprefix}users_entity u on e.guid=u.guid where ";

	$query .= "(u.name like \"%{$criteria}%\" or u.username like \"%{$criteria}%\")";
	$query .= " and $access";

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit";
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($count = get_data_row($query)) {
			return $count->total;
		}
	}
	return false;
}

/**
 * Displays a list of user objects that have been searched for.
 *
 * @see elgg_view_entity_list
 *
 * @param string $tag   Search criteria
 * @param int    $limit The number of entities to display on a page
 *
 * @return string The list in a form suitable to display
 *
 * @deprecated 1.7
 */
function list_user_search($tag, $limit = 10) {
	elgg_deprecated_notice('list_user_search() deprecated by new search', 1.7);
	$offset = (int) get_input('offset');
	$limit = (int) $limit;
	$count = (int) search_for_user($tag, 10, 0, '', true);
	$entities = search_for_user($tag, $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, false);
}

/**
 * A function that returns a maximum of $limit users who have done something within the last
 * $seconds seconds.
 *
 * @param int $seconds Number of seconds (default 600 = 10min)
 * @param int $limit   Limit, default 10.
 * @param int $offset  Offset, defualt 0.
 *
 * @return mixed
 */
function find_active_users($seconds = 600, $limit = 10, $offset = 0) {
	global $CONFIG;

	$seconds = (int)$seconds;
	$limit = (int)$limit;
	$offset = (int)$offset;

	$time = time() - $seconds;

	$access = get_access_sql_suffix("e");

	$query = "SELECT distinct e.* from {$CONFIG->dbprefix}entities e
		join {$CONFIG->dbprefix}users_entity u on e.guid = u.guid
		where u.last_action >= {$time} and $access
		order by u.last_action desc limit {$offset}, {$limit}";

	return get_data($query, "entity_row_to_elggstar");
}

/**
 * Generate and send a password request email to a given user's registered email address.
 *
 * @param int $user_guid User GUID
 *
 * @return bool
 */
function send_new_password_request($user_guid) {
	global $CONFIG;

	$user_guid = (int)$user_guid;

	$user = get_entity($user_guid);
	if ($user) {
		// generate code
		$code = generate_random_cleartext_password();
		//create_metadata($user_guid, 'conf_code', $code, '', 0, ACCESS_PRIVATE);
		set_private_setting($user_guid, 'passwd_conf_code', $code);

		// generate link
		$link = $CONFIG->site->url . "pg/resetpassword?u=$user_guid&c=$code";

		// generate email
		$email = elgg_echo('email:resetreq:body', array($user->name, $_SERVER['REMOTE_ADDR'], $link));

		return notify_user($user->guid, $CONFIG->site->guid,
			elgg_echo('email:resetreq:subject'), $email, NULL, 'email');
	}

	return false;
}

/**
 * Low level function to reset a given user's password.
 *
 * This can only be called from execute_new_password_request().
 *
 * @param int    $user_guid The user.
 * @param string $password  Text (which will then be converted into a hash and stored)
 *
 * @return bool
 */
function force_user_password_reset($user_guid, $password) {
	global $CONFIG;

	if (call_gatekeeper('execute_new_password_request', __FILE__)) {
		$user = get_entity($user_guid);

		if ($user) {
			$salt = generate_random_cleartext_password(); // Reset the salt
			$user->salt = $salt;

			$hash = generate_user_password($user, $password);

			$query = "UPDATE {$CONFIG->dbprefix}users_entity
				set password='$hash', salt='$salt' where guid=$user_guid";
			return update_data($query);
		}
	}

	return false;
}

/**
 * Validate and execute a password reset for a user.
 *
 * @param int    $user_guid The user id
 * @param string $conf_code Confirmation code as sent in the request email.
 *
 * @return mixed
 */
function execute_new_password_request($user_guid, $conf_code) {
	global $CONFIG;

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);

	$saved_code = get_private_setting($user_guid, 'passwd_conf_code');

	if ($user && $saved_code && $saved_code == $conf_code) {
		$password = generate_random_cleartext_password();

		if (force_user_password_reset($user_guid, $password)) {
			remove_private_setting($user_guid, 'passwd_conf_code');

			$email = elgg_echo('email:resetpassword:body', array($user->name, $password));

			return notify_user($user->guid, $CONFIG->site->guid,
				elgg_echo('email:resetpassword:subject'), $email, NULL, 'email');
		}
	}

	return FALSE;
}

/**
 * Handles pages for password reset requests.
 *
 * @param array $page Pages array
 *
 * @return void
 */
function elgg_user_resetpassword_page_handler($page) {
	global $CONFIG;

	$user_guid = get_input('u');
	$code = get_input('c');

	$user = get_entity($user_guid);

	// don't check code here to avoid automated attacks
	if (!$user instanceof ElggUser) {
		register_error(elgg_echo('user:passwordreset:unknown_user'));
		forward();
	}

	$form_body = elgg_echo('user:resetpassword:reset_password_confirm') . "<br />";

	$form_body .= elgg_view('input/hidden', array(
		'internalname' => 'u',
		'value' => $user_guid
	));

	$form_body .= elgg_view('input/hidden', array(
		'internalname' => 'c',
		'value' => $code
	));

	$form_body .= elgg_view('input/submit', array(
		'value' => elgg_echo('resetpassword')
	));

	$form .= elgg_view('input/form', array(
		'body' => $form_body,
		'action' => 'action/user/passwordreset'
	));

	$title = elgg_echo('resetpassword');
	$content = elgg_view_title(elgg_echo('resetpassword')) . $form;

	echo elgg_view_page($title, elgg_view_layout('one_column', $content));
}

/**
 * Simple function that will generate a random clear text password
 * suitable for feeding into generate_user_password().
 *
 * @see generate_user_password
 *
 * @return string
 */
function generate_random_cleartext_password() {
	return substr(md5(microtime() . rand()), 0, 8);
}

/**
 * Generate a password for a user, currently uses MD5.
 *
 * @param ElggUser $user     The user this is being generated for.
 * @param string   $password Password in clear text
 *
 * @return string
 */
function generate_user_password(ElggUser $user, $password) {
	return md5($password . $user->salt);
}

/**
 * Simple function which ensures that a username contains only valid characters.
 *
 * This should only permit chars that are valid on the file system as well.
 *
 * @param string $username Username
 *
 * @return bool
 * @throws RegistrationException on invalid
 */
function validate_username($username) {
	global $CONFIG;

	// Basic, check length
	if (!isset($CONFIG->minusername)) {
		$CONFIG->minusername = 4;
	}

	if (strlen($username) < $CONFIG->minusername) {
		throw new RegistrationException(elgg_echo('registration:usernametooshort'));
	}

	// Blacklist for bad characters (partially nicked from mediawiki)
	$blacklist = '/[' .
		'\x{0080}-\x{009f}' . // iso-8859-1 control chars
		'\x{00a0}' .          // non-breaking space
		'\x{2000}-\x{200f}' . // various whitespace
		'\x{2028}-\x{202f}' . // breaks and control chars
		'\x{3000}' .          // ideographic space
		'\x{e000}-\x{f8ff}' . // private use
		']/u';

	if (
		preg_match($blacklist, $username)
	) {
		throw new RegistrationException(elgg_echo('registration:invalidchars'));
	}

	// Belts and braces
	// @todo Tidy into main unicode
	$blacklist2 = '\'/\\"*& ?#%^(){}[]~?<>;|¬`@-+=';

	for ($n = 0; $n < strlen($blacklist2); $n++) {
		if (strpos($username, $blacklist2[$n]) !== false) {
			$msg = elgg_echo('registration:invalidchars', array($blacklist2[$n], $blacklist2));
			throw new RegistrationException($msg);
		}
	}

	$result = true;
	return trigger_plugin_hook('registeruser:validate:username', 'all',
		array('username' => $username), $result);
}

/**
 * Simple validation of a password.
 *
 * @param string $password Clear text password
 *
 * @return bool
 * @throws RegistrationException on invalid
 */
function validate_password($password) {
	global $CONFIG;

	if (strlen($password) < $CONFIG->min_password_length) {
		throw new RegistrationException(elgg_echo('registration:passwordtooshort'));
	}

	$result = true;
	return trigger_plugin_hook('registeruser:validate:password', 'all',
		array('password' => $password), $result);
}

/**
 * Simple validation of a email.
 *
 * @param string $address Email address
 *
 * @throws RegistrationException on invalid
 * @return bool
 */
function validate_email_address($address) {
	if (!is_email_address($address)) {
		throw new RegistrationException(elgg_echo('registration:notemail'));
	}

	// Got here, so lets try a hook (defaulting to ok)
	$result = true;
	return trigger_plugin_hook('registeruser:validate:email', 'all',
		array('email' => $address), $result);
}

/**
 * Registers a user, returning false if the username already exists
 *
 * @param string $username              The username of the new user
 * @param string $password              The password
 * @param string $name                  The user's display name
 * @param string $email                 Their email address
 * @param bool   $allow_multiple_emails Allow the same email address to be
 *                                      registered multiple times?
 * @param int    $friend_guid           GUID of a user to friend once fully registered
 * @param string $invitecode            An invite code from a friend
 *
 * @return int|false The new user's GUID; false on failure
 */
function register_user($username, $password, $name, $email,
$allow_multiple_emails = false, $friend_guid = 0, $invitecode = '') {

	// Load the configuration
	global $CONFIG;

	// no need to trim password.
	$username = trim($username);
	$name = trim(strip_tags($name));
	$email = trim($email);

	// A little sanity checking
	if (empty($username)
	|| empty($password)
	|| empty($name)
	|| empty($email)) {
		return false;
	}

	// Make sure a user with conflicting details hasn't registered and been disabled
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);

	if (!validate_email_address($email)) {
		throw new RegistrationException(elgg_echo('registration:emailnotvalid'));
	}

	if (!validate_password($password)) {
		throw new RegistrationException(elgg_echo('registration:passwordnotvalid'));
	}

	if (!validate_username($username)) {
		throw new RegistrationException(elgg_echo('registration:usernamenotvalid'));
	}

	if ($user = get_user_by_username($username)) {
		throw new RegistrationException(elgg_echo('registration:userexists'));
	}

	if ((!$allow_multiple_emails) && (get_user_by_email($email))) {
		throw new RegistrationException(elgg_echo('registration:dupeemail'));
	}

	access_show_hidden_entities($access_status);

	// Create user
	$user = new ElggUser();
	$user->username = $username;
	$user->email = $email;
	$user->name = $name;
	$user->access_id = ACCESS_PUBLIC;
	$user->salt = generate_random_cleartext_password(); // Note salt generated before password!
	$user->password = generate_user_password($user, $password);
	$user->owner_guid = 0; // Users aren't owned by anyone, even if they are admin created.
	$user->container_guid = 0; // Users aren't contained by anyone, even if they are admin created.
	$user->save();

	// If $friend_guid has been set, make mutual friends
	if ($friend_guid) {
		if ($friend_user = get_user($friend_guid)) {
			if ($invitecode == generate_invite_code($friend_user->username)) {
				$user->addFriend($friend_guid);
				$friend_user->addFriend($user->guid);

				// @todo Should this be in addFriend?
				add_to_river('friends/river/create', 'friend', $user->getGUID(), $friend_guid);
				add_to_river('friends/river/create', 'friend', $friend_guid, $user->getGUID());
			}
		}
	}

	// Check to see if we've registered the first admin yet.
	// If not, this is the first admin user!
	$have_admin = datalist_get('admin_registered');

	if (!$have_admin) {
		// makeAdmin() calls ElggUser::canEdit().
		// right now no one is logged in and so canEdit() returns false.
		// instead of making an override for this one instance that is called on every
		// canEdit() call, just override the access system to set the first admin user.
		// @todo remove this when Cash merges in the new installer
		$ia = elgg_set_ignore_access(TRUE);
		$user->makeAdmin();
		datalist_set('admin_registered', 1);
		elgg_set_ignore_access($ia);
	}

	// Turn on email notifications by default
	set_user_notification_setting($user->getGUID(), 'email', true);

	return $user->getGUID();
}

/**
 * Generates a unique invite code for a user
 *
 * @param string $username The username of the user sending the invitation
 *
 * @return string Invite code
 */
function generate_invite_code($username) {
	$secret = datalist_get('__site_secret__');
	return md5($username . $secret);
}

/**
 * Adds collection submenu items
 *
 * @return void
 */
function collections_submenu_items() {
	global $CONFIG;
	$user = get_loggedin_user();

	add_submenu_item(elgg_echo('friends:collections'),
		$CONFIG->wwwroot . "pg/collections/" . $user->username);

	add_submenu_item(elgg_echo('friends:collections:add'), $CONFIG->wwwroot . "pg/collections/add");
}

/**
 * Page handler for friends
 *
 * @param array $page_elements Page elements
 *
 * @return void
 */
function friends_page_handler($page_elements) {
	if (isset($page_elements[0]) && $user = get_user_by_username($page_elements[0])) {
		set_page_owner($user->getGUID());
	}
	if (get_loggedin_userid() == elgg_get_page_owner_guid()) {
		// disabled for now as we no longer use friends collections (replaced by shared access)
		// collections_submenu_items();
	}
	require_once(dirname(dirname(dirname(__FILE__))) . "/pages/friends/index.php");
}

/**
 * Page handler for friends of
 *
 * @param array $page_elements Page elements
 *
 * @return void
 */
function friends_of_page_handler($page_elements) {
	if (isset($page_elements[0]) && $user = get_user_by_username($page_elements[0])) {
		set_page_owner($user->getGUID());
	}
	if (get_loggedin_userid() == elgg_get_page_owner_guid()) {
		// disabled for now as we no longer use friends collections (replaced by shared access)
		// collections_submenu_items();
	}
	require_once(dirname(dirname(dirname(__FILE__))) . "/pages/friends/of.php");
}

/**
 * Page handler for friends collections
 *
 * @param array $page_elements Page elements
 *
 * @return void
 */
function collections_page_handler($page_elements) {
	if (isset($page_elements[0])) {
		if ($page_elements[0] == "add") {
			set_page_owner(get_loggedin_userid());
			collections_submenu_items();
			require_once(dirname(dirname(dirname(__FILE__))) . "/pages/friends/add.php");
		} else {
			if ($user = get_user_by_username($page_elements[0])) {
				set_page_owner($user->getGUID());
				if (get_loggedin_userid() == elgg_get_page_owner_guid()) {
					collections_submenu_items();
				}
				require_once(dirname(dirname(dirname(__FILE__))) . "/pages/friends/collections.php");
			}
		}
	}
}

/**
 * Page handler for dashboard
 *
 * @param array $page_elements Page elements
 *
 * @return void
 */
function dashboard_page_handler($page_elements) {
	require_once(dirname(dirname(dirname(__FILE__))) . "/pages/dashboard/index.php");
}


/**
 * Page handler for registration
 *
 * @param array $page_elements Page elements
 *
 * @return void
 */
function registration_page_handler($page_elements) {
	require_once(dirname(dirname(dirname(__FILE__))) . "/pages/account/register.php");
}

/**
 * Display a login box.
 *
 * This is a fallback for non-JS users who click on the
 * dropdown login link.
 *
 * @return void
 * @todo finish
 */
function elgg_user_login_page_handler() {
	$content = elgg_view_layout('one_column', elgg_view('account/login_box'));
	$content = '
	<div id="elgg_content" class="clearfix">
	' .	elgg_view('account/login_box') . '
	</div>
	';
	echo elgg_view_page('test', $content);
}

/**
 * Sets the last action time of the given user to right now.
 *
 * @param int $user_guid The user GUID
 *
 * @return void
 */
function set_last_action($user_guid) {
	$user_guid = (int) $user_guid;
	global $CONFIG;
	$time = time();

	$query = "UPDATE {$CONFIG->dbprefix}users_entity
		set prev_last_action = last_action,
		last_action = {$time} where guid = {$user_guid}";

	execute_delayed_write_query($query);
}

/**
 * Sets the last logon time of the given user to right now.
 *
 * @param int $user_guid The user GUID
 *
 * @return boid
 */
function set_last_login($user_guid) {
	$user_guid = (int) $user_guid;
	global $CONFIG;
	$time = time();

	$query = "UPDATE {$CONFIG->dbprefix}users_entity
		set prev_last_login = last_login, last_login = {$time} where guid = {$user_guid}";

	execute_delayed_write_query($query);
}

/**
 * Creates a relationship between this site and the user.
 *
 * @param string   $event       create
 * @param string   $object_type user
 * @param ElggUser $object      User object
 *
 * @return bool
 */
function user_create_hook_add_site_relationship($event, $object_type, $object) {
	global $CONFIG;

	add_entity_relationship($object->getGUID(), 'member_of_site', $CONFIG->site->getGUID());
}

/**
 * Sets up user-related menu items
 *
 * @return void
 */
function users_pagesetup() {
	// Load config
	global $CONFIG;

	//add submenu options
	if (elgg_get_context() == "friends" || elgg_get_context() == "friendsof") {
		// || elgg_get_context() == "collections") { - disabled as we no longer use collections

		add_submenu_item(elgg_echo('friends'), $CONFIG->wwwroot . "pg/friends/"
			. elgg_get_page_owner()->username);

		add_submenu_item(elgg_echo('friends:of'), $CONFIG->wwwroot . "pg/friendsof/"
			. elgg_get_page_owner()->username);

		if (is_plugin_enabled('members')) {
			add_submenu_item(elgg_echo('members:browse'), $CONFIG->wwwroot . "mod/members/index.php");
		}
	}
}

/**
 * Users initialisation function, which establishes the page handler
 *
 * @return void
 */
function users_init() {
	// Load config
	global $CONFIG;

	// add Friends to tools menu - if profile mod is running
	// now added to toolbar
	/*
	if ( isloggedin() && is_plugin_enabled('profile') ) {
		$user = get_loggedin_user();
		add_menu(elgg_echo('friends'), $CONFIG->wwwroot .
			"pg/friends/" . $user->username, array(), 'core:friends');
	}
	*/

	register_page_handler('friends', 'friends_page_handler');
	register_page_handler('friendsof', 'friends_of_page_handler');
	register_page_handler('dashboard', 'dashboard_page_handler');
	register_page_handler('register', 'registration_page_handler');
	register_page_handler('resetpassword', 'elgg_user_resetpassword_page_handler');
	register_page_handler('login', 'elgg_user_login_page_handler');

	register_action("register", true);
	register_action("useradd", true);
	register_action("friends/add");
	register_action("friends/remove");
	//register_action('friends/addcollection');
	//register_action('friends/deletecollection');
	//register_action('friends/editcollection');
	//register_action("user/spotlight");

	register_action("usersettings/save");

	register_action("user/passwordreset", TRUE);
	register_action("user/requestnewpassword", TRUE);

	// User name change
	extend_elgg_settings_page('user/settings/name', 'usersettings/user', 1);
	//register_action("user/name");

	// User password change
	extend_elgg_settings_page('user/settings/password', 'usersettings/user', 1);
	//register_action("user/password");

	// Add email settings
	extend_elgg_settings_page('user/settings/email', 'usersettings/user', 1);
	//register_action("email/save");

	// Add language settings
	extend_elgg_settings_page('user/settings/language', 'usersettings/user', 1);

	// Add default access settings
	extend_elgg_settings_page('user/settings/default_access', 'usersettings/user', 1);

	//register_action("user/language");

	// Register the user type
	register_entity_type('user', '');

	register_plugin_hook('usersettings:save', 'user', 'users_settings_save');

	register_elgg_event_handler('create', 'user', 'user_create_hook_add_site_relationship');
}

/**
 * Returns a formatted list of users suitable for injecting into search.
 *
 * @deprecated 1.7
 *
 * @param string $hook        Hook name
 * @param string $user        User?
 * @param mixed  $returnvalue Previous hook's return value
 * @param mixed  $tag         Tag to search against
 *
 * @return void
 */
function search_list_users_by_name($hook, $user, $returnvalue, $tag) {
	elgg_deprecated_notice('search_list_users_by_name() was deprecated by new search', 1.7);
	// Change this to set the number of users that display on the search page
	$threshold = 4;

	$object = get_input('object');

	if (!get_input('offset') && (empty($object) || $object == 'user')) {
		if ($users = search_for_user($tag, $threshold)) {
			$countusers = search_for_user($tag, 0, 0, "", true);

			$return = elgg_view('user/search/startblurb', array('count' => $countusers, 'tag' => $tag));
			foreach ($users as $user) {
				$return .= elgg_view_entity($user);
			}

			$vars = array('count' => $countusers, 'threshold' => $threshold, 'tag' => $tag);
			$return .= elgg_view('user/search/finishblurb', $vars);
			return $return;

		}
	}
}

/**
 * Saves user settings by directly including actions.
 *
 * @todo this is dirty.
 *
 * @return void
 */
function users_settings_save() {
	global $CONFIG;
	include($CONFIG->path . "actions/user/name.php");
	include($CONFIG->path . "actions/user/password.php");
	include($CONFIG->path . "actions/email/save.php");
	include($CONFIG->path . "actions/user/language.php");
	include($CONFIG->path . "actions/user/default_access.php");
}

/**
 * Runs unit tests for ElggObject
 *
 * @param sting  $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 */
function users_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/objects/users.php";
	return $value;
}

register_elgg_event_handler('init', 'system', 'users_init', 0);
register_elgg_event_handler('pagesetup', 'system', 'users_pagesetup', 0);
register_plugin_hook('unit_test', 'system', 'users_test');