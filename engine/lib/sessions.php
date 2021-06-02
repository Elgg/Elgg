<?php
/**
 * Elgg session management
 * Functions to manage logins
 */

use Elgg\Di\ServiceProvider;
use Elgg\Exceptions\LoginException;
use Elgg\SystemMessagesService;

/**
 * Gets Elgg's session object
 *
 * @return \ElggSession
 * @since 1.9
 */
function elgg_get_session() {
	return elgg()->session;
}

/**
 * Return the current logged in user, or null if no user is logged in.
 *
 * @return \ElggUser|null
 */
function elgg_get_logged_in_user_entity() {
	return elgg()->session->getLoggedInUser();
}

/**
 * Return the current logged in user by guid.
 *
 * @see elgg_get_logged_in_user_entity()
 * @return int
 */
function elgg_get_logged_in_user_guid() {
	return elgg()->session->getLoggedInUserGuid();
}

/**
 * Returns whether or not the user is currently logged in
 *
 * @return bool
 */
function elgg_is_logged_in() {
	return elgg()->session->isLoggedIn();
}

/**
 * Returns whether or not the viewer is currently logged in and an admin user.
 *
 * @return bool
 */
function elgg_is_admin_logged_in() {
	return elgg()->session->isAdminLoggedIn();
}

/**
 * Perform user authentication with a given username and password.
 *
 * @warning This returns an error message on failure. Use the identical operator to check
 * for access: if (true === elgg_authenticate()) { ... }.
 *
 *
 * @see login()
 *
 * @param string $username The username
 * @param string $password The password
 *
 * @return true|string True or an error message on failure
 * @internal
 */
function elgg_authenticate($username, $password) {
	$pam = new \ElggPAM('user');
	$credentials = ['username' => $username, 'password' => $password];
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
 * @internal
 */
function pam_auth_userpass(array $credentials = []) {

	if (!isset($credentials['username']) || !isset($credentials['password'])) {
		return false;
	}

	return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($credentials) {
		$user = get_user_by_username($credentials['username']);
		if (!$user) {
			throw new LoginException(_elgg_services()->translator->translate('LoginException:UsernameFailure'));
		}

		$password_svc = _elgg_services()->passwords;
		$password = $credentials['password'];
		$hash = $user->password_hash;

		if (check_rate_limit_exceeded($user->guid)) {
			throw new LoginException(_elgg_services()->translator->translate('LoginException:AccountLocked'));
		}
		if (!$password_svc->verify($password, $hash)) {
			log_login_failure($user->guid);
			throw new LoginException(_elgg_services()->translator->translate('LoginException:PasswordFailure'));
		}

		if ($password_svc->needsRehash($hash)) {
			$password_svc->forcePasswordReset($user, $password);
		}

		return true;
	});
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
 * with elgg_authenticate.
 *
 * @see elgg_authenticate()
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
	$session = elgg()->session;
	$session->setLoggedInUser($user);

	// re-register at least the core language file for users with language other than site default
	elgg()->translator->registerTranslations(\Elgg\Project\Paths::elgg() . 'languages/');

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
	$session = elgg()->session;
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

/**
 * Initializes the session and checks for the remember me cookie
 *
 * @param ServiceProvider $services Services
 *
 * @return bool
 *
 * @internal
 */
function _elgg_session_boot(ServiceProvider $services) {
	$services->timer->begin([__FUNCTION__]);

	$session = $services->session;
	$session->start();

	// test whether we have a user session
	if ($session->has('guid')) {
		$user = $services->entityTable->get($session->get('guid'), 'user');
		if (!$user instanceof ElggUser) {
			// OMG user has been deleted.
			$session->invalidate();
			// redirect to homepage
			$services->responseFactory->redirect('');
		}
	} else {
		$user = $services->persistentLogin->bootSession();
		if ($user instanceof ElggUser) {
			$services->persistentLogin->updateTokenUsage($user);
		}
	}

	if ($user instanceof ElggUser) {
		$session->setLoggedInUser($user);
		$user->setLastAction();

		// logout a user with open session who has been banned
		if ($user->isBanned()) {
			logout();
			return false;
		}
	}

	$services->timer->end([__FUNCTION__]);
	return true;
}
