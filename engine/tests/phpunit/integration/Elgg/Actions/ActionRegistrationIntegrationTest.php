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
			$provides[] = [$name];
		}

		return $provides;
	}

	/**
	 * @dataProvider actionsProvider
	 */
	public function testCanRequestActionWithoutParameters($name) {

		if (in_array($name, $this->skips)) {
			$this->markTestSkipped("Can not test action '{$name}'");
		}

		$response = $this->executeAction($name);

		$this->assertInstanceOf(ResponseBuilder::class, $response);
	}

	/**
	 * @dataProvider actionsProvider
	 */
	public function testCanRequestActionWithoutParametersViaAjax($name) {

		if (in_array($name, $this->skips)) {
			$this->markTestSkipped("Can not test action '{$name}'");
		}

		$response = $this->executeAction($name, [], 2);

		$this->assertInstanceOf(ResponseBuilder::class, $response);
	}
}
