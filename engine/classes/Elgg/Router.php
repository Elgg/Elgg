<?php

namespace Elgg;

use Elgg\Http\RedirectResponse;
use Elgg\Http\Request;
use Elgg\Http\ResponseBuilder;
use Elgg\Http\ResponseFactory;
use Elgg\Router\Middleware\WalledGarden;
use Elgg\Router\Route;
use Elgg\Router\RouteCollection;
use Elgg\Router\UrlGenerator;
use Elgg\Router\UrlMatcher;
use ElggEntity;
use Exception;
use InvalidParameterException;
use RuntimeException;
use SecurityException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Delegates requests to controllers based on the registered configuration.
 *
 * Plugin devs should use elgg_register_route() to register a named route or define it in elgg-plugin.php
 *
 * @package    Elgg.Core
 * @subpackage Router
 * @since      1.9.0
 * @access     private
 */
class Router {

	use Profilable;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var RouteCollection
	 */
	protected $routes;

	/**
	 * @var UrlMatcher
	 */
	protected $matcher;

	/**
	 * @var HandlersService
	 */
	protected $handlers;

	/**
	 * @var ResponseFactory
	 */
	protected $response;

	/**
	 * @var Route
	 */
	protected $current_route;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks    Hook service
	 * @param RouteCollection    $routes   Route collection
	 * @param UrlMatcher         $matcher  URL Matcher
	 * @param HandlersService    $handlers Handlers service
	 * @param ResponseFactory    $response Response
	 */
	public function __construct(
		PluginHooksService $hooks,
		RouteCollection $routes,
		UrlMatcher $matcher,
		HandlersService $handlers,
		ResponseFactory $response
	) {
		$this->hooks = $hooks;
		$this->routes = $routes;
		$this->matcher = $matcher;
		$this->handlers = $handlers;
		$this->response = $response;
	}

	/**
	 * Routes the request to a registered page handler
	 *
	 * This function triggers a plugin hook `'route', $identifier` so that plugins can
	 * modify the routing or handle a request.
	 *
	 * @param Request $request The request to handle.
	 *
	 * @return boolean Whether the request was routed successfully.
	 * @throws InvalidParameterException
	 * @throws Exception
	 * @access private
	 */
	public function route(Request $request) {
		if ($this->timer) {
			$this->timer->begin(['build page']);
		}

		$response = $this->prepareLegacyResponse($request);

		if (!$response) {
			$response = $this->prepareResponse($request);
		}

		if ($this->response->getSentResponse()) {
			return true;
		}

		if ($response instanceof ResponseBuilder) {
			$this->response->respond($response);
		}

		return headers_sent();
	}

	/**
	 * Prepare legacy response by listening to "route" hook
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder|null
	 * @throws Exception
	 */
	protected function prepareLegacyResponse(Request $request) {

		$segments = $request->getUrlSegments();
		if ($segments) {
			$identifier = array_shift($segments);
		} else {
			$identifier = '';
			$segments = [];
		}

		$old = [
			'identifier' => $identifier,
			'handler' => $identifier, // backward compatibility
			'segments' => $segments,
		];

		if (!$this->hooks->hasHandler('route', $identifier) && !$this->hooks->hasHandler('route', 'all')) {
			return null;
		}

		elgg_deprecated_notice('"route" hook has been deprecated. Use named routes instead', '3.0');

		try {
			ob_start();

			$result = $this->hooks->trigger('route', $identifier, $old, $old);

			$output = ob_get_clean();

			if ($result instanceof ResponseBuilder) {
				return $result;
			} else if ($result === false) {
				return elgg_ok_response($output);
			} else if ($result !== $old) {
				elgg_log("'route' hook should not be used to rewrite routing path. Use 'route:rewrite' hook instead", 'ERROR');

				if ($identifier != $result['identifier']) {
					$identifier = $result['identifier'];
				} else if ($identifier != $result['handler']) {
					$identifier = $result['handler'];
				}

				$segments = elgg_extract('segments', $result, [], false);

				array_unshift($segments, $identifier);

				$forward_url = implode('/', $segments);

				return elgg_redirect_response($forward_url, ELGG_HTTP_PERMANENTLY_REDIRECT);
			}
		} catch (Exception $ex) {
			ob_end_clean();
			throw $ex;
		}
	}

	/**
	 * Prepare response
	 *
	 * @param Request $request Request
	 *
	 * @return bool|Http\ErrorResponse|Http\OkResponse|mixed
	 * @throws Exception
	 * @throws PageNotFoundException
	 */
	protected function prepareResponse(Request $request) {
		$response = false;

		$segments = $request->getUrlSegments();
		$path = '/' . implode('/', $segments);
		$url = elgg_normalize_url($path);

		try {
			$parameters = $this->matcher->match($path);

			$resource = elgg_extract('_resource', $parameters);
			unset($parameters['_resource']);

			$handler = elgg_extract('_handler', $parameters);
			unset($parameters['_handler']);

			$controller = elgg_extract('_controller', $parameters);
			unset($parameters['_controller']);

			$file = elgg_extract('_file', $parameters);
			unset($parameters['_file']);

			$middleware = elgg_extract('_middleware', $parameters, []);
			unset($parameters['_middleware']);

			$this->current_route = $this->routes->get($parameters['_route']);

			$parameters['_url'] = $url;
			$parameters['_path'] = $path;

			$this->current_route->setMatchedParameters($parameters);

			foreach ($parameters as $key => $value) {
				$request->getInputStack()->set($key, $value);
			}

			$envelope = new \Elgg\Request(elgg(), $this->current_route, $request);
			$parameters['request'] = $envelope;

			foreach ($middleware as $callable) {
				$this->handlers->call($callable, $envelope, null);
			}

			if ($handler) {
				$response = $this->getResponseFromHandler($handler, $envelope);
			} else if ($controller) {
				$response = $this->handlers->call($controller, $envelope, null);
			} else if ($file) {
				$response = $this->getResponseFromFile($file, $envelope);
			} else {
				$output = elgg_view_resource($resource, $parameters);
				$response = elgg_ok_response($output);
			}
		} catch (ResourceNotFoundException $ex) {
			// continue with the legacy logic
		} catch (MethodNotAllowedException $ex) {
			$response = elgg_error_response($ex->getMessage(), REFERRER, ELGG_HTTP_METHOD_NOT_ALLOWED);
		}

		return $response;
	}

	/**
	 * Get response from handler function
	 *
	 * @param callable      $handler Legacy page handler function
	 * @param \Elgg\Request $request Request envelope
	 *
	 * @return ResponseBuilder|null
	 * @deprecated 3.0
	 */
	protected function getResponseFromHandler($handler, \Elgg\Request $request) {
		if (!is_callable($handler)) {
			return null;
		}

		$path = trim($request->getPath(), '/');
		$segments = explode('/', $path);
		$identifier = array_shift($segments) ? : '';

		ob_start();
		try {
			$response = call_user_func($handler, $segments, $identifier, $request);
		} catch (Exception $ex) {
			ob_end_clean();
			throw $ex;
		}

		$output = ob_get_clean();

		if ($response instanceof ResponseBuilder) {
			return $response;
		} else if ($response === false) {
			return null;
		}

		return elgg_ok_response($output);
	}

	/**
	 * Get response from file
	 *
	 * @param string        $file    File
	 * @param \Elgg\Request $request Request envelope
	 *
	 * @return ResponseBuilder|null
	 * @deprecated 3.0
	 */
	protected function getResponseFromFile($file, \Elgg\Request $request) {
		if (!is_file($file) || !is_readable($file)) {
			throw new PageNotFoundException(elgg_echo('actionnotfound'), ELGG_HTTP_NOT_IMPLEMENTED);
		}

		ob_start();

		$response = include $file;

		$output = ob_get_clean();

		if ($response instanceof ResponseBuilder) {
			return $response;
		}

		return elgg_ok_response($output);
	}

	/**
	 * Returns current route
	 * @return Route
	 */
	public function getCurrentRoute() {
		return $this->current_route;
	}

	/**
	 * Filter a request through the route:rewrite hook
	 *
	 * @param Request $request Elgg request
	 *
	 * @return Request
	 * @access private
	 */
	public function allowRewrite(Request $request) {
		$segments = $request->getUrlSegments();
		if ($segments) {
			$identifier = array_shift($segments);
		} else {
			$identifier = '';
		}

		$old = [
			'identifier' => $identifier,
			'segments' => $segments,
		];
		$new = $this->hooks->trigger('route:rewrite', $identifier, $old, $old);
		if ($new === $old) {
			return $request;
		}

		if (!isset($new['identifier']) || !isset($new['segments']) || !is_string($new['identifier']) || !is_array($new['segments'])
		) {
			throw new RuntimeException('rewrite_path handler returned invalid route data.');
		}

		// rewrite request
		$segments = $new['segments'];
		array_unshift($segments, $new['identifier']);

		return $request->setUrlSegments($segments);
	}
}
