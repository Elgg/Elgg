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
		
		$this->invokeInaccessableMethod($boot, 'registerActions');
		$this->invokeInaccessableMethod($boot, 'registerRoutes');
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
