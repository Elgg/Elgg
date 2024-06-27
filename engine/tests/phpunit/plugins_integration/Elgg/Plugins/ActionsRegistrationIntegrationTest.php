<?php

namespace Elgg\Plugins;

use Elgg\Actions\RegistrationIntegrationTestCase;

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
			_elgg_services()->reset('actions');
			_elgg_services()->reset('routes');
			_elgg_services()->reset('routeCollection');
			
			self::registerPluginActions($plugin);
			
			$actions = _elgg_services()->actions->getAllActions();
			foreach ($actions as $name => $params) {
				$result[] = [$name, $params['access'], $plugin, $plugin->getID()];
			}
		}
		
		if (empty($result)) {
			// hack so test can check if there are no actions provided
			$result[] = [null, null];
		}
		
		return $result;
	}
	
	/**
	 * @dataProvider actionsProvider
	 */
	public function testCanRequestActionWithoutParameters($name, $access, \ElggPlugin $plugin = null) {
		if (!isset($name)) {
			$this->markTestSkipped('no plugin actions to test');
		}
		
		$this->registerPluginActions($plugin);
		
		parent::testCanRequestActionWithoutParameters($name, $access);
	}
	
	/**
	 * @dataProvider actionsProvider
	 */
	public function testCanRequestActionWithoutParametersViaAjax($name, $access, \ElggPlugin $plugin = null) {
		if (!isset($name)) {
			$this->markTestSkipped('no plugin actions to test');
		}
		
		$this->registerPluginActions($plugin);
		
		parent::testCanRequestActionWithoutParametersViaAjax($name, $access);
	}
}
