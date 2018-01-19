<?php
/**
 * Elgg web services API plugin
 */


/**
 * Web services init
 *
 * @return void
 */
function ws_init() {

	\Elgg\Includer::requireFileOnce(__DIR__ . "/lib/web_services.php");
	\Elgg\Includer::requireFileOnce(__DIR__ . "/lib/api_user.php");
	\Elgg\Includer::requireFileOnce(__DIR__ . "/lib/client.php");
	\Elgg\Includer::requireFileOnce(__DIR__ . "/lib/tokens.php");

	// Register a service handler for the default web services
	// The name rest is a misnomer as they are not RESTful
	elgg_ws_register_service_handler('rest', 'ws_rest_handler');

	// expose the list of api methods
	elgg_ws_expose_function("system.api.list", "list_all_apis", null,
		elgg_echo("system.api.list"), "GET", false, false);

	// The authentication token api
	elgg_ws_expose_function(
		"auth.gettoken",
		"auth_gettoken",
		[
			'username' =>  ['type' => 'string'],
			'password' =>  ['type' => 'string'],
		],
		elgg_echo('auth.gettoken'),
		'POST',
		false,
		false
	);

	elgg_register_plugin_hook_handler('rest:output', 'system.api.list', 'ws_system_api_list_hook');
}

/**
 * Handle a web service request
 *
 * Handles requests of format: http://site/services/api/handler/response_format/request
 * The first element after 'services/api/' is the service handler name as
 * registered by {@link register_service_handler()}.
 *
 * The remaining string is then passed to the {@link service_handler()}
 * which explodes by /, extracts the first element as the response format
 * (viewtype), and then passes the remaining array to the service handler
 * function registered by {@link register_service_handler()}.
 *
 * If a service handler isn't found, a 404 header is sent.
 *
 * @param array $segments URL segments
 *
 * @return bool
 */
function ws_page_handler($segments) {
	if (!isset($segments[0]) || $segments[0] != 'api') {
		return false;
	}
	array_shift($segments);

	$handler = array_shift($segments);
	$request = implode('/', $segments);

	service_handler($handler, $request);

	return true;
}

/**
 * A global array holding API methods.
 * The structure of this is
 * 	$API_METHODS = array (
 * 		$method => array (
 * 			"description" => "Some human readable description"
 * 			"function" = 'my_function_callback'
 * 			"parameters" = array (
 * 				"variable" = array ( // the order should be the same as the function callback
 * 					type => 'int' | 'bool' | 'float' | 'string'
 * 					required => true (default) | false
 *					default => value // optional
 * 				)
 * 			)
 * 			"call_method" = 'GET' | 'POST'
 * 			"require_api_auth" => true | false (default)
 * 			"require_user_auth" => true | false (default)
 * 		)
 *  )
 */
global $API_METHODS;
$API_METHODS = [];

/** Define a global array of errors */
global $ERRORS;
$ERRORS = [];

/**
 * Expose a function as a web service.
 *
 * Limitations: Currently cannot expose functions which expect objects.
 * It also cannot handle arrays of bools or arrays of arrays.
 * Also, input will be filtered to protect against XSS attacks through the web services.
 *
 * @param string   $method            The api name to expose - for example "myapi.dosomething"
 * @param callable $function          Callable to handle API call
 * @param array    $parameters        (optional) List of parameters in the same order as in
 *                                    your function. Default values may be set for parameters which
 *                                    allow REST api users flexibility in what parameters are passed.
 *                                    Generally, optional parameters should be after required
 *                                    parameters. If an optional parameter is not set and has no default,
 *                                    the API callable will receive null.
 *
 *                                    This array should be in the format
 *                                      "variable" = array (
 *                                          type => 'int' | 'bool' | 'float' | 'string' | 'array'
 *                                          required => true (default) | false
 *                                  	    default => value (optional)
 *                                  	 )
 * @param string   $description       (optional) human readable description of the function.
 * @param string   $call_method       (optional) Define what http method must be used for
 *                                    this function. Default: GET
 * @param bool     $require_api_auth  (optional) (default is false) Does this method
 *                                    require API authorization? (example: API key)
 * @param bool     $require_user_auth (optional) (default is false) Does this method
 *                                    require user authorization?
 * @param bool     $assoc             (optional) If set to true, the callback function will receive a single argument
 *                                    that contains an associative array of parameter => input pairs for the method.
 *
 * @return bool
 * @throws InvalidParameterException
 */
function elgg_ws_expose_function(
	$method,
	$function,
	$parameters = null,
	$description = "",
	$call_method = "GET",
	$require_api_auth = false,
	$require_user_auth = false,
	$assoc = false
) {

	global $API_METHODS;

	if (empty($method) || empty($function)) {
		$msg = elgg_echo('InvalidParameterException:APIMethodOrFunctionNotSet');
		throw new InvalidParameterException($msg);
	}

	// does not check whether this method has already been exposed - good idea?
	$API_METHODS[$method] = [];

	$API_METHODS[$method]["description"] = $description;

	// does not check whether callable - done in execute_method()
	$API_METHODS[$method]["function"] = $function;

	if ($parameters != null) {
		if (!is_array($parameters)) {
			$msg = elgg_echo('InvalidParameterException:APIParametersArrayStructure', [$method]);
			throw new InvalidParameterException($msg);
		}

		// catch common mistake of not setting up param array correctly
		$first = current($parameters);
		if (!is_array($first)) {
			$msg = elgg_echo('InvalidParameterException:APIParametersArrayStructure', [$method]);
			throw new InvalidParameterException($msg);
		}
	}

	if ($parameters != null) {
		// ensure the required flag is set correctly in default case for each parameter
		foreach ($parameters as $key => $value) {
			// check if 'required' was specified - if not, make it true
			if (!array_key_exists('required', $value)) {
				$parameters[$key]['required'] = true;
			}
		}

		$API_METHODS[$method]["parameters"] = $parameters;
	}

	$call_method = strtoupper($call_method);
	switch ($call_method) {
		case 'POST' :
			$API_METHODS[$method]["call_method"] = 'POST';
			break;
		case 'GET' :
			$API_METHODS[$method]["call_method"] = 'GET';
			break;
		default :
			$msg = elgg_echo('InvalidParameterException:UnrecognisedHttpMethod',
			[$call_method, $method]);

			throw new InvalidParameterException($msg);
	}

	$API_METHODS[$method]["require_api_auth"] = $require_api_auth;

	$API_METHODS[$method]["require_user_auth"] = $require_user_auth;

	$API_METHODS[$method]["assoc"] = (bool) $assoc;

	return true;
}

/**
 * Unregister a web services method
 *
 * @param string $method The api name that was exposed
 * @return void
 */
function elgg_ws_unexpose_function($method) {
	global $API_METHODS;

	if (isset($API_METHODS[$method])) {
		unset($API_METHODS[$method]);
	}
}

/**
 * Simple api to return a list of all api's installed on the system.
 *
 * @return array
 * @access private
 */
function list_all_apis() {
	global $API_METHODS;

	// sort first
	ksort($API_METHODS);

	return $API_METHODS;
}

/**
 * Registers a web services handler
 *
 * @param string $handler  Web services type
 * @param string $function Your function name
 *
 * @return bool Depending on success
 */
function elgg_ws_register_service_handler($handler, $function) {
	$servicehandler = _elgg_config()->servicehandler;
	if (!$servicehandler) {
		$servicehandler = [];
	}
	if (is_callable($function, true)) {
		$servicehandler[$handler] = $function;
		_elgg_config()->servicehandler = $servicehandler;
		return true;
	}

	return false;
}

/**
 * Remove a web service
 * To replace a web service handler, register the desired handler over the old on
 * with register_service_handler().
 *
 * @param string $handler web services type
 * @return void
 */
function elgg_ws_unregister_service_handler($handler) {
	$servicehandler = _elgg_config()->servicehandler;

	if (isset($servicehandler, $servicehandler[$handler])) {
		unset($servicehandler[$handler]);
		_elgg_config()->servicehandler = $servicehandler;
	}
}

/**
 * REST API handler
 *
 * @return void
 * @access private
 *
 * @throws SecurityException|APIException
 */
function ws_rest_handler() {

	$viewtype = elgg_get_viewtype();

	if (!elgg_view_exists('api/output', $viewtype)) {
		header("HTTP/1.0 400 Bad Request");
		header("Content-type: text/plain");
		echo "Missing view 'api/output' in viewtype '$viewtype'.";
		if (in_array($viewtype, ['xml', 'php'])) {
			echo "\nEnable the 'data_views' plugin to add this view.";
		}
		exit;
	}

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
	echo elgg_view_page($method, elgg_view("api/output", ["result" => $result]));
}

/**
 * Filters system API list to remove PHP internal function names
 *
 * @param string $hook   "rest:output"
 * @param string $type   "system.api.list"
 * @param array  $return API list
 * @param array  $params Method params
 * @return array
 */
function ws_system_api_list_hook($hook, $type, $return, $params) {

	if (!empty($return) && is_array($return)) {
		foreach ($return as $method => $settings) {
			unset($return[$method]['function']);
		}
	}

	return $return;
}

return function() {
	elgg_register_event_handler('init', 'system', 'ws_init');
};
