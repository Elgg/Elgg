<?php

namespace Elgg\Actions;

use Elgg\Application;
use Elgg\Application\BootHandler;

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
		
		_elgg_services()->reset('actions');
		_elgg_services()->reset('routes');
		_elgg_services()->reset('routeCollection');
		
		self::registerCoreActions($app);
		
		$result = [];
		
		$actions = _elgg_services()->actions->getAllActions();
		foreach ($actions as $name => $params) {
			$result[] = [$name, $params['access']];
		}
		
		return $result;
	}
}
