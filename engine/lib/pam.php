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
 * @package Elgg
 * @subpackage Core
 */

global $_PAM_HANDLERS;
$_PAM_HANDLERS = array();

global $_PAM_HANDLERS_MSG;
$_PAM_HANDLERS_MSG = array();

/**
 * Register a PAM handler.
 *
 * @param string $handler The handler function in the format
 * 		pam_handler($credentials = NULL);
 * @param string $importance The importance - "sufficient" (default) or "required"
 * @param string $policy - the policy type, default is "user"
 * @return boolean
 */
function register_pam_handler($handler, $importance = "sufficient", $policy = "user") {
	global $_PAM_HANDLERS;

	// setup array for this type of pam if not already set
	if (!isset($_PAM_HANDLERS[$policy])) {
		$_PAM_HANDLERS[$policy] = array();
	}
	
	if (is_callable($handler)) {
		$_PAM_HANDLERS[$policy][$handler] = new stdClass;

		$_PAM_HANDLERS[$policy][$handler]->handler = $handler;
		$_PAM_HANDLERS[$policy][$handler]->importance = strtolower($importance);

		return true;
	}

	return false;
}

/**
 * Unregisters a PAM handler.
 *
 * @param string $handler The PAM handler function name
 * @param string $policy - the policy type, default is "user"
 * @since 1.7.0
 */
function unregister_pam_handler($handler, $policy = "user") {
	global $_PAM_HANDLERS;

	unset($_PAM_HANDLERS[$policy][$handler]);
}

/**
 * Attempt to authenticate.
 * This function will process all registered PAM handlers or stop when the first 
 * handler fails. A handler fails by either returning false or throwing an 
 * exception. The advantage of throwing an exception is that it returns a message
 * through the global $_PAM_HANDLERS_MSG which can be used in communication with 
 * a user. The order that handlers are processed is determined by the order that
 * they were registered.
 *
 * If $credentials are provided the PAM handler should authenticate using the 
 * provided credentials, if not then credentials should be prompted for or 
 * otherwise retrieved (eg from the HTTP header or $_SESSION).
 *
 * @param mixed $credentials Mixed PAM handler specific credentials (e.g. username, password)
 * @param string $policy - the policy type, default is "user"
 * @return bool true if authenticated, false if not.
 */
function pam_authenticate($credentials = NULL, $policy = "user") {
	global $_PAM_HANDLERS, $_PAM_HANDLERS_MSG;

	$_PAM_HANDLERS_MSG = array();
	
	$authenticated = false;

	foreach ($_PAM_HANDLERS[$policy] as $k => $v) {
		$handler = $v->handler;
		$importance = $v->importance;

		try {
			// Execute the handler
			if ($handler($credentials)) {
				// Explicitly returned true
				$_PAM_HANDLERS_MSG[$k] = "Authenticated!";

				$authenticated = true;
			} else {
				$_PAM_HANDLERS_MSG[$k] = "Not Authenticated.";

				// If this is required then abort.
				if ($importance == 'required') {
					return false;
				}
			}
		} catch (Exception $e) {
			$_PAM_HANDLERS_MSG[$k] = "$e";

			// If this is required then abort.
			if ($importance == 'required') {
				return false;
			}
		}
	}

	return $authenticated;
}