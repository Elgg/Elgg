<?php
/**
 * Rest endpoint.
 * The API REST endpoint.
 *
 * @package Elgg
 * @subpackage API
 */

/**
 *  Start the Elgg engine
 */
require_once("../../engine/start.php");
global $CONFIG;

// Register the error handler
error_reporting(E_ALL);
set_error_handler('_php_api_error_handler');

// Register a default exception handler
set_exception_handler('_php_api_exception_handler');

// Check to see if the api is available
if ((isset($CONFIG->disable_api)) && ($CONFIG->disable_api == true)) {
	throw new SecurityException(elgg_echo('SecurityException:APIAccessDenied'));
}

// plugins should return true to control what API and user authentication handlers are registered
if (elgg_trigger_plugin_hook('rest', 'init', null, false) == false) {
	// for testing from a web browser, you can use the session PAM
	// do not use for production sites!!
	//register_pam_handler('pam_auth_session');

	// user token can also be used for user authentication
	register_pam_handler('pam_auth_usertoken');

	// simple API key check
	register_pam_handler('api_auth_key', "sufficient", "api");
	// hmac
	register_pam_handler('api_auth_hmac', "sufficient", "api");
}

// Get parameter variables
$method = get_input('method');
$result = null;

// this will throw an exception if authentication fails
authenticate_method($method);

$result = execute_method($method);


if (!($result instanceof GenericResult)) {
	throw new APIException(elgg_echo('APIException:ApiResultUnknown'));
}

// Output the result
echo elgg_view_page($method, elgg_view("api/output", array("result" => $result)));