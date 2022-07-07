<?php

namespace Elgg\Actions;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ResponseBuilder;

/**
 * @group ActionsService
 */
class ActionRegistrationIntegrationTest extends ActionResponseTestCase {

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
		_elgg_services()->session->setLoggedInUser($this->getAdmin());
	}

	public function actionsProvider() {
		$this->createApplication([
			'isolate' => true,
		]);

		$provides = [];

		$actions = _elgg_services()->actions->getAllActions();
		foreach ($actions as $name => $params) {
			$provides[] = [$name, $params['access']];
		}

		return $provides;
	}

	/**
	 * @dataProvider actionsProvider
	 */
	public function testCanRequestActionWithoutParameters($name, $access) {

		if (in_array($name, $this->skips)) {
			$this->markTestSkipped("Can not test action '{$name}'");
		}

		if ($access === 'logged_out') {
			_elgg_services()->session->removeLoggedInUser();
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
			_elgg_services()->session->removeLoggedInUser();
		}

		$response = $this->executeAction($name, [], 2);

		$this->assertInstanceOf(ResponseBuilder::class, $response);
	}
}
