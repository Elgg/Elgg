<?php
/**
 * Library for managing user tokens
 */

/**
 * Obtain a token for a user.
 *
 * @param string $username The username
 * @param int    $expire   Minutes until token expires (default is 60 minutes)
 *
 * @return bool
 */
function create_user_token($username, $expire = 60) {
	global $CONFIG;

	$site_guid = $CONFIG->site_id;
	$user = get_user_by_username($username);
	$time = time();
	$time += 60 * $expire;
	$token = md5(rand() . microtime() . $username . $time . $site_guid);

	if (!$user) {
		return false;
	}

	if (insert_data("INSERT into {$CONFIG->dbprefix}users_apisessions
				(user_guid, site_guid, token, expires) values
				({$user->guid}, $site_guid, '$token', '$time')
				on duplicate key update token='$token', expires='$time'")) {
		return $token;
	}

	return false;
}

/**
 * Get all tokens attached to a user
 *
 * @param int $user_guid The user GUID
 * @param int $site_guid The ID of the site (default is current site)
 *
 * @return false if none available or array of stdClass objects
 * 		(see users_apisessions schema for available variables in objects)
 * @since 1.7.0
 */
function get_user_tokens($user_guid, $site_guid) {
	global $CONFIG;

	if (!isset($site_guid)) {
		$site_guid = $CONFIG->site_id;
	}

	$site_guid = (int)$site_guid;
	$user_guid = (int)$user_guid;

	$tokens = get_data("SELECT * from {$CONFIG->dbprefix}users_apisessions
		where user_guid=$user_guid and site_guid=$site_guid");

	return $tokens;
}

/**
 * Validate a token against a given site.
 *
 * A token registered with one site can not be used from a
 * different apikey(site), so be aware of this during development.
 *
 * @param string $token     The Token.
 * @param int    $site_guid The ID of the site (default is current site)
 *
 * @return mixed The user id attached to the token if not expired or false.
 */
function validate_user_token($token, $site_guid) {
	global $CONFIG;

	if (!isset($site_guid)) {
		$site_guid = $CONFIG->site_id;
	}

	$site_guid = (int)$site_guid;
	$token = sanitise_string($token);

	$time = time();

	$user = get_data_row("SELECT * from {$CONFIG->dbprefix}users_apisessions
		where token='$token' and site_guid=$site_guid and $time < expires");

	if ($user) {
		return $user->user_guid;
	}

	return false;
}

/**
 * Remove user token
 *
 * @param string $token     The toekn
 * @param int    $site_guid The ID of the site (default is current site)
 *
 * @return bool
 * @since 1.7.0
 */
function remove_user_token($token, $site_guid) {
	global $CONFIG;

	if (!isset($site_guid)) {
		$site_guid = $CONFIG->site_id;
	}

	$site_guid = (int)$site_guid;
	$token = sanitise_string($token);

	return delete_data("DELETE from {$CONFIG->dbprefix}users_apisessions
		where site_guid=$site_guid and token='$token'");
}

/**
 * Remove expired tokens
 *
 * @return bool
 * @since 1.7.0
 */
function remove_expired_user_tokens() {
	global $CONFIG;

	$site_guid = $CONFIG->site_id;

	$time = time();

	return delete_data("DELETE from {$CONFIG->dbprefix}users_apisessions
		where site_guid=$site_guid and expires < $time");
}

/**
 * The auth.gettoken API.
 * This API call lets a user log in, returning an authentication token which can be used
 * to authenticate a user for a period of time. It is passed in future calls as the parameter
 * auth_token.
 *
 * @param string $username Username
 * @param string $password Clear text password
 *
 * @return string Token string or exception
 * @throws SecurityException
 * @access private
 */
function auth_gettoken($username, $password) {
	// check if username is an email address
	if (is_email_address($username)) {
		$users = get_user_by_email($username);

		// check if we have a unique user
		if (is_array($users) && (count($users) == 1)) {
			$username = $users[0]->username;
		}
	}

	// validate username and password
	if (true === elgg_authenticate($username, $password)) {
		$token = create_user_token($username);
		if ($token) {
			return $token;
		}
	}

	throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
}
