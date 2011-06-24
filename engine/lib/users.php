<?php
/**
 * Elgg users
 * Functions to manage multiple or single users in an Elgg install
 *
 * @package Elgg.Core
 * @subpackage DataModel.User
 */

/// Map a username to a cached GUID
global $USERNAME_TO_GUID_MAP_CACHE;
$USERNAME_TO_GUID_MAP_CACHE = array();

/// Map a user code to a cached GUID
global $CODE_TO_GUID_MAP_CACHE;
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
 * Create or update the entities table for a given user.
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
				if (elgg_trigger_event('update', $entity->type, $entity)) {
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
				if (elgg_trigger_event('create', $entity->type, $entity)) {
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
		if (elgg_trigger_event('disable', $entity->type, $entity)) {
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
		if (elgg_trigger_event('ban', 'user', $user)) {
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
		if (elgg_trigger_event('unban', 'user', $user)) {
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
		if (elgg_trigger_event('make_admin', 'user', $user)) {

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
		if (elgg_trigger_event('remove_admin', 'user', $user)) {

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
	return check_entity_relationship($user_guid, "friend", $friend_guid) !== false;
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
 * @param bool   $full_view      Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle Whether or not to allow you to flip to gallery mode (default: true)
 * @param bool   $pagination     Whether to display pagination (default: true)
 * @param int    $timelower      The earliest time the entity can have been created. Default: all
 * @param int    $timeupper      The latest time the entity can have been created. Default: all
 *
 * @return string The list in a form suitable to display
 */
function list_user_friends_objects($user_guid, $subtype = "", $limit = 10, $full_view = true,
$listtypetoggle = true, $pagination = true, $timelower = 0, $timeupper = 0) {

	$offset = (int) get_input('offset');
	$limit = (int) $limit;
	$count = (int) count_user_friends_objects($user_guid, $subtype, $timelower, $timeupper);

	$entities = get_user_friends_objects($user_guid, $subtype, $limit, $offset,
		$timelower, $timeupper);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $full_view,
		$listtypetoggle, $pagination);
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

	$entity = get_data_row($query, 'entity_row_to_elggstar');
	if ($entity) {
		$USERNAME_TO_GUID_MAP_CACHE[$username] = $entity->guid;
	}

	return $entity;
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

	$entity = get_data_row($query, 'entity_row_to_elggstar');
	if ($entity) {
		$CODE_TO_GUID_MAP_CACHE[$code] = $entity->guid;
	}

	return $entity;
}

/**
 * Get an array of users from their email addresses
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
 * A function that returns a maximum of $limit users who have done something within the last
 * $seconds seconds or the total count of active users.
 *
 * @param int $seconds Number of seconds (default 600 = 10min)
 * @param int $limit   Limit, default 10.
 * @param int $offset  Offset, default 0.
 * @param bool $count  Count, default false.
 *
 * @return mixed
 */
function find_active_users($seconds = 600, $limit = 10, $offset = 0, $count = false) {
	$seconds = (int)$seconds;
	$limit = (int)$limit;
	$offset = (int)$offset;
	$params = array('seconds' => $seconds, 'limit' => $limit, 'offset' => $offset, 'count' => $count);
	$data = elgg_trigger_plugin_hook('find_active_users', 'system', $params, NULL);
	if (!$data) {
		global $CONFIG;

		$time = time() - $seconds;

		$data = elgg_get_entities(array(
			'type' => 'user', 
			'limit' => $limit,
			'offset' => $offset,
			'count' => $count,
			'joins' => array("join {$CONFIG->dbprefix}users_entity u on e.guid = u.guid"),
			'wheres' => array("u.last_action >= {$time}"),
			'order_by' => "u.last_action desc"
		));
	}
	return $data;
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
		$user->setPrivateSetting('passwd_conf_code', $code);


		// generate link
		$link = $CONFIG->site->url . "resetpassword?u=$user_guid&c=$code";

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

	$user = get_entity($user_guid);

	if ($user) {
		$salt = generate_random_cleartext_password(); // Reset the salt
		$user->salt = $salt;

		$hash = generate_user_password($user, $password);

		$query = "UPDATE {$CONFIG->dbprefix}users_entity
			set password='$hash', salt='$salt' where guid=$user_guid";
		return update_data($query);
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

	if ($user) {
		$saved_code = $user->getPrivateSetting('passwd_conf_code');

		if ($saved_code && $saved_code == $conf_code) {
			$password = generate_random_cleartext_password();

			if (force_user_password_reset($user_guid, $password)) {
				remove_private_setting($user_guid, 'passwd_conf_code');

				$email = elgg_echo('email:resetpassword:body', array($user->name, $password));

				return notify_user($user->guid, $CONFIG->site->guid,
					elgg_echo('email:resetpassword:subject'), $email, NULL, 'email');
			}
		}
	}

	return FALSE;
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
		$msg = elgg_echo('registration:usernametooshort', array($CONFIG->minusername));
		throw new RegistrationException($msg);
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
	return elgg_trigger_plugin_hook('registeruser:validate:username', 'all',
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

	if (!isset($CONFIG->min_password_length)) {
		$CONFIG->min_password_length = 6;
	}

	if (strlen($password) < $CONFIG->min_password_length) {
		$msg = elgg_echo('registration:passwordtooshort', array($CONFIG->min_password_length));
		throw new RegistrationException($msg);
	}

	$result = true;
	return elgg_trigger_plugin_hook('registeruser:validate:password', 'all',
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
	return elgg_trigger_plugin_hook('registeruser:validate:email', 'all',
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
 * Set the validation status for a user.
 *
 * @param int    $user_guid The user's GUID
 * @param bool   $status    Validated (true) or unvalidated (false)
 * @param string $method    Optional method to say how a user was validated
 * @return bool
 * @since 1.8.0
 */
function elgg_set_user_validation_status($user_guid, $status, $method = '') {
	$result1 = create_metadata($user_guid, 'validated', $status, '', 0, ACCESS_PUBLIC, false);
	$result2 = create_metadata($user_guid, 'validated_method', $method, '', 0, ACCESS_PUBLIC, false);
	if ($result1 && $result2) {
		return true;
	} else {
		return false;
	}
}

/**
 * Gets the validation status of a user.
 *
 * @param int $user_guid The user's GUID
 * @return bool|null Null means status was not set for this user.
 * @since 1.8.0
 */
function elgg_get_user_validation_status($user_guid) {
	$md = elgg_get_metadata(array(
		'guid' => $user_guid,
		'metadata_name' => 'validated'
	));
	if ($md == false) {
		return;
	}

	if ($md->value) {
		return true;
	}

	return false;
}

/**
 * Adds collection submenu items
 *
 * @return void
 */
function collections_submenu_items() {

	$user = elgg_get_logged_in_user_entity();

	elgg_register_menu_item('page', array(
		'name' => 'friends:view:collections',
		'text' => elgg_echo('friends:collections'),
		'href' => "collections/$user->username",
	));
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
		elgg_set_page_owner_guid($user->getGUID());
	}
	if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
		collections_submenu_items();
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
	elgg_set_context('friends');
	if (isset($page_elements[0]) && $user = get_user_by_username($page_elements[0])) {
		set_page_owner($user->getGUID());
	}
	if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
		collections_submenu_items();
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
	elgg_set_context('friends');
	$base = elgg_get_config('path');
	if (isset($page_elements[0])) {
		if ($page_elements[0] == "add") {
			set_page_owner(elgg_get_logged_in_user_guid());
			collections_submenu_items();
			require_once "{$base}pages/friends/collections/add.php";
		} else {
			$user = get_user_by_username($page_elements[0]);
			if ($user) {
				set_page_owner($user->getGUID());
				if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
					collections_submenu_items();
				}
				require_once "{$base}pages/friends/collections/view.php";
			}
		}
	}
}

/**
 * Page handler for account related pages
 *
 * @param array  $page_elements Page elements
 * @param string $handler The handler string
 *
 * @return void
 */
function elgg_user_account_page_handler($page_elements, $handler) {

	$base_dir = elgg_get_root_path() . 'pages/account';
	switch ($handler) {
		case 'forgotpassword':
			require_once("$base_dir/forgotten_password.php");
			break;
		case 'resetpassword':
			require_once("$base_dir/reset_password.php");
			break;
		case 'register':
			require_once("$base_dir/register.php");
			break;
	}
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
	$login_box = elgg_view('core/account/login_box');
	$content = elgg_view_layout('one_column', array('content' => $login_box));
	echo elgg_view_page(elgg_echo('login'), $content);
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
 * Serves the user's avatar
 *
 * @param string $hook
 * @param string $entity_type
 * @param string $returnvalue
 * @param array  $params
 * @return string
 */
function user_avatar_hook($hook, $entity_type, $returnvalue, $params) {
	$user = $params['entity'];
	$size = $params['size'];

	if (isset($user->icontime)) {
		return "avatar/view/$user->username/$size/$user->icontime";
	} else {
		return "_graphics/icons/user/default{$size}.gif";
	}
}

/**
 * Setup the default user hover menu
 */
function elgg_user_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];

	if (elgg_is_logged_in()) {
		if (elgg_get_logged_in_user_guid() != $user->guid) {
			if ($user->isFriend()) {
				$url = "action/friends/remove?friend={$user->guid}";
				$text = elgg_echo('friend:remove');
			} else {
				$url = "action/friends/add?friend={$user->guid}";
				$text = elgg_echo('friend:add');
			}
			$url = elgg_add_action_tokens_to_url($url);
			$item = new ElggMenuItem('addfriend', $text, $url);
			$item->setSection('action');
			$return[] = $item;
		} else {
			$url = "profile/$user->username/edit";
			$item = new ElggMenuItem('profile:edit', elgg_echo('profile:edit'), $url);
			$item->setSection('action');
			$return[] = $item;

			$url = "avatar/edit/$user->username";
			$item = new ElggMenuItem('avatar:edit', elgg_echo('avatar:edit'), $url);
			$item->setSection('action');
			$return[] = $item;
		}
	}

	// prevent admins from banning or deleting themselves
	if (elgg_get_logged_in_user_guid() == $user->guid) {
		return $return;
	}

	if (elgg_is_admin_logged_in()) {
		$actions = array();
		if (!$user->isBanned()) {
			$actions[] = 'ban';
		} else {
			$actions[] = 'unban';
		}
		$actions[] = 'delete';
		$actions[] = 'resetpassword';
		if (!$user->isAdmin()) {
			$actions[] = 'makeadmin';
		} else {
			$actions[] = 'removeadmin';
		}

		foreach ($actions as $action) {
			$url = "action/admin/user/$action?guid={$user->guid}";
			$url = elgg_add_action_tokens_to_url($url);
			$item = new ElggMenuItem($action, elgg_echo($action), $url);
			$item->setSection('admin');
			$item->setLinkClass('elgg-requires-confirmation');

			$return[] = $item;
		}

		$url = "profile/$user->username/edit";
		$item = new ElggMenuItem('profile:edit', elgg_echo('profile:edit'), $url);
		$item->setSection('admin');
		$return[] = $item;
	}

	return $return;
}

function elgg_users_setup_entity_menu($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	if (!elgg_instanceof($entity, 'user')) {
		return $return;
	}

	if ($entity->isBanned()) {
		$banned = elgg_echo('banned');
		$options = array(
			'name' => 'banned',
			'text' => "<span>$banned</span>",
			'href' => false,
			'priority' => 0,
		);
		$return = array(ElggMenuItem::factory($options));
	} else {
		$return = array();
		if (isset($entity->location)) {
			$options = array(
				'name' => 'location',
				'text' => "<span>$entity->location</span>",
				'href' => false,
				'priority' => 150,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * This function loads a set of default fields into the profile, then triggers a hook letting other plugins to edit
 * add and delete fields.
 *
 * Note: This is a secondary system:init call and is run at a super low priority to guarantee that it is called after all
 * other plugins have initialised.
 */
function elgg_profile_fields_setup() {
	global $CONFIG;

	$profile_defaults = array (
		'description' => 'longtext',
		'briefdescription' => 'text',
		'location' => 'location',
		'interests' => 'tags',
		'skills' => 'tags',
		'contactemail' => 'email',
		'phone' => 'text',
		'mobile' => 'text',
		'website' => 'url',
		'twitter' => 'text'
	);

	$loaded_default = array();
	if ($fieldlist = elgg_get_config('profile_custom_fields')) {
		if (!empty($fieldlist)) {
			$fieldlistarray = explode(',', $fieldlist);
			$loaded_defaults = array();
			foreach ($fieldlistarray as $listitem) {
				if ($translation = elgg_get_config("admin_defined_profile_{$listitem}")) {
					$type = elgg_get_config("admin_defined_profile_type_{$listitem}");
					$loaded_defaults["admin_defined_profile_{$listitem}"] = $type;
					add_translation(get_current_language(), array("profile:admin_defined_profile_{$listitem}" => $translation));
				}
			}
		}
	}

	if (count($loaded_defaults)) {
		$CONFIG->profile_using_custom = true;
		$profile_defaults = $loaded_defaults;
	}

	$CONFIG->profile_fields = elgg_trigger_plugin_hook('profile:fields', 'profile', NULL, $profile_defaults);

	// register any tag metadata names
	foreach ($CONFIG->profile_fields as $name => $type) {
		if ($type == 'tags' || $type == 'location' || $type == 'tag') {
			elgg_register_tag_metadata_name($name);
			// register a tag name translation
			add_translation(get_current_language(), array("tag_names:$name" => elgg_echo("profile:$name")));
		}
	}
}

/**
 * Avatar page handler
 *
 * /avatar/edit/<username>
 * /avatar/view/<username>/<size>/<icontime>
 *
 * @param array $page
 */
function elgg_avatar_page_handler($page) {
	global $CONFIG;

	set_input('username', $page[1]);

	if ($page[0] == 'edit') {
		require_once("{$CONFIG->path}pages/avatar/edit.php");
	} else {
		set_input('size', $page[2]);
		require_once("{$CONFIG->path}pages/avatar/view.php");
	}
}

/**
 * Profile page handler
 *
 * @param array $page
 */
function elgg_profile_page_handler($page) {
	global $CONFIG;

	$user = get_user_by_username($page[0]);
	elgg_set_page_owner_guid($user->guid);

	if ($page[1] == 'edit') {
		require_once("{$CONFIG->path}pages/profile/edit.php");
	}
}

/**
 * Sets up user-related menu items
 *
 * @return void
 */
function users_pagesetup() {

	if (elgg_get_page_owner_guid()) {
		$params = array(
			'name' => 'friends',
			'text' => elgg_echo('friends'),
			'href' => 'friends/' . elgg_get_page_owner_entity()->username,
			'contexts' => array('friends')
		);
		elgg_register_menu_item('page', $params);

		$params = array(
			'name' => 'friends:of',
			'text' => elgg_echo('friends:of'),
			'href' => 'friendsof/' . elgg_get_page_owner_entity()->username,
			'contexts' => array('friends')
		);
		elgg_register_menu_item('page', $params);
	}

	// topbar
	$user = elgg_get_logged_in_user_entity();
	if ($user) {
		elgg_register_menu_item('page', array(
			'name' => 'edit_avatar',
			'href' => "avatar/edit/{$user->username}",
			'text' => elgg_echo('avatar:edit'),
			'contexts' => array('profile_edit'),
		));

		elgg_register_menu_item('page', array(
			'name' => 'edit_profile',
			'href' => "profile/{$user->username}/edit",
			'text' => elgg_echo('profile:edit'),
			'contexts' => array('profile_edit'),
		));

		$icon_url = $user->getIconURL('topbar');
		$class = 'elgg-border-plain elgg-transition';
		$title = elgg_echo('profile');
		elgg_register_menu_item('topbar', array(
			'name' => 'profile',
			'href' =>  $user->getURL(),
			'text' => "<img src=\"$icon_url\" alt=\"$user->name\" title=\"$title\" class=\"$class\" />",
			'priority' => 100,
			'link_class' => 'elgg-topbar-avatar',
		));

		elgg_register_menu_item('topbar', array(
			'name' => 'friends',
			'href' => "friends/{$user->username}",
			'text' => elgg_view_icon('users'),
			'title' => elgg_echo('friends'),
			'priority' => 300,
		));

		elgg_register_menu_item('topbar', array(
			'name' => 'usersettings',
			'href' => "settings/user/{$user->username}",
			'text' => elgg_view_icon('settings') . elgg_echo('settings'),
			'priority' => 500,
			'section' => 'alt',
		));

		elgg_register_menu_item('topbar', array(
			'name' => 'logout',
			'href' => "action/logout",
			'text' => elgg_echo('logout'),
			'is_action' => TRUE,
			'priority' => 1000,
			'section' => 'alt',
		));

	}
}

/**
 * Users initialisation function, which establishes the page handler
 *
 * @return void
 */
function users_init() {

	elgg_register_page_handler('friends', 'friends_page_handler');
	elgg_register_page_handler('friendsof', 'friends_of_page_handler');
	elgg_register_page_handler('register', 'elgg_user_account_page_handler');
	elgg_register_page_handler('forgotpassword', 'elgg_user_account_page_handler');
	elgg_register_page_handler('resetpassword', 'elgg_user_account_page_handler');
	elgg_register_page_handler('login', 'elgg_user_login_page_handler');
	elgg_register_page_handler('avatar', 'elgg_avatar_page_handler');
	elgg_register_page_handler('profile', 'elgg_profile_page_handler');
	elgg_register_page_handler('collections', 'collections_page_handler');

	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'elgg_user_hover_menu');

	elgg_register_action('register', '', 'public');
	elgg_register_action('useradd', '', 'public');
	elgg_register_action('friends/add');
	elgg_register_action('friends/remove');
	elgg_register_action('avatar/upload');
	elgg_register_action('avatar/crop');
	elgg_register_action('profile/edit');

	elgg_register_action('friends/collections/add');
	elgg_register_action('friends/collections/delete');
	elgg_register_action('friends/collections/edit');

	elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'user_avatar_hook');

	elgg_register_action('user/passwordreset', '', 'public');
	elgg_register_action('user/requestnewpassword', '', 'public');

	elgg_register_widget_type('friends', elgg_echo('friends'), elgg_echo('friends:widget:description'));

	// extend the account settings form
	elgg_extend_view('forms/account/settings', 'core/settings/account/name', 100);
	elgg_extend_view('forms/account/settings', 'core/settings/account/password', 100);
	elgg_extend_view('forms/account/settings', 'core/settings/account/email', 100);
	elgg_extend_view('forms/account/settings', 'core/settings/account/language', 100);
	elgg_extend_view('forms/account/settings', 'core/settings/account/default_access', 100);

	// Register the user type
	elgg_register_entity_type('user', '');

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'elgg_users_setup_entity_menu', 501);

	elgg_register_event_handler('create', 'user', 'user_create_hook_add_site_relationship');
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

elgg_register_event_handler('init', 'system', 'users_init', 0);
elgg_register_event_handler('init', 'system', 'elgg_profile_fields_setup', 10000); // Ensure this runs after other plugins
elgg_register_event_handler('pagesetup', 'system', 'users_pagesetup', 0);
elgg_register_plugin_hook_handler('unit_test', 'system', 'users_test');
