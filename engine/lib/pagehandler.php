<?php
/**
 * Elgg page handler functions
 *
 * @package    Elgg.Core
 * @subpackage Routing
 */

/**
 * Register a new route
 *
 * Route paths can contain wildcard segments, i.e. /blog/owner/{username}
 * To make a certain wildcard segment optional, add ? to its name,
 * i.e. /blog/owner/{username?}
 *
 * Wildcard requirements for common named variables such as 'guid' and 'username'
 * will be set automatically.
 *
 * @warning If you are registering a route in the path of a route registered by
 *          deprecated {@link elgg_register_page_handler}, your registration must
 *          preceed the call to elgg_register_page_handler() in the boot sequence.
 *
 * @param string $name   Unique route name
 *                       This name can later be used to generate route URLs
 * @param array  $params Route parameters
 *                       - path : path of the route
 *                       - resource : name of the resource view
 *                       - defaults : default values of wildcard segments
 *                       - requirements : regex patterns for wildcard segment requirements
 *                       - methods : HTTP methods
 *
 * @return \Elgg\Router\Route
 * @throws InvalidParameterException
 */
function elgg_register_route($name, array $params = []) {
	return _elgg_services()->routes->register($name, $params);
}

/**
 * Unregister a route by its name
 *
 * @param string $name Name of the route
 *
 * @return void
 */
function elgg_unregister_route($name) {
	_elgg_services()->routes->unregister($name);
}

/**
 * Generate a URL for named route
 *
 * @param string $name       Route name
 * @param array  $parameters Parameters
 *
 * @return false|string
 */
function elgg_generate_url($name, array $parameters = []) {
	return _elgg_services()->routes->generateUrl($name, $parameters);
}

/**
 * Generate entity URL from a named route
 *
 * This function is intended to generate URLs from registered named routes that depend on entity type and subtype.
 * It will first try to detect routes that contain both type and subtype in the name, and will then fallback to
 * route names without the subtype, e.g. 'view:object:blog:attachments' and 'view:object:attachments'
 *
 * @tip Route segments will be automatically resolved from entity attributes and metadata,
 *      so given the path `/blog/view/{guid}/{title}/{status}` the path will be
 *      be resolved from entity guid, URL-friendly title and status metadata.
 *
 * @tip Parameters that do not have matching segment names in the route path, will be added to the URL as query
 *      elements.
 *
 *
 * @param ElggEntity $entity      Entity
 * @param string     $resource    Resource name
 * @param string     $subresource Subresource name
 * @param array      $parameters  URL query elements
 *
 * @return false|string
 */
function elgg_generate_entity_url(ElggEntity $entity, $resource = 'view', $subresource = null, array $parameters = []) {

	$make_route_name = function ($type, $subtype) use ($resource, $subresource) {
		$route_parts = [
			$resource,
			$type,
			$subtype,
			$subresource,
		];

		return implode(':', array_filter($route_parts));
	};

	$pairs = [
		[$entity->type, $entity->subtype],
		[$entity->type, null],
	];

	foreach ($pairs as $pair) {
		$route_name = $make_route_name($pair[0], $pair[1]);
		$params = _elgg_services()->routes->resolveRouteParameters($route_name, $entity, $parameters);
		if ($params !== false) {
			return elgg_generate_url($route_name, $params);
		}
	}

	return false;
}

/**
 * Generate an action URL
 *
 * @param string $action          Action name
 * @param array  $query           Query elements
 * @param bool   $add_csrf_tokens Add tokens
 *
 * @return string
 */
function elgg_generate_action_url($action, array $query = [], $add_csrf_tokens = true) {

	$url = "action/$action";
	$url = elgg_http_add_url_query_elements($url, $query);
	$url = elgg_normalize_url($url);

	if ($add_csrf_tokens) {
		$url = elgg_add_action_tokens_to_url($url);
	}

	return $url;
}

/**
 * Used at the top of a page to mark it as logged in users only.
 *
 * @return void
 * @throws \Elgg\Http\Exception\LoggedInGatekeeperException
 * @since 1.9.0
 */
function elgg_gatekeeper() {
	_elgg_services()->gatekeeper->assertAuthenticatedUser();
}

/**
 * Used at the top of a page to mark it as admin only.
 *
 * @return void
 * @throws \Elgg\Http\Exception\AdminGatekeeperException
 * @since 1.9.0
 */
function elgg_admin_gatekeeper() {
	_elgg_services()->gatekeeper->assertAuthenticatedAdmin();
}

/**
 * Can the viewer see this entity?
 *
 * Tests if the entity exists and whether the viewer has access to the entity
 * if it does. If the viewer cannot view this entity, it forwards to an
 * appropriate page.
 *
 * @param int    $guid    Entity GUID
 * @param string $type    Optional required entity type
 * @param string $subtype Optional required entity subtype
 *
 * @return void
 *
 * @throws Exception
 * @throws \Elgg\EntityNotFoundException
 * @throws \Elgg\EntityPermissionsException
 * @throws \Elgg\HttpException
 * @since 1.9.0
 */
function elgg_entity_gatekeeper($guid, $type = null, $subtype = null) {
	$entity = _elgg_services()->gatekeeper->assertExists($guid, $type, $subtype);
	_elgg_services()->gatekeeper->assertAccessibleEntity($entity);
}

/**
 * Require that the current request be an XHR. If not, execution of the current function
 * will end and a 400 response page will be sent.
 *
 * @return void
 * @throws \Elgg\Http\Exception\AjaxGatekeeperException
 * @since 1.12.0
 */
function elgg_ajax_gatekeeper() {
	_elgg_services()->gatekeeper->assertXmlHttpRequest();
}

/**
 * Prepares a successful response to be returned by a page or an action handler
 *
 * @param mixed  $content     Response content
 *                            In page handlers, response content should contain an HTML string
 *                            In action handlers, response content can contain either a JSON string or an array of data
 * @param string $message     System message visible to the client
 *                            Can be used by handlers to display a system message
 * @param string $forward_url Forward URL
 *                            Can be used by handlers to redirect the client on non-ajax requests
 * @param int    $status_code HTTP status code
 *                            Status code of the HTTP response (defaults to 200)
 *
 * @return \Elgg\Http\OkResponse
 */
function elgg_ok_response($content = '', $message = '', $forward_url = null, $status_code = ELGG_HTTP_OK) {
	if ($message) {
		system_message($message);
	}

	return new \Elgg\Http\OkResponse($content, $status_code, $forward_url);

}

/**
 * Prepare an error response to be returned by a page or an action handler
 *
 * @param string $error       Error message
 *                            Can be used by handlers to display an error message
 *                            For certain requests this error message will also be used as the response body
 * @param string $forward_url URL to redirect the client to
 *                            Can be used by handlers to redirect the client on non-ajax requests
 * @param int    $status_code HTTP status code
 *                            Status code of the HTTP response
 *                            For BC reasons and due to the logic in the client-side AJAX API,
 *                            this defaults to 200. Note that the Router and AJAX API will
 *                            treat these responses as error in spite of the HTTP code assigned
 *
 * @return \Elgg\Http\ErrorResponse
 */
function elgg_error_response($error = '', $forward_url = REFERRER, $status_code = ELGG_HTTP_OK) {
	if ($error) {
		register_error($error);
	}

	return new \Elgg\Http\ErrorResponse($error, $status_code, $forward_url);
}

/**
 * Prepare a silent redirect response to be returned by a page or an action handler
 *
 * @param string $forward_url Redirection URL
 *                            Relative or absolute URL to redirect the client to
 * @param int    $status_code HTTP status code
 *                            Status code of the HTTP response
 *                            Note that the Router and AJAX API will treat these responses
 *                            as redirection in spite of the HTTP code assigned
 *                            Note that non-redirection HTTP codes will throw an exception
 *
 * @return \Elgg\Http\RedirectResponse
 * @throws \InvalidArgumentException
 */
function elgg_redirect_response($forward_url = REFERRER, $status_code = ELGG_HTTP_FOUND) {
	return new Elgg\Http\RedirectResponse($forward_url, $status_code);
}
