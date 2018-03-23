<?php

namespace Elgg;

use Elgg\Project\Paths;
use Elgg\Router\Middleware\ActionMiddleware;
use Elgg\Router\Middleware\AdminGatekeeper;
use Elgg\Router\Middleware\CsrfFirewall;
use Elgg\Router\Middleware\Gatekeeper;
use Elgg\Router\RouteRegistrationService;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access     private
 * @internal
 * @since      1.9.0
 */
class ActionsService {

	/**
	 * @var string[]
	 */
	private static $access_levels = ['public', 'logged_in', 'admin'];

	/**
	 * Actions for which CSRF firewall should be bypassed
	 * @var array
	 */
	private static $bypass_csrf = [
		'logout',
	];

	/**
	 * @var RouteRegistrationService
	 */
	protected $routes;

	/**
	 * @var HandlersService
	 */
	protected $handlers;

	/**
	 * Constructor
	 *
	 * @param RouteRegistrationService $routes   Routes
	 * @param HandlersService          $handlers Handlers service
	 */
	public function __construct(RouteRegistrationService $routes, HandlersService $handlers) {
		$this->routes = $routes;
		$this->handlers = $handlers;
	}

	/**
	 * Registers an action
	 *
	 * @param string          $action  The name of the action (eg "register", "account/settings/save")
	 * @param string|callable $handler Optionally, the filename where this action is located. If not specified,
	 *                                 will assume the action is in elgg/actions/<action>.php
	 * @param string          $access  Who is allowed to execute this action: public, logged_in, admin.
	 *                                 (default: logged_in)
	 *
	 * @return bool
	 *
	 * @see    elgg_register_action()
	 * @access private
	 * @throws \InvalidParameterException
	 */
	public function register($action, $handler = "", $access = 'logged_in') {
		// plugins are encouraged to call actions with a trailing / to prevent 301
		// redirects but we store the actions without it
		$action = trim($action, '/');

		if (empty($handler)) {
			$path = Paths::elgg() . 'actions';
			$handler = Paths::sanitize("$path/$action.php", false);
		}

		$file = null;
		$controller = null;

		if (is_string($handler) && substr($handler, -4) === '.php') {
			$file = $handler;
		} else {
			$controller = $handler;
		}

		if (!in_array($access, self::$access_levels)) {
			_elgg_services()->logger->error("Unrecognized value '$access' for \$access in " . __METHOD__);
			$access = 'admin';
		}

		$middleware = [];

		if (!in_array($action, self::$bypass_csrf)) {
			$middleware[] = CsrfFirewall::class;
		}

		if ($access == 'admin') {
			$middleware[] = AdminGatekeeper::class;
		} else if ($access == 'logged_in') {
			$middleware[] = Gatekeeper::class;
		}

		$middleware[] = ActionMiddleware::class;

		$this->routes->register("action:$action", [
			'path' => "/action/$action",
			'file' => $file,
			'controller' => $controller,
			'middleware' => $middleware,
			'walled' => false,
		]);

		return true;
	}

	/**
	 * Unregisters an action
	 *
	 * @param string $action Action name
	 *
	 * @return bool
	 *
	 * @see    elgg_unregister_action()
	 * @access private
	 */
	public function unregister($action) {
		$action = trim($action, '/');

		$route = $this->routes->get("action:$action");
		if (!$route) {
			return false;
		}

		$this->routes->unregister("action:$action");
		return true;
	}

	/**
	 * Check if an action is registered and its script exists.
	 *
	 * @param string $action Action name
	 *
	 * @return bool
	 *
	 * @see    elgg_action_exists()
	 * @access private
	 */
	public function exists($action) {
		$action = trim($action, '/');
		$route = $this->routes->get("action:$action");
		if (!$route) {
			return false;
		}

		$file = $route->getDefault('_file');
		$controller = $route->getDefault('_controller');

		if (!$file && !$controller) {
			return false;
		}

		if ($file && !file_exists($file)) {
			return false;
		}

		if ($controller && !$this->handlers->isCallable($controller)) {
			return false;
		}

		return true;
	}

	/**
	 * Get all actions
	 *
	 * @return array
	 */
	public function getAllActions() {
		$actions = [];
		$routes = $this->routes->all();
		foreach ($routes as $name => $route) {
			if (strpos($name, 'action:') !== 0) {
				continue;
			}

			$action = substr($name, 7);

			$access = 'public';
			$middleware = $route->getDefault('_middleware');
			if (in_array(Gatekeeper::class, $middleware)) {
				$access = 'logged_in';
			} else if (in_array(AdminGatekeeper::class, $middleware)) {
				$access = 'admin';
			}

			$actions[$action] = array_filter([
				'file' => $route->getDefault('_file'),
				'controller' => $route->getDefault('_controller'),
				'access' => $access,
			]);
		}

		return $actions;
	}
}

