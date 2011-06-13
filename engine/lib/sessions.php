<?php

/**
 * Elgg session management
 * Functions to manage logins
 *
 * @package Elgg.Core
 * @subpackage Session
 */

/** Elgg magic session */
global $SESSION;

/**
 * Return the current logged in user, or NULL if no user is logged in.
 *
 * If no user can be found in the current session, a plugin
 * hook - 'session:get' 'user' to give plugin authors another
 * way to provide user details to the ACL system without touching the session.
 *
 * @return ElggUser
 */
function elgg_get_logged_in_user_entity() {
	global $SESSION;

	if (isset($SESSION)) {
		return $SESSION['user'];
	}

	return NULL;
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
 * @see login
 *
 * @param string $username The username
 * @param string $password The password
 *
 * @return true|string True or an error message on failure
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
 * @return bool on success
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
	global $CONFIG;

	// User is banned, return false.
	if ($user->isBanned()) {
		throw new LoginException(elgg_echo('LoginException:BannedUser'));
	}

	$_SESSION['user'] = $user;
	$_SESSION['guid'] = $user->getGUID();
	$_SESSION['id'] = $_SESSION['guid'];
	$_SESSION['username'] = $user->username;
	$_SESSION['name'] = $user->name;

	// if remember me checked, set cookie with token and store token on user
	if (($persistent)) {
		$code = (md5($user->name . $user->username . time() . rand()));
		$_SESSION['code'] = $code;
		$user->code = md5($code);
		setcookie("elggperm", $code, (time() + (86400 * 30)), "/");
	}

	if (!$user->save() || !elgg_trigger_event('login', 'user', $user)) {
		unset($_SESSION['username']);
		unset($_SESSION['name']);
		unset($_SESSION['code']);
		unset($_SESSION['guid']);
		unset($_SESSION['id']);
		unset($_SESSION['user']);
		setcookie("elggperm", "", (time() - (86400 * 30)), "/");
		throw new LoginException(elgg_echo('LoginException:Unknown'));
	}

	// Users privilege has been elevated, so change the session id (prevents session fixation)
	session_regenerate_id();

	// Update statistics
	set_last_login($_SESSION['guid']);
	reset_login_failure_count($user->guid); // Reset any previous failed login attempts

	return true;
}

/**
 * Log the current user out
 *
 * @return bool
 */
function logout() {
	global $CONFIG;

	if (isset($_SESSION['user'])) {
		if (!elgg_trigger_event('logout', 'user', $_SESSION['user'])) {
			return false;
		}
		$_SESSION['user']->code = "";
		$_SESSION['user']->save();
	}

	unset($_SESSION['username']);
	unset($_SESSION['name']);
	unset($_SESSION['code']);
	unset($_SESSION['guid']);
	unset($_SESSION['id']);
	unset($_SESSION['user']);

	setcookie("elggperm", "", (time() - (86400 * 30)), "/");

	// pass along any messages
	$old_msg = $_SESSION['msg'];

	session_destroy();

	// starting a default session to store any post-logout messages.
	session_init(NULL, NULL, NULL);
	$_SESSION['msg'] = $old_msg;

	return TRUE;
}

/**
 * Initialises the system session and potentially logs the user in
 *
 * This function looks for:
 *
 * 1. $_SESSION['id'] - if not present, we're logged out, and this is set to 0
 * 2. The cookie 'elggperm' - if present, checks it for an authentication
 * token, validates it, and potentially logs the user in
 *
 * @uses $_SESSION
 *
 * @param string $event       Event name
 * @param string $object_type Object type
 * @param mixed  $object      Object
 *
 * @return bool
 */
function session_init($event, $object_type, $object) {
	global $DB_PREFIX, $CONFIG;

	// Use database for sessions
	// HACK to allow access to prefix after object destruction
	$DB_PREFIX = $CONFIG->dbprefix;
	if ((!isset($CONFIG->use_file_sessions))) {
		session_set_save_handler("_elgg_session_open",
			"_elgg_session_close",
			"_elgg_session_read",
			"_elgg_session_write",
			"_elgg_session_destroy",
			"_elgg_session_gc");
	}

	session_name('Elgg');
	session_start();

	// Generate a simple token (private from potentially public session id)
	if (!isset($_SESSION['__elgg_session'])) {
		$_SESSION['__elgg_session'] = md5(microtime() . rand());
	}

	// test whether we have a user session
	if (empty($_SESSION['guid'])) {

		// clear session variables before checking cookie
		unset($_SESSION['user']);
		unset($_SESSION['id']);
		unset($_SESSION['guid']);
		unset($_SESSION['code']);

		// is there a remember me cookie
		if (isset($_COOKIE['elggperm'])) {
			// we have a cookie, so try to log the user in
			$code = $_COOKIE['elggperm'];
			$code = md5($code);
			if ($user = get_user_by_code($code)) {
				// we have a user, log him in
				$_SESSION['user'] = $user;
				$_SESSION['id'] = $user->getGUID();
				$_SESSION['guid'] = $_SESSION['id'];
				$_SESSION['code'] = $_COOKIE['elggperm'];
			}
		}
	} else {
		// we have a session and we have already checked the fingerprint
		// reload the user object from database in case it has changed during the session
		if ($user = get_user($_SESSION['guid'])) {
			$_SESSION['user'] = $user;
			$_SESSION['id'] = $user->getGUID();
			$_SESSION['guid'] = $_SESSION['id'];
		} else {
			// user must have been deleted with a session active
			unset($_SESSION['user']);
			unset($_SESSION['id']);
			unset($_SESSION['guid']);
			unset($_SESSION['code']);
		}
	}

	if (isset($_SESSION['guid'])) {
		set_last_action($_SESSION['guid']);
	}

	elgg_register_action("login", '', 'public');
	elgg_register_action("logout");

	// Register a default PAM handler
	register_pam_handler('pam_auth_userpass');

	// Initialise the magic session
	global $SESSION;
	$SESSION = new ElggSession();

	// Finally we ensure that a user who has been banned with an open session is kicked.
	if ((isset($_SESSION['user'])) && ($_SESSION['user']->isBanned())) {
		session_destroy();
		return false;
	}

	// Since we have loaded a new user, this user may have different language preferences
	register_translations(dirname(dirname(dirname(__FILE__))) . "/languages/");

	return true;
}

/**
 * Used at the top of a page to mark it as logged in users only.
 *
 * @return void
 */
function gatekeeper() {
	if (!elgg_is_logged_in()) {
		$_SESSION['last_forward_from'] = current_page_url();
		register_error(elgg_echo('loggedinrequired'));

		if (!forward('', 'login')) {
			throw new SecurityException(elgg_echo('SecurityException:UnexpectedOutputInGatekeeper'));
		}
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
		$_SESSION['last_forward_from'] = current_page_url();
		register_error(elgg_echo('adminrequired'));
		if (!forward('', 'admin')) {
			throw new SecurityException(elgg_echo('SecurityException:UnexpectedOutputInGatekeeper'));
		}
	}
}

/**
 * Handles opening a session in the DB
 *
 * @param string $save_path    The path to save the sessions
 * @param string $session_name The name of the session
 *
 * @return true
 * @todo Document
 */
function _elgg_session_open($save_path, $session_name) {
	global $sess_save_path;
	$sess_save_path = $save_path;

	return true;
}

/**
 * Closes a session
 *
 * @todo implement
 * @todo document
 *
 * @return true
 */
function _elgg_session_close() {
	return true;
}

/**
 * Read the session data from DB failing back to file.
 *
 * @param string $id The session ID
 *
 * @return string
 */
function _elgg_session_read($id) {
	global $DB_PREFIX;

	$id = sanitise_string($id);

	try {
		$result = get_data_row("SELECT * from {$DB_PREFIX}users_sessions where session='$id'");

		if ($result) {
			return (string)$result->data;
		}

	} catch (DatabaseException $e) {

		// Fall back to file store in this case, since this likely means
		// that the database hasn't been upgraded
		global $sess_save_path;

		$sess_file = "$sess_save_path/sess_$id";
		return (string) @file_get_contents($sess_file);
	}

	return '';
}

/**
 * Write session data to the DB falling back to file.
 *
 * @param string $id        The session ID
 * @param mixed  $sess_data Session data
 *
 * @return bool
 */
function _elgg_session_write($id, $sess_data) {
	global $DB_PREFIX;

	$id = sanitise_string($id);
	$time = time();

	try {
		$sess_data_sanitised = sanitise_string($sess_data);

		$q = "REPLACE INTO {$DB_PREFIX}users_sessions
			(session, ts, data) VALUES
			('$id', '$time', '$sess_data_sanitised')";

		if (insert_data($q) !== false) {
			return true;
		}
	} catch (DatabaseException $e) {
		// Fall back to file store in this case, since this likely means
		// that the database hasn't been upgraded
		global $sess_save_path;

		$sess_file = "$sess_save_path/sess_$id";
		if ($fp = @fopen($sess_file, "w")) {
			$return = fwrite($fp, $sess_data);
			fclose($fp);
			return $return;
		}
	}

	return false;
}

/**
 * Destroy a DB session, falling back to file.
 *
 * @param string $id Session ID
 *
 * @return bool
 */
function _elgg_session_destroy($id) {
	global $DB_PREFIX;

	$id = sanitise_string($id);

	try {
		return (bool)delete_data("DELETE from {$DB_PREFIX}users_sessions where session='$id'");
	} catch (DatabaseException $e) {
		// Fall back to file store in this case, since this likely means that
		// the database hasn't been upgraded
		global $sess_save_path;

		$sess_file = "$sess_save_path/sess_$id";
		return(@unlink($sess_file));
	}

	return false;
}

/**
 * Perform garbage collection on session table / files
 *
 * @param int $maxlifetime Max age of a session
 *
 * @return bool
 */
function _elgg_session_gc($maxlifetime) {
	global $DB_PREFIX;

	$life = time() - $maxlifetime;

	try {
		return (bool)delete_data("DELETE from {$DB_PREFIX}users_sessions where ts<'$life'");
	} catch (DatabaseException $e) {
		// Fall back to file store in this case, since this likely means that the database
		// hasn't been upgraded
		global $sess_save_path;

		foreach (glob("$sess_save_path/sess_*") as $filename) {
			if (filemtime($filename) < $life) {
				@unlink($filename);
			}
		}
	}

	return true;
}

elgg_register_event_handler("boot", "system", "session_init", 20);
