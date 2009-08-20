<?php
	/**
	 * Rest endpoint.
	 * The API REST endpoint.
	 * 
	 * @package Elgg
	 * @subpackage API
	 * @author Curverider Ltd <info@elgg.com>
	 * @link http://elgg.org/
	 */

	/**
	 *  Start the Elgg engine
	 */
	require_once("../../engine/start.php");
	global $CONFIG;

	// Register the error handler
	error_reporting(E_ALL); 
	set_error_handler('__php_api_error_handler');
	
	// Register a default exception handler
	set_exception_handler('__php_api_exception_handler'); 
	
	// Check to see if the api is available
	if ((isset($CONFIG->disable_api)) && ($CONFIG->disable_api == true))
		throw new SecurityException(elgg_echo('SecurityException:APIAccessDenied'));

	// Register some default PAM methods, plugins can add their own
	register_pam_handler('pam_auth_session_or_hmac'); // Command must either be authenticated by a hmac or the user is already logged in
	register_pam_handler('pam_auth_usertoken', 'required'); // Either token present and valid OR method doesn't require one.
	register_pam_handler('pam_auth_anonymous_method'); // Support anonymous functions
	
	// Get parameter variables
	$method = get_input('method');
	$result = null;

	// Authenticate session
	if (pam_authenticate())
	{
		// Authenticated somehow, now execute.
		$token = "";		
		$params = get_parameters_for_method($method); // Use $CONFIG->input instead of $_REQUEST since this is called by the pagehandler
		if (isset($params['auth_token'])) $token = $params['auth_token'];

		$result = execute_method($method, $params, $token);
	}
	else
		throw new SecurityException(elgg_echo('SecurityException:NoAuthMethods'));
	
	// Finally output
	if (!($result instanceof GenericResult))
		throw new APIException(elgg_echo('APIException:ApiResultUnknown'));

	// Output the result
	page_draw($method, elgg_view("api/output", array("result" => $result)));
	
?>