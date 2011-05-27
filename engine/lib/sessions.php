<?php

/**
 * Elgg session management
 * Functions to manage logins
 *
 * @package Elgg
 * @subpackage Core
 */

/** Elgg magic session */
global $SESSION;

/**
 * Magic session class.
 * This class is intended to extend the $_SESSION magic variable by providing an API hook
 * to plug in other values.
 *
 * Primarily this is intended to provide a way of supplying "logged in user" details without touching the session
 * (which can cause problems when accessed server side).
 *
 * If a value is present in the session then that value is returned, otherwise a plugin hook 'session:get', '$var' is called,
 * where $var is the variable being requested.
 *
 * Setting values will store variables in the session in the normal way.
 *
 * LIMITATIONS: You can not access multidimensional arrays
 *
 * This is EXPERIMENTAL.
 */
class ElggSession implements ArrayAccess {
	/** Local cache of trigger retrieved variables */
	private static $__localcache;

	function __isset($key) {
		return $this->offsetExists($key);
	}

	/** Set a value, go straight to session. */
	function offsetSet($key, $value) {
		$_SESSION[$key] = $value;
	}

	/**
	 * Get a variable from either the session, or if its not in the session attempt to get it from
	 * an api call.
	 */
	function offsetGet($key) {
		if (!ElggSession::$__localcache) {
			ElggSession::$__localcache = array();
		}

		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}

		if (isset(ElggSession::$__localcache[$key])) {
			return ElggSession::$__localcache[$key];
		}

		$value = NULL;
		$value = trigger_plugin_hook('session:get', $key, NULL, $value);

		ElggSession::$__localcache[$key] = $value;

		return ElggSession::$__localcache[$key];
	}

	/**
	* Unset a value from the cache and the session.
	*/
	function offsetUnset($key) {
		unset(ElggSession::$__localcache[$key]);
		unset($_SESSION[$key]);
	}

	/**
	* Return whether the value is set in either the session or the cache.
	*/
	function offsetExists($offset) {
		if (isset(ElggSession::$__localcache[$offset])) {
			return true;
		}

		if (isset($_SESSION[$offset])) {
			return true;
		}

		if ($this->offsetGet($offset)){
			return true;
		}
	}


	// Alias functions
	function get($key) {
		return $this->offsetGet($key);
	}

	function set($key, $value) {
		return $this->offsetSet($key, $value);
	}

	function del($key) {
		return $this->offsetUnset($key);
	}
}


/**
 * Return the current logged in user, or NULL if no user is logged in.
 *
 * If no user can be found in the current session, a plugin hook - 'session:get' 'user' to give plugin
 * authors another way to provide user details to the ACL system without touching the session.
 * @return ElggUser|NULL
 */
function get_loggedin_user() {
	global $SESSION;

	if (isset($SESSION)) {
		return $SESSION['user'];
	}

	return NULL;
}

/**
 * Return the current logged in user by id.
 *
 * @see get_loggedin_user()
 * @return int
 */
function get_loggedin_userid() {
	$user = get_loggedin_user();
	if ($user) {
		return $user->guid;
	}

	return 0;
}

/**
 * Returns whether or not the user is currently logged in
 *
 * @return true|false
 */
function isloggedin() {
	if (!is_installed()) {
		return false;
	}

	$user = get_loggedin_user();

	if ((isset($user)) && ($user instanceof ElggUser) && ($user->guid > 0)) {
		return true;
	}

	return false;
}

/**
 * Returns whether or not the user is currently logged in and that they are an admin user.
 *
 * @uses isloggedin()
 * @return true|false
 */
function isadminloggedin() {
	if (!is_installed()) {
		return FALSE;
	}

	$user = get_loggedin_user();

	if ((isloggedin()) && $user->isAdmin()) {
		return TRUE;
	}

	return FALSE;
}

/**
 * Check if the given user has full access.
 * @todo: Will always return full access if the user is an admin.
 *
 * @param $user_guid
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
 * Perform standard authentication with a given username and password.
 * Returns an ElggUser object for use with login.
 *
 * @see login
 * @param string $username The username, optionally (for standard logins)
 * @param string $password The password, optionally (for standard logins)
 * @return ElggUser|false The authenticated user object, or false on failure.
 */

function authenticate($username, $password) {
	if (pam_authenticate(array('username' => $username, 'password' => $password))) {
		return get_user_by_username($username);
	}

	return false;
}

/**
 * Hook into the PAM system which accepts a username and password and attempts to authenticate
 * it against a known user.
 *
 * @param array $credentials Associated array of credentials passed to pam_authenticate. This function expects
 * 		'username' and 'password' (cleartext).
 */
function pam_auth_userpass($credentials = NULL) {

	if (is_array($credentials) && ($credentials['username']) && ($credentials['password'])) {
		if ($user = get_user_by_username($credentials['username'])) {

			// Let admins log in without validating their email, but normal users must have validated their email or been admin created
			if ((!$user->isAdmin()) && (!$user->validated) && (!$user->admin_created)) {
				return false;
			}

			// User has been banned, so prevent from logging in
			if ($user->isBanned()) {
				return false;
			}

			if ($user->password == generate_user_password($user, $credentials['password'])) {
				return true;
			} else {
				// Password failed, log.
				log_login_failure($user->guid);
			}

		}
	}

	return false;
}

/**
 * Log a failed login for $user_guid
 *
 * @param $user_guid
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
 * @param $user_guid
 * @return bool on success (success = user has no logged failed attempts)
 */
function reset_login_failure_count($user_guid) {
	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);

	if (($user_guid) && ($user) && ($user instanceof ElggUser)) {
		$fails = (int)$user->getPrivateSetting("login_failures");

		if ($fails) {
			for ($n=1; $n <= $fails; $n++) {
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
 * @param $user_guid
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
			for ($n=$fails; $n>0; $n--) {
				$f = $user->getPrivateSetting("login_failure_$n");
				if ($f > $time - (60*5)) {
					$cnt++;
				}

				if ($cnt==$limit) {
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
 * with authenticate.
 *
 * @see authenticate
 * @param ElggUser $user A valid Elgg user object
 * @param boolean $persistent Should this be a persistent login?
 * @return true|false Whether login was successful
 */
function login(ElggUser $user, $persistent = false) {
	global $CONFIG;

	// User is banned, return false.
	if ($user->isBanned()) {
		return false;
	}

	// Check rate limit
	if (check_rate_limit_exceeded($user->guid)) {
		return false;
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
		setcookie("elggperm", $code, (time()+(86400 * 30)),"/");
	}

	if (!$user->save() || !trigger_elgg_event('login','user',$user)) {
		unset($_SESSION['username']);
		unset($_SESSION['name']);
		unset($_SESSION['code']);
		unset($_SESSION['guid']);
		unset($_SESSION['id']);
		unset($_SESSION['user']);
		setcookie("elggperm", "", (time()-(86400 * 30)),"/");
		return false;
	}

	// Users privilege has been elevated, so change the session id (prevents session fixation)
	session_regenerate_id();

	// Update statistics
	set_last_login($_SESSION['guid']);
	reset_login_failure_count($user->guid); // Reset any previous failed login attempts

	// Set admin shortcut flag if this is an admin
//	if (isadminloggedin()) {
//		//@todo REMOVE THIS.
//		global $is_admin;
//		$is_admin = true;
//	}

	return true;
}

/**
 * Log the current user out
 *
 * @return true|false
 */
function logout() {
	global $CONFIG;

	if (isset($_SESSION['user'])) {
		if (!trigger_elgg_event('logout','user',$_SESSION['user'])) {
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

	setcookie("elggperm", "", (time()-(86400 * 30)),"/");

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
 * 2. The cookie 'elggperm' - if present, checks it for an authentication token, validates it, and potentially logs the user in
 *
 * @uses $_SESSION
 * @param unknown_type $event
 * @param unknown_type $object_type
 * @param unknown_type $object
 */
function session_init($event, $object_type, $object) {
	global $DB_PREFIX, $CONFIG;

	if (!is_db_installed()) {
		return false;
	}

	// Use database for sessions
	// HACK to allow access to prefix after object destruction
	$DB_PREFIX = $CONFIG->dbprefix;
	if ((!isset($CONFIG->use_file_sessions))) {
		session_set_save_handler("__elgg_session_open",
			"__elgg_session_close",
			"__elgg_session_read",
			"__elgg_session_write",
			"__elgg_session_destroy",
			"__elgg_session_gc");
	}

	session_name('Elgg');
	session_start();

	// Generate a simple token (private from potentially public session id)
	if (!isset($_SESSION['__elgg_session'])) {
		$_SESSION['__elgg_session'] = md5(microtime().rand());
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

	register_action("login",true);
	register_action("logout");

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
 */
function gatekeeper() {
	if (!isloggedin()) {
		$_SESSION['last_forward_from'] = current_page_url();
		register_error(elgg_echo('loggedinrequired'));

		if (!forward()) {
			throw new SecurityException(elgg_echo('SecurityException:UnexpectedOutputInGatekeeper'));
		}
	}
}

/**
 * Used at the top of a page to mark it as logged in admin or siteadmin only.
 *
 */
function admin_gatekeeper() {
	gatekeeper();

	if (!isadminloggedin()) {
		$_SESSION['last_forward_from'] = current_page_url();
		register_error(elgg_echo('adminrequired'));
		if (!forward()) {
			throw new SecurityException(elgg_echo('SecurityException:UnexpectedOutputInGatekeeper'));
		}
	}
}

/**
 * DB Based session handling code.
 */
function __elgg_session_open($save_path, $session_name) {
	global $sess_save_path;
	$sess_save_path = $save_path;

	return true;
}

/**
 * DB Based session handling code.
 */
function __elgg_session_close() {
	return true;
}

/**
 * DB Based session handling code.
 */
function __elgg_session_read($id) {
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
 * DB Based session handling code.
 */
function __elgg_session_write($id, $sess_data) {
	global $DB_PREFIX;

	$id = sanitise_string($id);
	$time = time();

	try {
		$sess_data_sanitised = sanitise_string($sess_data);

		$q = "REPLACE INTO {$DB_PREFIX}users_sessions
			(session, ts, data) VALUES
			('$id', '$time', '$sess_data_sanitised')";

		if (insert_data($q)!==false) {
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
 * DB Based session handling code.
 */
function __elgg_session_destroy($id) {
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
 * DB Based session handling code.
 */
function __elgg_session_gc($maxlifetime) {
	global $DB_PREFIX;

	$life = time()-$maxlifetime;

	try {
		return (bool)delete_data("DELETE from {$DB_PREFIX}users_sessions where ts<'$life'");
	} catch (DatabaseException $e) {
		// Fall back to file store in this case, since this likely means that the database hasn't been upgraded
		global $sess_save_path;

		foreach (glob("$sess_save_path/sess_*") as $filename) {
			if (filemtime($filename) < $life) {
				@unlink($filename);
			}
		}
	}

	return true;
}

register_elgg_event_handler("boot","system","session_init",20);
