<?php
/**
 * Elgg session management
 * Functions to manage logins
 */

use Elgg\Di\InternalContainer;
use Elgg\Exceptions\LoginException;
use Elgg\SystemMessagesService;

/**
 * Gets Elgg's session object
 *
 * @return \ElggSession
 * @since 1.9
 */
function elgg_get_session() {
	return _elgg_services()->session;
}

/**
 * Return the current logged in user, or null if no user is logged in.
 *
 * @return \ElggUser|null
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
	return _elgg_services()->session->getLoggedInUserGuid();
}

/**
 * Returns whether or not the user is currently logged in
 *
 * @return bool
 */
function elgg_is_logged_in() {
	return _elgg_services()->session->isLoggedIn();
}

/**
 * Returns whether or not the viewer is currently logged in and an admin user.
 *
 * @return bool
 */
function elgg_is_admin_logged_in() {
	return _elgg_services()->session->isAdminLoggedIn();
}

/**
 * Log a failed login for $user_guid
 *
 * @param int $user_guid User GUID
 *
 * @return bool
 */
function log_login_failure($user_guid) {
	return elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($user_guid) {
		$user_guid = (int) $user_guid;
		$user = get_entity($user_guid);

		if (($user_guid) && ($user) && ($user instanceof \ElggUser)) {
			$fails = (int) $user->getPrivateSetting("login_failures");
			$fails++;

			$user->setPrivateSetting("login_failures", $fails);
			$user->setPrivateSetting("login_failure_$fails", time());

			return true;
		}

		return false;
	});
}

/**
 * Resets the fail login count for $user_guid
 *
 * @param int $user_guid User GUID
 *
 * @return bool true on success (success = user has no logged failed attempts)
 */
function reset_login_failure_count($user_guid) {
	return elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($user_guid) {
		$user_guid = (int) $user_guid;

		$user = get_entity($user_guid);

		if (($user_guid) && ($user) && ($user instanceof \ElggUser)) {
			$fails = (int) $user->getPrivateSetting("login_failures");

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
	});
}

/**
 * Checks if the rate limit of failed logins has been exceeded for $user_guid.
 *
 * @param int $user_guid User GUID
 *
 * @return bool on exceeded limit.
 */
function check_rate_limit_exceeded($user_guid) {
	return elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($user_guid) {
		// 5 failures in 5 minutes causes temporary block on logins
		$limit = 5;
		$user_guid = (int) $user_guid;
		$user = get_entity($user_guid);

		if (($user_guid) && ($user) && ($user instanceof \ElggUser)) {
			$fails = (int) $user->getPrivateSetting("login_failures");
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
	});
}

/**
 * Set a cookie, but allow plugins to customize it first.
 *
 * To customize all cookies, register for the 'init:cookie', 'all' event.
 *
 * @param \ElggCookie $cookie The cookie that is being set
 * @return bool
 * @since 1.9
 */
function elgg_set_cookie(\ElggCookie $cookie) {
	return _elgg_services()->responseFactory->setCookie($cookie);
}

/**
 * Logs in a specified \ElggUser. For standard registration, use in conjunction
 * with elgg_pam_authenticate.
 *
 * @see elgg_pam_authenticate()
 *
 * @param \ElggUser $user       A valid Elgg user object
 * @param boolean   $persistent Should this be a persistent login?
 *
 * @return true or throws exception
 * @throws LoginException
 */
function login(\ElggUser $user, $persistent = false) {
	if ($user->isBanned()) {
		throw new LoginException(elgg_echo('LoginException:BannedUser'));
	}

	// give plugins a chance to reject the login of this user (no user in session!)
	if (!elgg_trigger_before_event('login', 'user', $user)) {
		throw new LoginException(elgg_echo('LoginException:Unknown'));
	}

	if (!$user->isEnabled()) {
		throw new LoginException(elgg_echo('LoginException:DisabledUser'));
	}

	// #5933: set logged in user early so code in login event will be able to
	// use elgg_get_logged_in_user_entity().
	$session = _elgg_services()->session;
	$session->setLoggedInUser($user);

	// re-register at least the core language file for users with language other than site default
	_elgg_services()->translator->registerTranslations(\Elgg\Project\Paths::elgg() . 'languages/');

	// if remember me checked, set cookie with token and store hash(token) for user
	if ($persistent) {
		_elgg_services()->persistentLogin->makeLoginPersistent($user);
	}

	// User's privilege has been elevated, so change the session id (prevents session fixation)
	$session->migrate();

	// check before updating last login to determine first login
	$first_login = empty($user->last_login);
	
	$user->setLastLogin();
	reset_login_failure_count($user->guid);

	elgg_trigger_after_event('login', 'user', $user);
	
	if ($first_login) {
		elgg_trigger_event('login:first', 'user', $user);
		$user->first_login = time();
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

	_elgg_services()->persistentLogin->removePersistentLogin();

	// pass along any messages into new session
	$old_msg = $session->get(SystemMessagesService::SESSION_KEY, []);
	$session->invalidate();
	$session->set(SystemMessagesService::SESSION_KEY, $old_msg);

	elgg_trigger_after_event('logout', 'user', $user);

	return true;
}

/**
 * Determine which URL the user should be forwarded to upon successful login
 *
 * @param \Elgg\Request $request Request object
 * @param \ElggUser     $user    Logged in user
 * @return string
 *
 * @internal
 */
function _elgg_get_login_forward_url(\Elgg\Request $request, \ElggUser $user) {

	$session = elgg_get_session();
	if ($session->has('last_forward_from')) {
		$forward_url = $session->get('last_forward_from');
		$session->remove('last_forward_from');
		$forward_source = 'last_forward_from';
	} elseif ($request->getParam('returntoreferer')) {
		$forward_url = REFERER;
		$forward_source = 'return_to_referer';
	} else {
		// forward to main index page
		$forward_url = '';
		$forward_source = null;
	}

	$params = [
		'request' => $request,
		'user' => $user,
		'source' => $forward_source,
	];

	return elgg_trigger_plugin_hook('login:forward', 'user', $params, $forward_url);

}
