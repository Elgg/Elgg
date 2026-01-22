<?php

namespace Elgg;

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\PageNotFoundException;
use Elgg\Exceptions\RuntimeException;
use Elgg\Http\Request as HttpRequest;
use Elgg\Http\ResponseBuilder;
use Elgg\Http\ResponseFactory;
use Elgg\Router\Middleware\MaintenanceGatekeeper;
use Elgg\Router\RouteCollection;
use Elgg\Router\UrlMatcher;
use Elgg\Traits\Debug\Profilable;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Delegates requests to controllers based on the registered configuration.
 *
 * Plugin devs should use elgg_register_route() to register a named route or define it in elgg-plugin.php
 *
 * @since 1.9.0
 * @internal
 */
class Router {

	use Profilable;

	/**
	 * Constructor
	 *
	 * @param EventsService   $events   Events service
	 * @param RouteCollection $routes   Route collection
	 * @param UrlMatcher      $matcher  URL Matcher
	 * @param HandlersService $handlers Handlers service
	 * @param ResponseFactory $response Response
	 */
	public function __construct(
		protected EventsService $events,
		protected RouteCollection $routes,
		protected UrlMatcher $matcher,
		protected HandlersService $handlers,
		protected ResponseFactory $response
	) {
	}

	/**
	 * Routes the request to a registered page handler
	 *
	 * This function triggers a `'route', $identifier` event so that plugins can
	 * modify the routing or handle a request.
	 *
	 * @param \Elgg\Http\Request $request The request to handle.
	 *
	 * @return boolean Whether the request was routed successfully.
	 */
	public function route(HttpRequest $request): bool {
		$this->beginTimer(['build page']);

		$request->validate();

		$response = $this->getResponse($request);

		if ($this->response->getSentResponse()) {
			return true;
		}

		$this->response->respond($response);
		
		return headers_sent();
	}

	/**
	 * Build a response
	 *
	 * @param \Elgg\Http\Request $request Request
	 *
	 * @return ResponseBuilder
	 * @throws PageNotFoundException
	 */
	public function getResponse(HttpRequest $request): ResponseBuilder {
		$response = $this->prepareResponse($request);
		
		if (!$response instanceof ResponseBuilder) {
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
	 * Prepare response
	 *
	 * @param \Elgg\Http\Request $request Request
	 *
	 * @return ResponseBuilder|null
	 * @throws BadRequestException
	 * @throws PageNotFoundException
	 */
	protected function prepareResponse(HttpRequest $request) {

		$segments = $request->getUrlSegments();
		$path = '/' . implode('/', $segments);

		try {
			$parameters = $this->matcher->match($path);

			$resource = (string) elgg_extract('_resource', $parameters);
			unset($parameters['_resource']);

			$handler = elgg_extract('_handler', $parameters);
			unset($parameters['_handler']);

			$controller = elgg_extract('_controller', $parameters);
			unset($parameters['_controller']);

			$file = elgg_extract('_file', $parameters);
			unset($parameters['_file']);

			$deprecated = elgg_extract('_deprecated', $parameters, '');
			unset($parameters['_deprecated']);
			
			$middleware = elgg_extract('_middleware', $parameters, []);
			unset($parameters['_middleware']);
			
			unset($parameters['_detect_page_owner']);
			unset($parameters['_use_logged_in']);

			$route = $this->routes->get($parameters['_route']);
			$route->setMatchedParameters($parameters);
			$request->setRoute($route);

			$envelope = new \Elgg\Request(elgg(), $request);
			$parameters['request'] = $envelope;

			if (!empty($deprecated)) {
				elgg_deprecated_notice("The route \"{$route->getName()}\" has been deprecated.", $deprecated);
			}
			
			// force presence of MaintenanceGatekeeper
			array_unshift($middleware, MaintenanceGatekeeper::class);
			
			foreach ($middleware as $callable) {
				$result = $this->handlers->call($callable, $envelope, null);
				if ($result[1] instanceof ResponseBuilder) {
					return $result[1];
				}
			}

			if ($handler) {
				return $this->getResponseFromHandler($handler, $envelope);
			} elseif ($controller) {
				$result = $this->handlers->call($controller, $envelope, null);
				if ($result[1] instanceof ResponseBuilder) {
					return $result[1];
				}
			} elseif ($file) {
				return $this->getResponseFromFile($file, $envelope);
			}
			
			$output = elgg_view_resource($resource, $parameters);
			return elgg_ok_response($output);
		} catch (ResourceNotFoundException $ex) {
			$envelope = new \Elgg\Request(elgg(), $request);
			$result = $this->handlers->call(MaintenanceGatekeeper::class, $envelope, null);
			if ($result[1] instanceof ResponseBuilder) {
				return $result[1];
			}
			
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
	 */
	protected function getResponseFromHandler($handler, \Elgg\Request $request) {
		if (!is_callable($handler)) {
			return null;
		}

		$path = trim($request->getPath(), '/');
		$segments = explode('/', $path);
		$identifier = array_shift($segments) ?: '';

		ob_start();
		try {
			$response = call_user_func($handler, $segments, $identifier, $request);
		} catch (\Exception $ex) {
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
	 */
	protected function getResponseFromFile(string $file, \Elgg\Request $request) {
		if (!is_file($file)) {
			throw new PageNotFoundException(elgg_echo('actionnotfound', [$request->getPath()]), ELGG_HTTP_NOT_IMPLEMENTED);
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
	 * Filter a request through the 'route:rewrite' event
	 *
	 * @param \Elgg\Http\Request $request Elgg request
	 *
	 * @return \Elgg\Http\Request
	 * @throws RuntimeException
	 */
	public function allowRewrite(HttpRequest $request) {
		$segments = $request->getUrlSegments();
		if (!empty($segments)) {
			$identifier = array_shift($segments);
		} else {
			$identifier = '';
		}

		$old = [
			'identifier' => $identifier,
			'segments' => $segments,
		];
		$new = $this->events->triggerResults('route:rewrite', $identifier, $old, $old);
		if ($new === $old) {
			return $request;
		}

		if (!isset($new['identifier']) ||
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
