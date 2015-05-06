<?php
namespace Elgg\Services;

/**
 * Describes an object that establishes JSON API endpoints
 *
 * @since 2.0.0
 */
interface AjaxApi {

	const RESPONSE_HOOK = 'ajax_api:response';

	/**
	 * Register an invokable class as a handler for JSON requests
	 *
	 * The class will be instantiated with no arguments. Then __invoke will be called, receiving two
	 * arguments: an \Elgg\AjaxApi\ApiResponse object, and the \Elgg\Application instance. The
	 * handler must return the ApiResponse.
	 *
	 * The response is finally passed through the hook [ajax_api:response, <endpoint>] before
	 * being used to construct the HTTP response.
	 *
	 * @param string $endpoint   The endpoint name
	 * @param string $class_name The handler class name
	 *
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public function register($endpoint, $class_name);
}
