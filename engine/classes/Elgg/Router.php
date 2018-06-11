<?php

namespace Elgg;

use Elgg\Http\Request;
use Elgg\Http\ResponseBuilder;
use Elgg\Http\ResponseFactory;
use Elgg\Router\RouteCollection;
use Elgg\Router\UrlMatcher;
use Exception;
use InvalidParameterException;
use RuntimeException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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

		$request->validate();

		$response = $this->getResponse($request);

		if ($this->response->getSentResponse()) {
			return true;
		}

		if ($response instanceof ResponseBuilder) {
			$this->response->respond($response);
		}

		return headers_sent();
	}

	/**
	 * Build a response
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 * @throws Exception
	 * @throws PageNotFoundException
	 */
	public function getResponse(Request $request) {
		$response = $this->prepareLegacyResponse($request);

		if (!$response) {
			$response = $this->prepareResponse($request);
		}

		if (!$response) {
			throw new PageNotFoundException();
		}

		if ($request->getFirstUrlSegment() == 'action') {
			if ($response->getForwardURL() === null) {
				$response->setForwardURL(REFERRER);
			}
		}

		return $response;
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
	 * @return ResponseBuilder|null
	 * @throws Exception
	 * @throws PageNotFoundException
	 */
	protected function prepareResponse(Request $request) {

		$segments = $request->getUrlSegments();
		$path = '/' . implode('/', $segments);

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

			$route = $this->routes->get($parameters['_route']);
			$route->setMatchedParameters($parameters);
			$request->setRoute($route);

			$envelope = new \Elgg\Request(elgg(), $request);
			$parameters['request'] = $envelope;

			foreach ($middleware as $callable) {
				$result = $this->handlers->call($callable, $envelope, null);
				if ($result[1] instanceof ResponseBuilder) {
					return $result[1];
				}
			}

			if ($handler) {
				return $this->getResponseFromHandler($handler, $envelope);
			} else if ($controller) {
				$result =  $this->handlers->call($controller, $envelope, null);
				if ($result[1] instanceof ResponseBuilder) {
					return $result[1];
				}
			} else if ($file) {
				return $this->getResponseFromFile($file, $envelope);
			} else {
				$output = elgg_view_resource($resource, $parameters);
				return elgg_ok_response($output);
			}
		} catch (ResourceNotFoundException $ex) {
			throw new PageNotFoundException();
		} catch (MethodNotAllowedException $ex) {
			throw new BadRequestException();
		}
	}

	/**
	 * Get response from handler function
	 *
	 * @param callable      $handler Legacy page handler function
	 * @param \Elgg\Request $request Request envelope
	 *
	 * @return ResponseBuilder|null
	 * @throws Exception
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
	 * @throws PageNotFoundException
	 * @deprecated 3.0
	 */
	protected function getResponseFromFile($file, \Elgg\Request $request) {
		if (!is_file($file) || !is_readable($file)) {
			$path = $request->getPath();
			throw new PageNotFoundException(elgg_echo('actionnotfound', [$path]), ELGG_HTTP_NOT_IMPLEMENTED);
		}

		ob_start();

		try {
			$response = include $file;
		} catch (\Exception $ex) {
			ob_get_clean();
			throw $ex;
		}

		$output = ob_get_clean();

		if ($response instanceof ResponseBuilder) {
			return $response;
		}

		return elgg_ok_response($output);
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

		if (
			!isset($new['identifier']) ||
			!isset($new['segments']) ||
			!is_string($new['identifier']) ||
			!is_array($new['segments'])
		) {
			throw new RuntimeException('rewrite_path handler returned invalid route data.');
		}

		// rewrite request
		$segments = $new['segments'];
		array_unshift($segments, $new['identifier']);

		return $request->setUrlSegments($segments);
	}
}
