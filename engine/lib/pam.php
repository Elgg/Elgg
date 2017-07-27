<?php
/**
 * Elgg Simple PAM library
 * Contains functions for managing authentication.
 * This is not a full implementation of PAM. It supports a single facility
 * (authentication) and allows multiple policies (user authentication is the
 * default). There are two control flags possible for each module: sufficient
 * or required. The entire chain for a policy is processed (or until a
 * required module fails). A module fails by returning false or throwing an
 * exception. The order that modules are processed is determined by the order
 * they are registered. For an example of a PAM, see pam_auth_userpass() in
 * sessions.php.
 *
 * For more information on PAMs see:
 * http://www.freebsd.org/doc/en/articles/pam/index.html
 *
 * @see \ElggPAM
 *
 * @package Elgg.Core
 * @subpackage Authentication.PAM
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
 */
function register_pam_handler($handler, $importance = "sufficient", $policy = "user") {
	// setup array for this type of pam if not already set
	if (!isset(\ElggPAM::$_handlers[$policy])) {
		\ElggPAM::$_handlers[$policy] = [];
	}

	// @todo remove requirement that $handle be a global function
	if (is_string($handler) && is_callable($handler, true)) {
		\ElggPAM::$_handlers[$policy][$handler] = new \stdClass;

		\ElggPAM::$_handlers[$policy][$handler]->handler = $handler;
		\ElggPAM::$_handlers[$policy][$handler]->importance = strtolower($importance);

		return true;
	}

	return false;
}

/**
 * Unregisters a PAM handler.
 *
 * @param string $handler The PAM handler function name
 * @param string $policy  The policy type, default is "user"
 *
 * @return void
 * @since 1.7.0
 */
function unregister_pam_handler($handler, $policy = "user") {
	unset(\ElggPAM::$_handlers[$policy][$handler]);
}
