<?php

namespace Elgg\Router;

use Elgg\Exceptions\InvalidParameterException;
use Elgg\PluginHooksService;
use Elgg\Router\Middleware\MaintenanceGatekeeper;
use Elgg\Router\Middleware\WalledGarden;
use Elgg\Traits\Loggable;

/**
 * Route registration service
 *
 * @internal
 */
class RouteRegistrationService {

	use Loggable;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var RouteCollection
	 */
	protected $routes;

	/**
	 * @var UrlGenerator
	 */
	protected $generator;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks     Hook service
	 * @param RouteCollection    $routes    Route collection
	 * @param UrlGenerator       $generator URL Generator
	 */
	public function __construct(
		PluginHooksService $hooks,
		RouteCollection $routes,
		UrlGenerator $generator
	) {
		$this->hooks = $hooks;
		$this->routes = $routes;
		$this->generator = $generator;
	}

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
	 * @return Route
	 * @throws InvalidParameterException
	 */
	public function register($name, array $params = []) {

		$params = $this->hooks->trigger('route:config', $name, $params, $params);

		$path = elgg_extract('path', $params);
		$controller = elgg_extract('controller', $params);
		$file = elgg_extract('file', $params);
		$resource = elgg_extract('resource', $params);
		$handler = elgg_extract('handler', $params);
		$middleware = elgg_extract('middleware', $params, []);
		$protected = elgg_extract('walled', $params, true);
		$deprecated = elgg_extract('deprecated', $params, '');
		$required_plugins = elgg_extract('required_plugins', $params, []);
		$detect_page_owner = (bool) elgg_extract('detect_page_owner', $params, false);

		if (!$path || (!$controller && !$resource && !$handler && !$file)) {
			throw new InvalidParameterException(
				__METHOD__ . ' requires "path" and one of controller parameters ("resource", "controller", "file" or "handler") to be set'
			);
		}

		$defaults = elgg_extract('defaults', $params, []);
		$requirements = elgg_extract('requirements', $params, []);
		$methods = elgg_extract('methods', $params, []);

		$patterns = [
			'guid' => '\d+',
			'group_guid' => '\d+',
			'container_guid' => '\d+',
			'owner_guid' => '\d+',
			'username' => '[\p{L}\p{M}\p{Nd}._-]+',
		];

		$path = trim($path, '/');
		$segments = explode('/', $path);
		foreach ($segments as &$segment) {
			// look for segments that are defined as optional with added ?
			// e.g. /blog/owner/{username?}

			$matches = [];
			if (!preg_match('/\{(\w*)(\?)?\}/i', $segment, $matches)) {
				continue;
			}

			$wildcard = $matches[1];
			if (!isset($defaults[$wildcard]) && isset($matches[2])) {
				$defaults[$wildcard] = ''; // make it optional
			}

			if (!isset($requirements[$wildcard])) {
				if (array_key_exists($wildcard, $patterns)) {
					$requirements[$wildcard] = $patterns[$wildcard];
				} else {
					$requirements[$wildcard] = '.+?';
				}
			}

			$segment = '{' . $wildcard . '}';
		}

		$path = '/' . implode('/', $segments);

		if ($protected !== false) {
			$middleware[] = WalledGarden::class;
		}
		
		$middleware[] = MaintenanceGatekeeper::class;

		$defaults['_controller'] = $controller;
		$defaults['_file'] = $file;
		$defaults['_resource'] = $resource;
		$defaults['_handler'] = $handler;
		$defaults['_deprecated'] = $deprecated;
		$defaults['_middleware'] = $middleware;
		$defaults['_required_plugins'] = $required_plugins;
		$defaults['_detect_page_owner'] = $detect_page_owner;

		$route = new Route($path, $defaults, $requirements, [
			'utf8' => true,
		], '', [], $methods);

		$this->routes->add($name, $route);

		return $route;
	}

	/**
	 * Unregister a route by its name
	 *
	 * @param string $name Name of the route
	 *
	 * @return void
	 */
	public function unregister($name) {
		$this->routes->remove($name);
	}

	/**
	 * Get route config from its name
	 *
	 * @param string $name Route name
	 *
	 * @return Route|null
	 */
	public function get($name) {
		return $this->routes->get($name);
	}

	/**
	 * Get all registered routes
	 * @return Route[]
	 */
	public function all() {
		return $this->routes->all();
	}

	/**
	 * Generate a absolute URL for a named route
	 *
	 * @param string $name       Route name
	 * @param array  $parameters Query parameters
	 *
	 * @return false|string
	 */
	public function generateUrl($name, array $parameters = []) {
		try {
			$route = $this->get($name);
			if ($route instanceof Route) {
				$deprecated = $route->getDefault('_deprecated');
				if (!empty($deprecated)) {
					elgg_deprecated_notice("The route \"{$name}\" has been deprecated.", $deprecated);
				}
			}
			
			$url = $this->generator->generate($name, $parameters, UrlGenerator::ABSOLUTE_URL);

			// make sure the url is always normalized so it is also usable in CLI
			return elgg_normalize_url($url);
		} catch (\Exception $exception) {
			$this->getLogger()->notice($exception->getMessage());
		}
		
		return false;
	}

	/**
	 * Populates route parameters from entity properties
	 *
	 * @param string           $name       Route name
	 * @param \ElggEntity|null $entity     Entity
	 * @param array            $parameters Preset parameters
	 *
	 * @return array|false
	 */
	public function resolveRouteParameters($name, \ElggEntity $entity = null, array $parameters = []) {
		$route = $this->routes->get($name);
		if (!$route) {
			return false;
		}

		$requirements = $route->getRequirements();
		$defaults = $route->getDefaults();
		$props = array_merge(array_keys($requirements), array_keys($defaults));

		foreach ($props as $prop) {
			if (substr($prop, 0, 1) === '_') {
				continue;
			}

			if (isset($parameters[$prop])) {
				continue;
			}

			if (!$entity) {
				$parameters[$prop] = '';
				continue;
			}

			switch ($prop) {
				case 'title' :
				case 'name' :
					$parameters[$prop] = elgg_get_friendly_title($entity->getDisplayName());
					break;

				default :
					$parameters[$prop] = $entity->$prop;
					break;
			}
		}

		return $parameters;
	}
}
