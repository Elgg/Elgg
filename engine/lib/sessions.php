<?php
/**
 * Elgg session management
 * Functions to manage logins
 */

use Elgg\Exceptions\LoginException;

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
 * Log in a user. Use elgg_pam_authenticate() to authenticate the user.
 *
 * @see elgg_pam_authenticate()
 *
 * @param \ElggUser $user       A valid Elgg user object
 * @param boolean   $persistent Should this be a persistent login?
 *
 * @return void
 * @throws LoginException
 * @since 4.3
 */
function elgg_login(\ElggUser $user, bool $persistent = false): void {
	_elgg_services()->session->login($user, $persistent);
}

/**
 * Log the current user out
 *
 * @return bool
 * @since 4.3
 */
function elgg_logout(): bool {
	return _elgg_services()->session->logout();
}

/**
 * Registers an authentication failure for a user
 *
 * @param \ElggUser $user user to log the failure for
 *
 * @return void
 * @since 4.3
 */
function elgg_register_authentication_failure(\ElggUser $user): void {
	_elgg_services()->accounts->registerAuthenticationFailure($user);
}

/**
 * Clears all authentication failures for a give user
 *
 * @param \ElggUser $user user to clear the failures for
 *
 * @return void
 * @since 4.3
 */
function elgg_reset_authentication_failures(\ElggUser $user): void {
	_elgg_services()->accounts->resetAuthenticationFailures($user);
}

/**
 * Checks if the authentication failure limit has been reached
 *
 * @param \ElggUser $user     User to check the limit for
 * @param int       $limit    (optional) number of allowed failures
 * @param int       $lifetime (optional) number of seconds before a failure is considered expired
 *
 * @return bool
 * @since 4.3
 */
function elgg_is_authentication_failure_limit_reached(\ElggUser $user, int $limit = null, int $lifetime = null) {
	return _elgg_services()->accounts->isAuthenticationFailureLimitReached($user, $limit, $lifetime);
}

/**
 * Determine which URL the user should be forwarded to upon successful login
 *
 * @param \ElggUser $user Logged in user
 *
 * @return string|int url to redirect to. Uses int to indicate REFERER
 * @since 4.3
 */
function elgg_get_login_forward_url(\ElggUser $user) {
	$session = _elgg_services()->session;
	if ($session->has('last_forward_from')) {
		$forward_url = $session->get('last_forward_from');
		$session->remove('last_forward_from');
		$forward_source = 'last_forward_from';
	} elseif (get_input('returntoreferer')) {
		$forward_url = REFERER;
		$forward_source = 'return_to_referer';
	} else {
		// forward to main index page
		$forward_url = '';
		$forward_source = null;
	}

	$params = [
		'user' => $user,
		'source' => $forward_source,
	];

	return elgg_trigger_plugin_hook('login:forward', 'user', $params, $forward_url);
}
