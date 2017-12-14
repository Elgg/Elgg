<?php

namespace Elgg\Actions;

use Elgg\IntegratedUnitTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group ActionsService
 */
class ActionRegistrationTestCase extends IntegratedUnitTestCase {

	/**
	 * Skip some actions that do not play well with testing suite
	 * @var array
	 */
	private $skips = [
		'logout',
	];

	public function up() {
		// Logging in admin so all actions are accessible
		_elgg_services()->session->setLoggedInUser($this->getAdmin());
	}

	public function down() {
		_elgg_services()->session->removeLoggedInUser();
	}

	public function actionsProvider() {
		self::createApplication();

		$provides = [];

		$actions = _elgg_services()->actions->getAllActions();

		foreach ($actions as $name => $params) {
			$provides[] = [
				$name,
				$params['file'],
				$params['access']
			];
		}

		return $provides;
	}

	/**
	 * @dataProvider actionsProvider
	 */
	public function testCanRequestActionWithoutParameters($name, $file, $access) {

		if (in_array($name, $this->skips)) {
			$this->markTestSkipped("Can not test action '$name'");
		}

		$request = $this->prepareHttpRequest("action/$name", 'POST', [], false, true);

		$this->assertTrue(_elgg_services()->router->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertInstanceOf(RedirectResponse::class, $response);
	}

	/**
	 * @dataProvider actionsProvider
	 */
	public function testCanRequestActionWithoutParametersViaAjax($name, $file, $access) {

		if (in_array($name, $this->skips)) {
			$this->markTestSkipped("Can not test action '$name'");
		}

		$request = $this->prepareHttpRequest("action/$name", 'POST', [], 2, true);

		$this->assertTrue(_elgg_services()->router->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertInstanceOf(Response::class, $response);
	}


}