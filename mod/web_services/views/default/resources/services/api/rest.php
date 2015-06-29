<?php

elgg_load_library('elgg:ws');

// Register the error handler
error_reporting(E_ALL);
set_error_handler('_php_api_error_handler');

// Register a default exception handler
set_exception_handler('_php_api_exception_handler');

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

$request = elgg_extract('request', $vars, '');
$request = explode('/', $request);

// Set viewtype to provide output in the response format specified by the request
$response_format = array_shift($request);
if (!elgg_is_registered_viewtype($response_format)) {
	throw new ApiException(elgg_echo('APIException:InvalidRestFormat', array($response_format)));
}

elgg_set_viewtype($response_format);

// Get parameter variables
$method = get_input('method');
$version = get_input('api_version', _elgg_ws_registry()->getApiVersion());

$result = _elgg_ws_registry()->get($method, $version)->execute();

if (!($result instanceof GenericResult)) {
	throw new APIException(elgg_echo('APIException:ApiResultUnknown'));
}

// Output the result
echo elgg_view_page($method, elgg_view("api/output", array("result" => $result)));
