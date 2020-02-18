<?php
/**
 * Helper functions
 */

use Elgg\WebServices\Middleware\ApiContextMiddleware;
use Elgg\WebServices\Middleware\ViewtypeMiddleware;
use Elgg\WebServices\Di\ApiRegistrationService;

/**
 * Expose a function as a web service.
 *
 * Limitations: Currently cannot expose functions which expect objects.
 * It also cannot handle arrays of bools or arrays of arrays.
 * Also, input will be filtered to protect against XSS attacks through the web services.
 *
 * The list of parameters should be in the format:
 *     "variable" = array (
 *         type => 'int' | 'bool' | 'float' | 'string' | 'array'
 *         required => true (default) | false
 *         default => value (optional)
 *     )
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

	ApiRegistrationService::instance()->registerApiMethod(
		$method,
		$function,
		$parameters ?: [],
		$description,
		$call_method,
		$require_api_auth,
		$require_user_auth,
		$assoc
	);

	return true;
}

/**
 * Unregister a web services method
 *
 * @param string $method The api name that was exposed
 * @return void
 */
function elgg_ws_unexpose_function($method) {
	ApiRegistrationService::instance()->unregisterApiMethod($method);
}

/**
 * Registers a web services handler
 *
 * @param string   $handler  Web services type
 * @param callable $function Your function name
 *
 * @return bool Depending on success
 */
function elgg_ws_register_service_handler($handler, $function) {
	
	$route_config = [
		'path' => "/services/api/{$handler}/{view}/{segments?}",
		'defaults' => [
			'view' => 'json',
		],
		'middleware' => [
			ApiContextMiddleware::class,
			ViewtypeMiddleware::class,
		],
		'requirements' => [
			'segments' => '.+',
		],
		'walled' => false,
	];
	
	if (is_callable($function, true)) {
		// function (eg: my_service_handler or \My\ServiceClass::handler)
		$route_config['handler'] = $function;
	} elseif (_elgg_services()->handlers->isCallable($function)) {
		// probably invokeable class
		$route_config['controller'] = $function;
	} else {
		return false;
	}
	
	elgg_register_route("default:service:{$handler}", $route_config);
	
	return true;
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
	elgg_unregister_route("default:service:{$handler}");
}
