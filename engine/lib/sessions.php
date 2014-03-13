<?php

/**
 * Elgg session management
 * Functions to manage logins
 *
 * @package    Elgg.Core
 * @subpackage Session
 */

/** 
 * Elgg magic session
 * @deprecated 1.9
 */
global $SESSION;

/**
 * Gets Elgg's session object
 * 
 * @return ElggSession
 * @since 1.9
 */
function elgg_get_session() {
	return _elgg_services()->session;
}

/**
 * Return the current logged in user, or null if no user is logged in.
 *
 * @return ElggUser
 */
function elgg_get_logged_in_user_entity() {
	return _elgg_services()->session->getLoggedInUser();
}

/**
 * Return the current logged in user by guid.
 *
 * @see elgg_get_logged_in_user_entity()
 * @return int
 */
function elgg_get_logged_in_user_guid() {
	$user = _elgg_services()->session->getLoggedInUser();
	if ($user) {
		return $user->guid;
	}

	return 0;
}

/**
 * Returns whether or not the user is currently logged in
 *
 * @return bool
 */
function elgg_is_logged_in() {
	return (bool)_elgg_services()->session->getLoggedInUser();
}

/**
 * Returns whether or not the viewer is currently logged in and an admin user.
 *
 * @return bool
 */
function elgg_is_admin_logged_in() {
	$user = elgg_get_logged_in_user_entity();

	if ($user && $user->isAdmin()) {
		return true;
	}

	return false;
}

/**
 * Check if the given user has full access.
 *
 * @todo: Will always return full access if the user is an admin.
 *
 * @param int $user_guid The user to check
 *
 * @return bool
 * @since 1.7.1
 */
function elgg_is_admin_user($user_guid) {
	global $CONFIG;

	$user_guid = (int)$user_guid;

	// cannot use magic metadata here because of recursion

	// must support the old way of getting admin from metadata
	// in order to run the upgrade to move it into the users table.
	$version = (int) datalist_get('version');

	if ($version < 2010040201) {
		$admin = elgg_get_metastring_id('admin');
		$yes = elgg_get_metastring_id('yes');
		$one = elgg_get_metastring_id('1');

		$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity as e,
			{$CONFIG->dbprefix}metadata as md
			WHERE (
				md.name_id = '$admin'
				AND md.value_id IN ('$yes', '$one')
				AND e.guid = md.entity_guid
				AND e.guid = {$user_guid}
				AND e.banned = 'no'
			)";
	} else {
		$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity as e
			WHERE (
				e.guid = {$user_guid}
				AND e.admin = 'yes'
			)";
	}

	// normalizing the results from get_data()
	// See #1242
	$info = get_data($query);
	if (!((is_array($info) && count($info) < 1) || $info === false)) {
		return true;
	}
	return false;
}

/**
 * Perform user authentication with a given username and password.
 *
 * @warning This returns an error message on failure. Use the identical operator to check
 * for access: if (true === elgg_authenticate()) { ... }.
 *
 *
 * @see login
 *
 * @param string $username The username
 * @param string $password The password
 *
 * @return true|string True or an error message on failure
 * @access private
 */
function elgg_authenticate($username, $password) {
	$pam = new ElggPAM('user');
	$credentials = array('username' => $username, 'password' => $password);
	$result = $pam->authenticate($credentials);
	if (!$result) {
		return $pam->getFailureMessage();
	}
	return true;
}

/**
 * Hook into the PAM system which accepts a username and password and attempts to authenticate
 * it against a known user.
 *
 * @param array $credentials Associated array of credentials passed to
 *                           Elgg's PAM system. This function expects
 *                           'username' and 'password' (cleartext).
 *
 * @return bool
 * @throws LoginException
 * @access private
 */
function pam_auth_userpass(array $credentials = array()) {

	if (!isset($credentials['username']) || !isset($credentials['password'])) {
		return false;
	}

	$user = get_user_by_username($credentials['username']);
	if (!$user) {
		throw new LoginException(elgg_echo('LoginException:UsernameFailure'));
	}

	if (check_rate_limit_exceeded($user->guid)) {
		throw new LoginException(elgg_echo('LoginException:AccountLocked'));
	}

	if ($user->password !== generate_user_password($user, $credentials['password'])) {
		log_login_failure($user->guid);
		throw new LoginException(elgg_echo('LoginException:PasswordFailure'));
	}

	return true;
}

/**
 * Log a failed login for $user_guid
 *
 * @param int $user_guid User GUID
 *
 * @return bool
 */
function log_login_failure($user_guid) {
	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);

	if (($user_guid) && ($user) && ($user instanceof ElggUser)) {
		$fails = (int)$user->getPrivateSetting("login_failures");
		$fails++;

		$user->setPrivateSetting("login_failures", $fails);
		$user->setPrivateSetting("login_failure_$fails", time());
		return true;
	}

	return false;
}

/**
 * Resets the fail login count for $user_guid
 *
 * @param int $user_guid User GUID
 *
 * @return bool true on success (success = user has no logged failed attempts)
 */
function reset_login_failure_count($user_guid) {
	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);

	if (($user_guid) && ($user) && ($user instanceof ElggUser)) {
		$fails = (int)$user->getPrivateSetting("login_failures");

		if ($fails) {
			for ($n = 1; $n <= $fails; $n++) {
				$user->removePrivateSetting("login_failure_$n");
			}

			$user->removePrivateSetting("login_failures");

			return true;
		}

		// nothing to reset
		return true;
	}

	return false;
}

/**
 * Checks if the rate limit of failed logins has been exceeded for $user_guid.
 *
 * @param int $user_guid User GUID
 *
 * @return bool on exceeded limit.
 */
function check_rate_limit_exceeded($user_guid) {
	// 5 failures in 5 minutes causes temporary block on logins
	$limit = 5;
	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);

	if (($user_guid) && ($user) && ($user instanceof ElggUser)) {
		$fails = (int)$user->getPrivateSetting("login_failures");
		if ($fails >= $limit) {
			$cnt = 0;
			$time = time();
			for ($n = $fails; $n > 0; $n--) {
				$f = $user->getPrivateSetting("login_failure_$n");
				if ($f > $time - (60 * 5)) {
					$cnt++;
				}

				if ($cnt == $limit) {
					// Limit reached
					return true;
				}
			}
		}
	}

	return false;
}

/**
 * Set a cookie, but allow plugins to customize it first.
 *
 * To customize all cookies, register for the 'init:cookie', 'all' event.
 *
 * @param ElggCookie $cookie The cookie that is being set
 * @return bool
 * @since 1.9
 */
function elgg_set_cookie(ElggCookie $cookie) {
	if (elgg_trigger_event('init:cookie', $cookie->name, $cookie)) {
		return setcookie($cookie->name, $cookie->value, $cookie->expire, $cookie->path,
						$cookie->domain, $cookie->secure, $cookie->httpOnly);
	}
	return false;
}

/**
 * Add a remember me cookie to storage
 * 
 * @param ElggUser $user The user being remembered
 * @param string   $code 32 letter code
 * @return void
 * @access private
 */
function _elgg_add_remember_me_cookie(ElggUser $user, $code) {
	$db = _elgg_services()->db;
	$prefix = $db->getTablePrefix();
	$time = time();
	$code = $db->sanitizeString($code);

	$query = "INSERT INTO {$prefix}users_remember_me_cookies
		(code, guid, timestamp) VALUES ('$code', $user->guid, $time)";
	try {
		$db->insertData($query);
	} catch (DatabaseException $e) {
		if (false !== strpos($e->getMessage(), "users_remember_me_cookies' doesn't exist")) {
			// schema has not been updated so we swallow this exception
			return null;
		} else {
			throw $e;
		}
	}
}

/**
 * Remove a remember me cookie from storage
 * 
 * @param string $code 32 letter code
 * @return void
 * @access private
 */
function _elgg_delete_remember_me_cookie($code) {
	$db = _elgg_services()->db;	
	$prefix = $db->getTablePrefix();
	$code = $db->sanitizeString($code);

	$query = "DELETE FROM {$prefix}users_remember_me_cookies
		WHERE code = '$code'";
	try {
		$db->deleteData($query);
	} catch (DatabaseException $e) {
		if (false !== strpos($e->getMessage(), "users_remember_me_cookies' doesn't exist")) {
			// schema has not been updated so we swallow this exception
			return null;
		} else {
			throw $e;
		}
	}
}

/**
 * Remove all of a user's remember me hashes from storage
 *
 * @param ElggUser $user
 * @return void
 * @access private
 *
 * @throws DatabaseException
 */
function _elgg_delete_users_remember_me_hashes(ElggUser $user) {
	$db = _elgg_services()->db;
	$prefix = $db->getTablePrefix();
	$guid = $user->guid;

	$query = "
		DELETE FROM {$prefix}users_remember_me_cookies
		WHERE guid = '$guid'
	";
	try {
		$db->deleteData($query);
	} catch (DatabaseException $e) {
		if (false !== strpos($e->getMessage(), "users_remember_me_cookies' doesn't exist")) {
			// schema has not been updated so we swallow this exception
			return null;
		} else {
			throw $e;
		}
	}
}

/**
 * Store a remember me token in a client cookie (or delete it)
 *
 * @param string $token A token, or empty string to delete the cookie
 * @access private
 */
function _elgg_set_remember_me_cookie($token) {
	$cookies_config = elgg_get_config('cookies');

	$cookie = new ElggCookie($cookies_config['remember_me']['name']);
	$cookie->value = $token;
	foreach (array('expire', 'path', 'domain', 'secure', 'httponly') as $key) {
		$cookie->$key = $cookies_config['remember_me'][$key];
	}
	if (!$token) {
		$cookie->setExpiresTime("-30 days");
	}

	elgg_set_cookie($cookie);
}

/**
 * Get the remember me token from the request cookie.
 *
 * @return string Empty string if missing
 * @access private
 */
function _elgg_get_remember_me_token_from_cookie() {
	$cookies = elgg_get_config('cookies');
	$cookie_name = $cookies['remember_me']['name'];
	return _elgg_services()->request->cookies->get($cookie_name, '');
}

/**
 * Generate a random cookie token used for the remember me feature.
 *
 * The first char is always "z" to indicate the value is more secure than the
 * previously generated ones.
 *
 * @return string
 */
function _elgg_generate_remember_me_token() {
	return 'z' . ElggCrypto::getRandomString(31);
}

/**
 * Determine if a remember me cookie is a legacy MD5 hash
 *
 * @param string $cookie_value The value of the remember me cookie
 * @return bool
 */
function _elgg_is_legacy_remember_me_token($cookie_value) {
	return (isset($cookie_value[0]) && $cookie_value[0] !== 'z');
}

/**
 * Logs in a specified ElggUser. For standard registration, use in conjunction
 * with elgg_authenticate.
 *
 * @see elgg_authenticate
 *
 * @param ElggUser $user       A valid Elgg user object
 * @param boolean  $persistent Should this be a persistent login?
 *
 * @return true or throws exception
 * @throws LoginException
 */
function login(ElggUser $user, $persistent = false) {
	if ($user->isBanned()) {
		throw new LoginException(elgg_echo('LoginException:BannedUser'));
	}

	$session = _elgg_services()->session;

	// give plugins a chance to reject the login of this user (no user in session!)
	if (!elgg_trigger_before_event('login', 'user', $user)) {
		throw new LoginException(elgg_echo('LoginException:Unknown'));
	}

	// #5933: set logged in user early so code in login event will be able to
	// use elgg_get_logged_in_user_entity().
	$session->setLoggedInUser($user);

	// deprecate event
	$message = "The 'login' event was deprecated. Register for 'login:before' or 'login:after'";
	$version = "1.9";
	if (!elgg_trigger_deprecated_event('login', 'user', $user, $message, $version)) {
		$session->removeLoggedInUser();
		throw new LoginException(elgg_echo('LoginException:Unknown'));
	}

	// if remember me checked, set cookie with token and store hash(token) for user
	if ($persistent) {
		$token = _elgg_generate_remember_me_token();
		$hash = md5($token);
		$session->set('code', $token);
		_elgg_add_remember_me_cookie($user, $hash);
		_elgg_set_remember_me_cookie($token);
	}
	
	// User's privilege has been elevated, so change the session id (prevents session fixation)
	$session->migrate();

	set_last_login($user->guid);
	reset_login_failure_count($user->guid);

	elgg_trigger_after_event('login', 'user', $user);

	// if memcache is enabled, invalidate the user in memcache @see https://github.com/Elgg/Elgg/issues/3143
	if (is_memcache_available()) {
		// this needs to happen with a shutdown function because of the timing with set_last_login()
		register_shutdown_function("_elgg_invalidate_memcache_for_entity", $_SESSION['guid']);
	}
	
	return true;
}

/**
 * Log the current user out
 *
 * @return bool
 */
function logout() {
	$session = _elgg_services()->session;
	$user = $session->getLoggedInUser();
	if (!$user) {
		return false;
	}

	if (!elgg_trigger_before_event('logout', 'user', $user)) {
		return false;
	}

	// deprecate event
	$message = "The 'logout' event was deprecated. Register for 'logout:before' or 'logout:after'";
	$version = "1.9";
	if (!elgg_trigger_deprecated_event('logout', 'user', $user, $message, $version)) {
		return false;
	}

	// remove remember me hash and cookie
	$cookie_token = _elgg_get_remember_me_token_from_cookie();
	$cookie_hash = md5($cookie_token);
	if ($cookie_token) {
		_elgg_delete_remember_me_cookie($cookie_hash);
		_elgg_set_remember_me_cookie('');
	}

	// pass along any messages into new session
	$old_msg = $session->get('msg');
	$session->invalidate();
	$session->set('msg', $old_msg);

	elgg_trigger_after_event('logout', 'user', $user);

	return true;
}

/**
 * Initializes the session and checks for the remember me cookie
 *
 * @return bool
 * @access private
 */
function _elgg_session_boot() {

	elgg_register_action('login', '', 'public');
	elgg_register_action('logout');
	register_pam_handler('pam_auth_userpass');
	
	$session = _elgg_services()->session;
	$session->start();

	$cookie_token = _elgg_get_remember_me_token_from_cookie();
	$cookie_hash = md5($cookie_token);

	// test whether we have a user session
	if ($session->has('guid')) {
		$user = get_user($session->get('guid'));
		if (!$user) {
			// OMG user has been deleted.
			$session->invalidate();
			forward('');
		}

		$session->setLoggedInUser($user);
		
		// replace user's old weaker-entropy code with new one
		if ($cookie_token && _elgg_is_legacy_remember_me_token($cookie_token)) {
			// replace user's old weaker-entropy code with new one
			$code = _elgg_generate_remember_me_token();
			$hash = md5($code);
			$session->set('code', $code);
			_elgg_add_remember_me_cookie($user, $hash);
			_elgg_set_remember_me_cookie($code);
		}
	} else {
		// is there a remember me cookie
		if ($cookie_token) {
			// we have a cookie, so try to log the user in
			$user = get_user_by_code($cookie_hash);
			if ($user) {
				$session->setLoggedInUser($user);
				$session->set('code', $cookie_hash);
				// note: if the token is legacy, we don't both replacing it here because
				// it will be replaced during the next request boot
			} else {
				if (_elgg_is_legacy_remember_me_token($cookie_token)) {
					// may be attempt to brute force legacy low-entropy tokens
					sleep(1);
				}
				_elgg_set_remember_me_cookie('');
			}
		}
	}

	if ($session->has('guid')) {
		set_last_action($session->get('guid'));
	}

	// initialize the deprecated global session wrapper
	global $SESSION;
	$SESSION = new Elgg_DeprecationWrapper($session, "\$SESSION is deprecated", 1.9);

	// logout a user with open session who has been banned
	$user = $session->getLoggedInUser();
	if ($user && $user->isBanned()) {
		logout();
		return false;
	}

	return true;
}
