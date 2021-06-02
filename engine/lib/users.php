<?php
/**
 * Elgg users
 * Functions to manage multiple or single users in an Elgg install
 */

use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\ClassException;
use Elgg\Exceptions\Configuration\RegistrationException;

/**
 * Get a user object from a GUID.
 *
 * This function returns an \ElggUser from a given GUID.
 *
 * @param int $guid The GUID
 *
 * @return \ElggUser|false
 */
function get_user($guid) {
	try {
		return _elgg_services()->entityTable->get($guid, 'user');
	} catch (InvalidParameterException $ex) {
		elgg_log($ex, 'ERROR');

		return false;
	} catch (ClassException $ex) {
		elgg_log($ex, 'ERROR');

		return false;
	}
}

/**
 * Get user by username
 *
 * @param string $username The user's username
 *
 * @return \ElggUser|false Depending on success
 */
function get_user_by_username($username) {
	return _elgg_services()->usersTable->getByUsername($username);
}

/**
 * Get user by persistent login password
 *
 * @param string $hash Hash of the persistent login password
 *
 * @return \ElggUser
 */
function get_user_by_code($hash) {
	return _elgg_services()->persistentLogin->getUserFromHash($hash);
}

/**
 * Get an array of users from an email address
 *
 * @param string $email Email address.
 *
 * @return array
 */
function get_user_by_email($email) {
	return _elgg_services()->usersTable->getByEmail($email);
}

/**
 * Return users (or the number of them) who have been active within a recent period.
 *
 * @param array $options Array of options with keys:
 *                       seconds (int)  => Length of period (default 600 = 10min)
 *                       limit   (int)  => Limit (default from settings)
 *                       offset  (int)  => Offset (default 0)
 *                       count   (bool) => Return a count instead of users? (default false)
 *
 * @return \ElggUser[]|int
 */
function find_active_users(array $options = []) {
	return _elgg_services()->usersTable->findActive($options);
}

/**
 * Render a list of currently online users
 *
 * @tip This also support options from elgg_list_entities().
 *
 * @param array $options Options array with keys:
 *                       seconds (int) => Number of seconds (default 600 = 10min)
 *
 * @return string
 */
function get_online_users(array $options = []) {
	$options = array_merge([
		'seconds' => 600,
	], $options);

	return elgg_list_entities($options, 'find_active_users');
}

/**
 * Generate and send a password request email to a given user's registered email address.
 *
 * @param int $user_guid User GUID
 *
 * @return false|array
 */
function send_new_password_request($user_guid) {
	return _elgg_services()->passwords->sendNewPasswordRequest($user_guid);
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
	return _elgg_services()->passwords->forcePasswordReset($user_guid, $password);
}

/**
 * Validate and change password for a user.
 *
 * @param int    $user_guid The user id
 * @param string $conf_code Confirmation code as sent in the request email.
 * @param string $password  Optional new password, if not randomly generated.
 *
 * @return bool True on success
 */
function execute_new_password_request($user_guid, $conf_code, $password = null) {
	return _elgg_services()->passwords->executeNewPasswordReset($user_guid, $conf_code, $password);
}

/**
 * Generate a random 12 character clear text password.
 *
 * @return string
 */
function generate_random_cleartext_password() {
	return _elgg_services()->passwordGenerator->generatePassword();
}

/**
 * Registers a user, returning false if the username already exists
 *
 * @param string $username              The username of the new user
 * @param string $password              The password
 * @param string $name                  The user's display name
 * @param string $email                 The user's email address
 * @param bool   $allow_multiple_emails Allow the same email address to be
 *                                      registered multiple times?
 * @param string $subtype               Subtype of the user entity
 * @param array  $params                Additional parameters
 *
 * @return int|false The new user's GUID; false on failure
 * @throws RegistrationException
 */
function register_user($username, $password, $name, $email, $allow_multiple_emails = false, $subtype = null, array $params = []) {
	return elgg()->accounts->register($username, $password, $name, $email, $allow_multiple_emails, $subtype, $params);
}

/**
 * Assert that given registration details are valid and can be used to register the user
 *
 * @param string       $username              The username of the new user
 * @param string|array $password              The password
 *                                            Can be an array [$password, $confirm_password]
 * @param string       $name                  The user's display name
 * @param string       $email                 The user's email address
 * @param bool         $allow_multiple_emails Allow the same email address to be
 *                                            registered multiple times?
 *
 * @return \Elgg\Validation\ValidationResults
 */
function elgg_validate_registration_data($username, $password, $name, $email, $allow_multiple_emails = false) {
	return elgg()->accounts->validateAccountData($username, $password, $name, $email, $allow_multiple_emails);
}

/**
 * Generates a unique invite code for a user
 *
 * @param string $username The username of the user sending the invitation
 *
 * @return string Invite code
 * @see elgg_validate_invite_code()
 */
function generate_invite_code($username) {
	return _elgg_services()->usersTable->generateInviteCode($username);
}

/**
 * Validate a user's invite code
 *
 * @param string $username The username
 * @param string $code     The invite code
 *
 * @return bool
 * @see   generate_invite_code()
 * @since 1.10
 */
function elgg_validate_invite_code($username, $code) {
	return _elgg_services()->usersTable->validateInviteCode($username, $code);
}

/**
 * Returns site's registration URL
 * Triggers a 'registration_url', 'site' plugin hook that can be used by
 * plugins to alter the default registration URL and append query elements, such as
 * an invitation code and inviting user's guid
 *
 * @param array  $query    An array of query elements
 * @param string $fragment Fragment identifier
 * @return string
 */
function elgg_get_registration_url(array $query = [], $fragment = '') {
	$url = elgg_normalize_url(elgg_generate_url('account:register'));
	$url = elgg_http_add_url_query_elements($url, $query) . $fragment;
	return elgg_trigger_plugin_hook('registration_url', 'site', $query, $url);
}

/**
 * Returns site's login URL
 * Triggers a 'login_url', 'site' plugin hook that can be used by
 * plugins to alter the default login URL
 *
 * @param array  $query    An array of query elements
 * @param string $fragment Fragment identifier (e.g. #login-dropdown-box)
 * @return string
 */
function elgg_get_login_url(array $query = [], $fragment = '') {
	$url = elgg_normalize_url(elgg_generate_url('account:login'));
	$url = elgg_http_add_url_query_elements($url, $query) . $fragment;
	return elgg_trigger_plugin_hook('login_url', 'site', $query, $url);
}
