<?php
/**
 * Helper functions
 */

use Elgg\WebServices\Middleware\ApiContextMiddleware;
use Elgg\WebServices\Middleware\ViewtypeMiddleware;
use Elgg\WebServices\Di\ApiRegistrationService;

/**
 * Unregister a web services method
 *
 * @param string $method              The API name that was exposed
 * @param string $http_request_method The HTTP call method (GET|POST|...)
 *
 * @return void
 */
function elgg_ws_unexpose_function(string $method, string $http_request_method = 'GET'): void {
	ApiRegistrationService::instance()->unregisterApiMethod($method, $http_request_method);
}

/**
 * Registers a web services handler
 *
 * @param string   $handler  Web services type
 * @param callable $function Your function name
 *
 * @return bool Depending on success
 */
function elgg_ws_register_service_handler(string $handler, $function): bool {
	
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
function elgg_ws_unregister_service_handler(string $handler): void {
	elgg_unregister_route("default:service:{$handler}");
}
