<?php

namespace Elgg\Actions;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ResponseBuilder;

abstract class RegistrationIntegrationTestCase extends ActionResponseTestCase {
	
	/**
	 * Skip some actions that do not play well with testing suite
	 * @var array
	 */
	protected $skips = [
		'admin/plugins/activate_all', // just skip this
		'admin/plugins/deactivate_all', // this would break other tests
		'diagnostics/download', // takes too long to complete
		'logout',
		'register', // handled by own test
	];
	
	public function up() {
		parent::up();
		
		// Logging in admin so all actions are accessible
		_elgg_services()->session_manager->setLoggedInUser($this->getAdmin());
		
		_elgg_services()->reset('actions');
		_elgg_services()->reset('routes');
		_elgg_services()->reset('routeCollection');
	}
	
	abstract public function actionsProvider(): array;
	
	/**
	 * @dataProvider actionsProvider
	 */
	public function testCanRequestActionWithoutParameters($name, $access) {
		if (in_array($name, $this->skips)) {
			$this->markTestSkipped("Can not test action '{$name}'");
		}
		
		if ($access === 'logged_out') {
			_elgg_services()->session_manager->removeLoggedInUser();
		}
		
		$response = $this->executeAction($name);
		
		$this->assertInstanceOf(ResponseBuilder::class, $response);
	}
	
	/**
	 * @dataProvider actionsProvider
	 */
	public function testCanRequestActionWithoutParametersViaAjax($name, $access) {
		if (in_array($name, $this->skips)) {
			$this->markTestSkipped("Can not test action '{$name}'");
		}
		
		if ($access === 'logged_out') {
			_elgg_services()->session_manager->removeLoggedInUser();
		}
		
		$response = $this->executeAction($name, [], 2);
		
		$this->assertInstanceOf(ResponseBuilder::class, $response);
	}
}
