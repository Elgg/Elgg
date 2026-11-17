<?php

namespace Elgg\Actions;

use Elgg\Application;
use Elgg\Application\BootHandler;
use Elgg\Router\Middleware\AdminGatekeeper;
use Elgg\Router\Middleware\Gatekeeper;
use Elgg\Router\Middleware\LoggedOutGatekeeper;

class RegistrationIntegrationTest extends RegistrationIntegrationTestCase {
	
	public function up() {
		parent::up();
		
		$this->registerCoreActions(Application::$_instance);
	}
	
	protected static function registerCoreActions(Application $app): void {
		$boot = new BootHandler($app);
		
		self::invokeInaccessableMethod($boot, 'registerActions');
		self::invokeInaccessableMethod($boot, 'registerRoutes');
	}
	
	public static function actionsProvider(): array {
		$app = self::createApplication([
			'isolate' => true,
		]);
		
		_elgg_services()->reset('routes');
		_elgg_services()->reset('routeCollection');
		
		self::registerCoreActions($app);
		
		$result = [];
		
		$actions = _elgg_services()->routes->all();
		foreach ($actions as $name => $route) {
			if (!str_starts_with($name, 'action:')) {
				continue;
			}

			$action = substr($name, strlen('action:'));

			$access = 'public';
			$middleware = (array) $route->getDefault('_middleware');
			if (in_array(Gatekeeper::class, $middleware)) {
				$access = 'logged_in';
			} elseif (in_array(LoggedOutGatekeeper::class, $middleware)) {
				$access = 'logged_out';
			} elseif (in_array(AdminGatekeeper::class, $middleware)) {
				$access = 'admin';
			}
			
			$result[] = [$action, $access];
		}
		
		return $result;
	}
}
