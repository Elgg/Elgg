<?php

namespace Elgg\Actions;

use Elgg\Application;
use Elgg\Application\BootHandler;

class RegistrationIntegrationTest extends RegistrationIntegrationTestCase {
	
	public function up() {
		parent::up();
		
		$this->registerCoreActions(Application::$_instance);
	}
	
	protected function registerCoreActions(Application $app): void {
		$boot = new BootHandler($app);
		$reflector = new \ReflectionClass($boot);
		
		$actions = $reflector->getMethod('registerActions');
		$actions->setAccessible(true);
		$actions->invoke($boot);
		
		$routes = $reflector->getMethod('registerRoutes');
		$routes->setAccessible(true);
		$routes->invoke($boot);
	}
	
	public function actionsProvider(): array {
		$app = $this->createApplication([
			'isolate' => true,
		]);
		
		_elgg_services()->reset('actions');
		_elgg_services()->reset('routes');
		_elgg_services()->reset('routeCollection');
		
		$this->registerCoreActions($app);
		
		$result = [];
		
		$actions = _elgg_services()->actions->getAllActions();
		foreach ($actions as $name => $params) {
			$result[] = [$name, $params['access']];
		}
		
		return $result;
	}
}
