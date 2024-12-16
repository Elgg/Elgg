<?php

namespace Elgg\Router;

use Psr\Log\LoggerInterface;

/**
 * UrlGenerator Wrapper
 */
class UrlGenerator extends \Symfony\Component\Routing\Generator\UrlGenerator {
	
	/**
	 * Create a new UrlGenerator
	 *
	 * @param RouteCollection      $routes        route collection
	 * @param RequestContext       $context       request context
	 * @param null|LoggerInterface $logger        logger
	 * @param null|string          $defaultLocale optional locale to generate urls for
	 */
	public function __construct(RouteCollection $routes, RequestContext $context, ?LoggerInterface $logger = null, ?string $defaultLocale = null) {
		parent::__construct($routes, $context, $logger, $defaultLocale);
	}
}
