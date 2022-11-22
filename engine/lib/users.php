<?php
/**
 * Elgg users
 * Functions to manage multiple or single users in an Elgg install
 */

/**
 * Get a user object from a GUID.
 *
 * This function returns an \ElggUser from a given GUID.
 *
 * @param int $guid The GUID
 *
 * @return \ElggUser|null
 */
function get_user(int $guid): ?\ElggUser {
	try {
		return _elgg_services()->entityTable->get($guid, 'user');
	} catch (\Elgg\Exceptions\DomainException $ex) {
		elgg_log($ex, 'ERROR');

		return null;
	} catch (\Elgg\Exceptions\ClassException $ex) {
		elgg_log($ex, 'ERROR');

		return null;
	}
}

/**
 * Get a user by username
 *
 * @param string $username  The user's username
 * @param bool   $try_email If the username contains an '@' try based on email first
 *
 * @return \ElggUser|null
 * @since 5.0
 */
function elgg_get_user_by_username(string $username, bool $try_email = false): ?\ElggUser {
	if (empty($username)) {
		return null;
	}
	
	if ($try_email && elgg_is_valid_email($username)) {
		$user = elgg_get_user_by_email($username);
		if ($user instanceof \ElggUser) {
			return $user;
		}
	}
	
	// Fixes #6052. Username is frequently sniffed from the path info, which,
	// unlike $_GET, is not URL decoded. If the username was not URL encoded,
	// this is harmless.
	$username = rawurldecode($username);
	if (empty($username)) {
		return null;
	}
	
	$logged_in_user = elgg_get_logged_in_user_entity();
	if (!empty($logged_in_user) && ($logged_in_user->username === $username)) {
		return $logged_in_user;
	}
	
	$users = elgg_get_entities([
		'types' => 'user',
		'metadata_name_value_pairs' => [
			[
				'name' => 'username',
				'value' => $username,
				'case_sensitive' => false,
			],
		],
		'limit' => 1,
	]);
	
	return $users ? $users[0] : null;
}

/**
 * Get a user from an email address
 *
 * @param string $email Email address
 *
 * @return \ElggUser|null
 * @since 5.0
 */
function elgg_get_user_by_email(string $email): ?\ElggUser {
	if (empty($email)) {
		return null;
	}
	
	$users = elgg_get_entities([
		'types' => 'user',
		'metadata_name_value_pairs' => [
			[
				'name' => 'email',
				'value' => $email,
				'case_sensitive' => false,
			],
		],
		'limit' => 1,
	]);
	
	return $users ? $users[0] : null;
}

/**
 * Generate and send a password request email to a given user's registered email address.
 *
 * @param \ElggUser $user the user to notify
 *
 * @return void
 *
 * @since 4.3
 */
function elgg_request_new_password(\ElggUser $user): void {
	_elgg_services()->passwords->requestNewPassword($user);
}

/**
 * Validate and change password for a user.
 *
 * @param \ElggUser $user      The user
 * @param string    $conf_code Confirmation code as sent in the request email.
 * @param string    $password  Optional new password, if not randomly generated.
 *
 * @return bool
 *
 * @since 4.3
 */
function elgg_save_new_password(\ElggUser $user, string $conf_code, string $password = null): bool {
	return _elgg_services()->passwords->saveNewPassword($user, $conf_code, $password);
}

/**
 * Generate a random 12 character clear text password.
 *
 * @return string
 *
 * @since 4.3
 */
function elgg_generate_password(): string {
	return _elgg_services()->passwordGenerator->generatePassword();
}

/**
 * Registers a user
 *
 * @param array $params Array of options with keys:
 *                      (string) username              => The username of the new user
 *                      (string) password              => The password
 *                      (string) name                  => The user's display name
 *                      (string) email                 => The user's email address
 *                      (string) subtype               => (optional) Subtype of the user entity
 *                      (string) language              => (optional) user language (defaults to current language)
 *                      (bool)   allow_multiple_emails => (optional) Allow the same email address to be registered multiple times (default false)
 *                      (bool)   validated             => (optional) Is the user validated (default true)
 *
 * @return \ElggUser
 * @throws \Elgg\Exceptions\Configuration\RegistrationException
 */
function elgg_register_user(array $params = []): \ElggUser {
	return _elgg_services()->accounts->register($params);
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
function elgg_validate_registration_data(string $username, string|array $password, string $name, string $email, bool $allow_multiple_emails = false): \Elgg\Validation\ValidationResults {
	return _elgg_services()->accounts->validateAccountData($username, $password, $name, $email, $allow_multiple_emails);
}

/**
 * Generates a unique invite code for a user
 *
 * @param string $username The username of the user sending the invitation
 *
 * @return string Invite code
 * @see elgg_validate_invite_code()
 * @since 4.3
 */
function elgg_generate_invite_code(string $username): string {
	return _elgg_services()->hmac->generateInviteCode($username);
}

/**
 * Validate a user's invite code
 *
 * @param string $username The username
 * @param string $code     The invite code
 *
 * @return bool
 * @see elgg_generate_invite_code()
 * @since 1.10
 */
function elgg_validate_invite_code(string $username, string $code): bool {
	return _elgg_services()->hmac->validateInviteCode($username, $code);
}

/**
 * Returns site's registration URL
 * Triggers a 'registration_url', 'site' event that can be used by
 * plugins to alter the default registration URL and append query elements, such as
 * an invitation code and inviting user's guid
 *
 * @param array  $parameters An array of query elements
 * @param string $fragment   Fragment identifier
 *
 * @return string
 */
function elgg_get_registration_url(array $parameters = [], string $fragment = ''): string {
	$url = elgg_generate_url('account:register', $parameters) . $fragment;
	
	return (string) elgg_trigger_event_results('registration_url', 'site', $parameters, $url);
}

/**
 * Returns site's login URL
 * Triggers a 'login_url', 'site' event that can be used by
 * plugins to alter the default login URL
 *
 * @param array  $query    An array of query elements
 * @param string $fragment Fragment identifier (e.g. #login-dropdown-box)
 * @return string
 */
function elgg_get_login_url(array $query = [], string $fragment = ''): string {
	$url = elgg_generate_url('account:login');
	$url = elgg_http_add_url_query_elements($url, $query) . $fragment;
	return (string) elgg_trigger_event_results('login_url', 'site', $query, $url);
}

/**
 * Get a user based on a persistent login token
 *
 * Please note the token should be the raw token, not hashed in any way.
 *
 * @param string $token the persistent token
 *
 * @return \ElggUser|null
 * @since 4.1
 */
function elgg_get_user_by_persistent_token(string $token): ?\ElggUser {
	return _elgg_services()->persistentLogin->getUserFromToken($token);
}
