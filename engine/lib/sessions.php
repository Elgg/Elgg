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
 * Return the current logged in user, or NULL if no user is logged in.
 *
 * @warning The plugin hook described below is deprecated
 * If no user can be found in the current session, a plugin
 * hook - 'session:get' 'user' to give plugin authors another
 * way to provide user details to the ACL system without touching the session.
 *
 * @return ElggUser
 */
function elgg_get_logged_in_user_entity() {
	return _elgg_services()->session->get('user');
}

/**
 * Return the current logged in user by id.
 *
 * @see elgg_get_logged_in_user_entity()
 * @return int
 */
function elgg_get_logged_in_user_guid() {
	$user = elgg_get_logged_in_user_entity();
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
	$user = elgg_get_logged_in_user_entity();

	if ((isset($user)) && ($user instanceof ElggUser) && ($user->guid > 0)) {
		return true;
	}

	return false;
}

/**
 * Returns whether or not the user is currently logged in and that they are an admin user.
 *
 * @return bool
 */
function elgg_is_admin_logged_in() {
	$user = elgg_get_logged_in_user_entity();

	if ((elgg_is_logged_in()) && $user->isAdmin()) {
		return TRUE;
	}

	return FALSE;
}

/**
 * Used at the top of a page to mark it as logged in users only.
 *
 * @return void
 */
function gatekeeper() {
	if (!elgg_is_logged_in()) {
		_elgg_services()->session->set('last_forward_from', current_page_url());
		register_error(elgg_echo('loggedinrequired'));
		forward('', 'login');
	}
}

/**
 * Used at the top of a page to mark it as logged in admin or siteadmin only.
 *
 * @return void
 */
function admin_gatekeeper() {
	gatekeeper();

	if (!elgg_is_admin_logged_in()) {
		_elgg_services()->session->set('last_forward_from', current_page_url());
		register_error(elgg_echo('adminrequired'));
		forward('', 'admin');
	}
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
	// cannot use magic metadata here because of recursion

	// must support the old way of getting admin from metadata
	// in order to run the upgrade to move it into the users table.
	$version = (int) datalist_get('version');

	if ($version < 2010040201) {
		$admin = get_metastring_id('admin');
		$yes = get_metastring_id('yes');
		$one = get_metastring_id('1');

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
	if (!((is_array($info) && count($info) < 1) || $info === FALSE)) {
		return TRUE;
	}
	return FALSE;
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
						$cookie->domain, $cookie->secure, $cookie->httponly);
	}
	return false;
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
	// User is banned, return false.
	if ($user->isBanned()) {
		throw new LoginException(elgg_echo('LoginException:BannedUser'));
	}

	$session = _elgg_services()->session;

	// we need the user in the session to have permission to save the entity
	// @todo this should go away when we move remember me cookies out of user table
	$session->set('user', $user);

	// if remember me checked, set cookie with token and store token on user
	if ($persistent) {
		$code = md5($user->name . $user->username . time() . rand());
		$session->set('code', $code);
		$user->code = md5($code);
		
		$cookie = new ElggCookie("elggperm");
		$cookie->value = $code;
		$cookie->setExpire("+30 days");
		
		elgg_set_cookie($cookie);
	}

	if (!$user->save() || !elgg_trigger_event('login', 'user', $user)) {
		$session->remove('user');
		$session->remove('code');

		$cookie = new ElggCookie("elggperm");
		$cookie->setExpire("-30 days");
		elgg_set_cookie($cookie);

		throw new LoginException(elgg_echo('LoginException:Unknown'));
	}

	// User's privilege has been elevated, so change the session id (prevents session fixation)
	$session->migrate();

	$session->set('guid', $user->getGUID());
	$session->set('id', $user->getGUID());
	$session->set('username', $user->username);
	$session->set('name', $user->name);

	// Update statistics
	set_last_login($user->guid);
	reset_login_failure_count($user->guid); // Reset any previous failed login attempts

	return true;
}

/**
 * Log the current user out
 *
 * @return bool
 */
function logout() {
	$session = _elgg_services()->session;
	$user = $session->get('user');
	if ($user) {
		if (!elgg_trigger_event('logout', 'user', $user)) {
			return false;
		}
		$user->code = "";
		$user->save();
	}

	$cookie = new ElggCookie("elggperm");
	$cookie->setExpire("-30 days");
	$cookie->domain = "/";

	elgg_set_cookie($cookie);

	// pass along any messages into new session
	$old_msg = $session->get('msg');
	$session->invalidate();
	$session->set('msg', $old_msg);

	return TRUE;
}

/**
 * Initialises the session and potentially loads the user object
 *
 * This function looks for:
 *
 * 1. 'guid' set in ElggSession - if not present, we're logged out
 * 2. The cookie 'elggperm' - if present, checks it for an authentication
 * token, validates it, and potentially logs the user in
 *
 * @return bool
 * @access private
 */
function _elgg_session_boot() {

	$session = _elgg_services()->session;
	$session->start();

	// test whether we have a user session
	if (!$session->has('guid')) {

		// clear session variables before checking cookie
		$session->remove('username');
		$session->remove('name');
		$session->remove('code');
		$session->remove('guid');
		$session->remove('id');
		$session->remove('user');

		// is there a remember me cookie
		if (isset($_COOKIE['elggperm'])) {
			// we have a cookie, so try to log the user in
			$code = $_COOKIE['elggperm'];
			$code = md5($code);
			if ($user = get_user_by_code($code)) {
				// we have a user, log him in
				$session->set('user', $user);
				$session->set('id', $user->getGUID());
				$session->set('guid', $user->getGUID());
				$session->set('code', $_COOKIE['elggperm']);
			}
		}
	} else {
		// we have a session and we have already checked the fingerprint
		// reload the user object from database in case it has changed during the session
		if ($user = get_user($session->get('guid'))) {
			$session->set('user', $user);
			$session->set('id', $user->getGUID());
			$session->set('guid', $user->getGUID());
		} else {
			// user must have been deleted with a session active
			$session->remove('code');
			$session->remove('guid');
			$session->remove('id');
			$session->remove('user');
		}
	}

	if ($session->has('guid')) {
		set_last_action($session->get('guid'));
	}

	elgg_register_action('login', '', 'public');
	elgg_register_action('logout');

	// Register a default PAM handler
	register_pam_handler('pam_auth_userpass');

	// Initialise the magic session
	global $SESSION;
	$SESSION = new ElggDeprecationWrapper(_elgg_services()->session, "\$SESSION is deprecated", 1.9);

	// Finally we ensure that a user who has been banned with an open session is kicked.
	if ($session->has('user') && $session->get('user')->isBanned()) {
		$session->invalidate();
		return false;
	}

	return true;
}
