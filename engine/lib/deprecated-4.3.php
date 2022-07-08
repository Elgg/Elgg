<?php
/**
 * Bundle all functions which have been deprecated in Elgg 4.3
 */

/**
 * Register a PAM handler.
 *
 * A PAM handler should return true if the authentication attempt passed. For a
 * failure, return false or throw an exception. Returning nothing indicates that
 * the handler wants to be skipped.
 *
 * Note, $handler must be string callback (not an array/Closure).
 *
 * @param string $handler    Callable global handler function in the format ()
 * 		                     pam_handler($credentials = null);
 * @param string $importance The importance - "sufficient" (default) or "required"
 * @param string $policy     The policy type, default is "user"
 *
 * @return bool
 * @deprecated 4.3 use elgg_register_pam_handler()
 */
function register_pam_handler($handler, $importance = 'sufficient', $policy = 'user') {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_register_pam_handler()', '4.3');
	
	return elgg_register_pam_handler($handler, $importance, $policy);
}

/**
 * Unregisters a PAM handler.
 *
 * @param string $handler The PAM handler function name
 * @param string $policy  The policy type, default is "user"
 *
 * @return void
 * @since 1.7.0
 * @deprecated 4.3 use elgg_unregister_pam_handler()
 */
function unregister_pam_handler($handler, $policy = 'user') {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_unregister_pam_handler()', '4.3');
	
	elgg_unregister_pam_handler($handler, $policy);
}

/**
 * Perform user authentication with a given username and password.
 *
 * @warning This returns an error message on failure. Use the identical operator to check
 * for access: if (true === elgg_authenticate()) { ... }.
 *
 * @see login()
 *
 * @param string $username The username
 * @param string $password The password
 *
 * @return true|string True or an error message on failure
 * @internal
 * @deprecated 4.3 use elgg_pam_authenticate()
 */
function elgg_authenticate($username, $password) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_pam_authenticate()', '4.3');
	
	$pam = new \ElggPAM('user');
	$credentials = ['username' => $username, 'password' => $password];
	$result = $pam->authenticate($credentials);
	if (!$result) {
		return $pam->getFailureMessage();
	}
	
	return true;
}

/**
 * Generates a unique invite code for a user
 *
 * @param string $username The username of the user sending the invitation
 *
 * @return string Invite code
 * @see elgg_validate_invite_code()
 * @deprecated 4.3 use elgg_generate_invite_code()
 */
function generate_invite_code($username) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_generate_invite_code()', '4.3');
	
	return elgg_generate_invite_code((string) $username);
}
