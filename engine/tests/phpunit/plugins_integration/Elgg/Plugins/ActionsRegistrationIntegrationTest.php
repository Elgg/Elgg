<?php

namespace Elgg\Plugins;

use Elgg\Actions\RegistrationIntegrationTestCase;
use Elgg\Router\Middleware\AdminGatekeeper;
use Elgg\Router\Middleware\Gatekeeper as MiddlewareGateKeeper;
use Elgg\Router\Middleware\LoggedOutGatekeeper;
use PHPUnit\Framework\Attributes\DataProvider;

class ActionsRegistrationIntegrationTest extends RegistrationIntegrationTestCase {
	
	protected static function registerPluginActions(\ElggPlugin $plugin) {
		$plugin->register();
		$plugin->boot();
		$plugin->init();
		$plugin->getBootstrap()->ready();
	}
	
	public static function actionsProvider(): array {
		self::createApplication([
			'isolate' => true,
		]);
		
		$result = [];
		
		$plugins = elgg_get_plugins();
		foreach ($plugins as $plugin) {
			_elgg_services()->reset('routes');
			_elgg_services()->reset('routeCollection');
			
			self::registerPluginActions($plugin);
			
			$actions = self::getAllActions();
			foreach ($actions as $name => $params) {
				$result[] = [$name, $params['access'], $plugin];
			}
		}
		
		if (empty($result)) {
			// hack so test can check if there are no actions provided
			$result[] = [null, null];
		}
		
		return $result;
	}

	/**
	 * Get all actions
	 *
	 * @return array
	 */
	protected static function getAllActions(): array {
		$actions = [];
		$routes = _elgg_services()->routes->all();
		foreach ($routes as $name => $route) {
			if (!str_starts_with($name, 'action:')) {
				continue;
			}

			$action = substr($name, strlen('action:'));

			$access = 'public';
			$middleware = (array) $route->getDefault('_middleware');
			if (in_array(MiddlewareGateKeeper::class, $middleware)) {
				$access = 'logged_in';
			} elseif (in_array(LoggedOutGatekeeper::class, $middleware)) {
				$access = 'logged_out';
			} elseif (in_array(AdminGatekeeper::class, $middleware)) {
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

	#[DataProvider('actionsProvider')]
	public function testCanRequestActionWithoutParameters($name, $access, ?\ElggPlugin $plugin = null) {
		if (!isset($name)) {
			$this->markTestSkipped('no plugin actions to test');
		}
		
		$this->registerPluginActions($plugin);
		
		parent::testCanRequestActionWithoutParameters($name, $access);
	}

	#[DataProvider('actionsProvider')]
	public function testCanRequestActionWithoutParametersViaAjax($name, $access, ?\ElggPlugin $plugin = null) {
		if (!isset($name)) {
			$this->markTestSkipped('no plugin actions to test');
		}
		
		$this->registerPluginActions($plugin);
		
		parent::testCanRequestActionWithoutParametersViaAjax($name, $access);
	}
}
