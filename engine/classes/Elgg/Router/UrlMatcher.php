<?php

namespace Elgg\Router;

use Elgg\EventsService;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * UrlMatcher Wrapper
 */
class UrlMatcher extends \Symfony\Component\Routing\Matcher\UrlMatcher {
	
	/**
	 * Create a new UrlMatcher
	 *
	 * @param RouteCollection          $routes              route collection
	 * @param RequestContext           $context             request context
	 * @param EventsService            $events              Elgg events service
	 * @param RouteRegistrationService $registrationService Elgg route registration service
	 */
	public function __construct(
		RouteCollection $routes,
		RequestContext $context,
		protected EventsService $events,
		protected RouteRegistrationService $registrationService
	) {
		parent::__construct($routes, $context);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function match(string $pathinfo): array {
		try {
			return parent::match($pathinfo);
		} catch (ResourceNotFoundException $e) {
			$result = $this->events->triggerResults('route:match', 'system', ['pathinfo' => $pathinfo]);
			if (is_array($result) && isset($result['route'])) {
				$name = $result['route'];
				
				// transform some keys inline with the route registration service
				// @see RouteRegistrationService::register()
				$transformed = $result;
				$transform_keys = [
					'controller',
					'file',
					'resource',
					'handler',
					'deprecated',
					'middleware',
					'detect_page_owner',
					'use_logged_in',
					'route',
				];
				foreach ($transform_keys as $key) {
					if (!isset($transformed[$key])) {
						continue;
					}
					
					$transformed["_{$key}"] = $transformed[$key];
					unset($transformed[$key]);
				}
				
				if (!$this->routes->get($name)) {
					$this->registrationService->register($name, $result);
				}
				
				return $transformed;
			}
			
			throw $e;
		}
	}
}
