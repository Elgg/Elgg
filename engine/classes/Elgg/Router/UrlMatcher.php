<?php

namespace Elgg\Router;

/**
 * UrlMatcher Wrapper
 */
class UrlMatcher extends \Symfony\Component\Routing\Matcher\UrlMatcher {
	
	/**
	 * Create a new UrlMatcher
	 *
	 * @param RouteCollection $routes  route collection
	 * @param RequestContext  $context request context
	 */
	public function __construct(RouteCollection $routes, RequestContext $context) {
		parent::__construct($routes, $context);
	}
}
