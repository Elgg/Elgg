<?php

/**
 * Elgg web services API plugin
 */
elgg_register_event_handler('init', 'system', 'ws_init');

function ws_init() {
	$lib_dir = elgg_get_plugins_path() . "web_services/lib";
	elgg_register_library('elgg:ws', "$lib_dir/web_services.php");
	elgg_register_library('elgg:ws:api_user', "$lib_dir/api_user.php");
	elgg_register_library('elgg:ws:client', "$lib_dir/client.php");
	elgg_register_library('elgg:ws:tokens', "$lib_dir/tokens.php");

	elgg_load_library('elgg:ws:api_user');
	elgg_load_library('elgg:ws:tokens');

	elgg_register_page_handler('services', 'ws_page_handler');

	// expose the list of api methods
	elgg_ws_expose_function("system.api.list", "list_all_apis", null,
		elgg_echo("system.api.list"), "GET", false, false);

	// The authentication token api
	elgg_ws_expose_function(
		"auth.gettoken",
		"auth_gettoken",
		array(
			'username' => array ('type' => 'string'),
			'password' => array ('type' => 'string'),
		),
		elgg_echo('auth.gettoken'),
		'POST',
		false,
		false,
		true
	);

	elgg_register_plugin_hook_handler('unit_test', 'system', 'ws_unit_test');
}

/**
 * Instantiates web service registry
 * @return Elgg\WebServices\Registry
 * @since 2.0
 * @access private
 */
function _elgg_ws_registry() {
	static $registry;
	if (null == $registry) {
		$registry = new \Elgg\WebServices\Registry();
	}
	return $registry;
}

/**
 * Handles a web service request
 * 
 * Handles requests to /services:
 * /services/<service>/<handler>[/<format>[/<request>]]
 *
 * The plugin currently only handlers requests to /services/api/rest endpoint.
 * In order to implement a custom service or handler, create a resource view
 * in /resources/services/<service>/<handler>.php
 *
 * @param array $segments URL segments
 * @return bool
 */
function ws_page_handler($segments) {

	$service = array_shift($segments);
	$handler = array_shift($segments);

	if (!$service || !$handler) {
		return false;
	}

	echo elgg_view_resource("services/$service/$handler", ['request' => implode('/', $segments)]);
	return true;
}

/**
 * Expose a function as a web service.
 *
 * Limitations: Currently cannot expose functions which expect objects.
 * Also, input will be filtered to protect against XSS attacks through the web services.
 *
 * @param string $method            The api name to expose - for example "myapi.dosomething"
 * @param string $function          Callable
 * @param array  $parameters        (optional) List of parameters in the same order as in
 *                                  your function. Default values may be set for parameters which
 *                                  allow REST api users flexibility in what parameters are passed.
 *                                  Generally, optional parameters should be after required
 *                                  parameters.
 *
 *                                  This array should be in the format
 *                                    'parameter_name' = array (
 *                                  		type => 'int' | 'bool' | 'float' | 'string' | 'array'
 *                                  		required => true (default) | false
 *                                  		default => value (optional)
 *                                     )
 * @param string $description       (optional) Human readable description of the function.
 * @param string $call_method       (optional) Define what http method must be used for
 *                                  this function. Default: GET
 * @param bool   $require_api_auth  (optional) (default is true) Does this method
 *                                  require API authorization? (example: API key)
 * @param bool   $require_user_auth (optional) (default is false) Does this method
 *                                  require user authorization?
 * @param string $assoc             Pass input parameters to the callback function as an associative array
 *
 * @return bool
 * @throws InvalidParameterException
 */
function elgg_ws_expose_function($method, callable $function, array $parameters = null, $description = "", $call_method = "GET", $require_api_auth = true, $require_user_auth = false, $assoc = false) {

	$params = array(
		'method' => $method,
		'description' => $description,
		'function' => $function,
		'require_api_auth' => (bool) $require_api_auth,
		'require_user_auth' => (bool) $require_user_auth,
		'call_method' => strtoupper((string) $call_method),
		'parameters' => (array) $parameters,
		'assoc' => $assoc,
	);

	$api_method = \Elgg\WebServices\Method::factory($params);
	return _elgg_ws_registry()->register($api_method);
}

/**
 * Unregister a web services method
 *
 * @param string $method The api name that was exposed
 * @return void
 */
function elgg_ws_unexpose_function($method) {
	_elgg_ws_registry()->unregister($method);
}

/**
 * Simple api to return a list of all api's installed on the system.
 *
 * @return array
 * @access private
 */
function list_all_apis() {

	$methods = _elgg_ws_registry()->all();
	array_walk($methods, function($elem) {
		return (array) $elem;
	});
	ksort($methods);
	return $methods;
}

/**
 * Unit tests for web services
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function ws_unit_test($hook, $type, $value, $params) {
	elgg_load_library('elgg:ws');
	elgg_load_library('elgg:ws:client');
	$value[] = dirname(__FILE__) . '/tests/ElggCoreWebServicesApiTest.php';
	return $value;
}
