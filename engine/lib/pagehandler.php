<?php
/**
 * Elgg page handler functions
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
 */
function elgg_register_route(string $name, array $params = []): \Elgg\Router\Route {
	return _elgg_services()->routes->register($name, $params);
}

/**
 * Unregister a route by its name
 *
 * @param string $name Name of the route
 *
 * @return void
 */
function elgg_unregister_route(string $name): void {
	_elgg_services()->routes->unregister($name);
}

/**
 * Get a registered route by it's name
 *
 * @param string $name the route name
 *
 * @return \Elgg\Router\Route|null
 * @since 4.0
 */
function elgg_get_route(string $name): ?\Elgg\Router\Route {
	return _elgg_services()->routes->get($name);
}

/**
 * Find a registered route based on an url/path
 *
 * @param string $url the full url or an url path to find a route for
 *
 * @return \Elgg\Router\Route|null
 * @since 4.1
 */
function elgg_get_route_for_url(string $url): ?\Elgg\Router\Route {
	$url = elgg_normalize_url($url);
	
	$path = parse_url($url, PHP_URL_PATH);
	try {
		$route_info = _elgg_services()->urlMatcher->match($path);
		if (!isset($route_info['_route'])) {
			return null;
		}
		
		$route = _elgg_services()->routes->get($route_info['_route']);
		$route->setMatchedParameters($route_info);
		
		return $route;
	} catch (\Symfony\Component\Routing\Exception\ExceptionInterface $e) {
		// route matcher exception
		return null;
	}
}

/**
 * Get the route for the current request
 *
 * @return \Elgg\Router\Route|null
 * @since 4.0
 */
function elgg_get_current_route(): ?\Elgg\Router\Route {
	return _elgg_services()->request->getRoute();
}

/**
 * Get the route name for the current request
 *
 * @return string Will be an empty string if no current route is found
 * @since 4.1
 */
function elgg_get_current_route_name(): string {
	$route = _elgg_services()->request->getRoute();
	return isset($route) ? $route->getName() : '';
}

/**
 * Check if a route is registered
 *
 * @param string $name route name
 *
 * @return bool
 * @since 4.0
 */
function elgg_route_exists(string $name): bool {
	return _elgg_services()->routes->get($name) instanceof \Elgg\Router\Route;
}

/**
 * Returns the current page's complete URL.
 *
 * @return string
 * @since 4.3
 */
function elgg_get_current_url(): string {
	return _elgg_services()->request->getCurrentURL();
}

/**
 * Generate a URL for named route
 *
 * @param string $name       Route name
 * @param array  $parameters Parameters
 *
 * @return string|null
 */
function elgg_generate_url(string $name, array $parameters = []): ?string {
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
 * @return string|null
 */
function elgg_generate_entity_url(ElggEntity $entity, string $resource = 'view', string $subresource = null, array $parameters = []): ?string {

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

	return null;
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
function elgg_generate_action_url(string $action, array $query = [], bool $add_csrf_tokens = true): string {

	$url = elgg_http_add_url_query_elements("action/{$action}", $query);
	$url = elgg_normalize_url($url);

	if ($add_csrf_tokens) {
		$url = elgg_add_action_tokens_to_url($url);
	}

	return $url;
}

/**
 * Prepares a successful response to be returned by a page or an action handler
 *
 * @param mixed        $content     Response content
 *                                  In page handlers, response content should contain an HTML string
 *                                  In action handlers, response content can contain either a JSON string or an array of data
 * @param string|array $message     System message visible to the client
 *                                  Can be used by handlers to display a system message
 * @param string       $forward_url Forward URL
 *                                  Can be used by handlers to redirect the client on non-ajax requests
 * @param int          $status_code HTTP status code
 *                                  Status code of the HTTP response (defaults to 200)
 *
 * @return \Elgg\Http\OkResponse
 */
function elgg_ok_response($content = '', string|array $message = '', string $forward_url = null, int $status_code = ELGG_HTTP_OK): \Elgg\Http\OkResponse {
	if ($message) {
		elgg_register_success_message($message);
	}

	return new \Elgg\Http\OkResponse($content, $status_code, $forward_url);
}

/**
 * Prepare an error response to be returned by a page or an action handler
 *
 * @param string|array $message     Error message
 *                                  Can be used by handlers to display an error message
 *                                  For certain requests this error message will also be used as the response body
 * @param string       $forward_url URL to redirect the client to
 *                                  Can be used by handlers to redirect the client on non-ajax requests
 * @param int          $status_code HTTP status code
 *                                  Status code of the HTTP response
 *
 * @return \Elgg\Http\ErrorResponse
 */
function elgg_error_response(string|array $message = '', string $forward_url = REFERRER, int $status_code = ELGG_HTTP_BAD_REQUEST): \Elgg\Http\ErrorResponse {
	if ($message) {
		elgg_register_error_message($message);
		
		// needed to convert the error message back to a string as the ErrorResponse does not support an array of \ElggSystemMessage options
		$message = is_array($message) ? $message['message'] : $message;
	}

	return new \Elgg\Http\ErrorResponse($message, $status_code, $forward_url);
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
 */
function elgg_redirect_response(string $forward_url = REFERRER, int $status_code = ELGG_HTTP_FOUND): \Elgg\Http\RedirectResponse {
	return new Elgg\Http\RedirectResponse($forward_url, $status_code);
}

/**
 * Prepare a download response
 *
 * @param string $content  The content of the download
 * @param string $filename The filename when downloaded
 * @param bool   $inline   Is this an inline download (default: false, determines the 'Content-Disposition' header)
 * @param array  $headers  (optional) additional headers for the response
 *
 * @return \Elgg\Http\DownloadResponse
 * @since 5.0
 */
function elgg_download_response(string $content, string $filename = '', bool $inline = false, array $headers = []): \Elgg\Http\DownloadResponse {
	$response = new \Elgg\Http\DownloadResponse($content);
	$response->setHeaders($headers);
	$response->setFilename($filename, $inline);
	
	return $response;
}
