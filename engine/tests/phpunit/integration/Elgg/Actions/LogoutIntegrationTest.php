<?php

namespace Elgg\Actions;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Values;

/**
 * @group ActionsService
 * @group AccountActions
 * @group Logout
 */
class LogoutIntegrationTest extends ActionResponseTestCase {

	public function up() {
		parent::up();

		self::createApplication(['isolate' => true]);
	}

	public function down() {
		parent::down();
	}

	/**
	 * @expectedException \Elgg\GatekeeperException
	 */
	public function testLogoutFailsWithoutActiveSession() {
		$this->executeAction('logout');
	}

	public function testLogout() {

		$user = $this->createOne('user', [], [
			'password' => 123456,
			'language' => 'de',
		]);

		$user->save();

		elgg_set_user_validation_status($user->guid, true);

		login($user);

		$response = $this->executeAction('logout');

		$this->assertInstanceOf(OkResponse::class, $response);

		$messages = _elgg_services()->systemMessages->dumpRegister();
		$this->assertNotEmpty($messages['success']);
		$this->assertEquals(elgg_echo('logoutok', [], $user->language), array_shift($messages['success']));

		$this->assertNull(_elgg_services()->session->getLoggedInUser());
	}

	public function testCanUseLogoutActionWithoutTokens() {
		$user = $this->createOne('user', [], [
			'password' => 123456,
			'language' => 'de',
		]);

		$user->save();

		elgg_set_user_validation_status($user->guid, true);

		login($user);

		$response = $this->executeAction('logout', [], false, false);

		$this->assertInstanceOf(OkResponse::class, $response);

		$messages = _elgg_services()->systemMessages->dumpRegister();
		$this->assertNotEmpty($messages['success']);
		$this->assertEquals(elgg_echo('logoutok', [], $user->language), array_shift($messages['success']));

		$this->assertNull(_elgg_services()->session->getLoggedInUser());
	}

	public function testCanPreventLogoutWithAHook() {

		$user = $this->createOne('user', [], [
			'password' => 123456,
			'language' => 'de',
		]);

		$user->save();

		elgg_set_user_validation_status($user->guid, true);

		login($user);

		elgg_register_event_handler('logout:before', 'user', [Values::class, 'getFalse']);

		$response = $this->executeAction('logout');

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('logouterror'), $response->getContent());

		$this->assertEquals($user, _elgg_services()->session->getLoggedInUser());

		_elgg_services()->session->removeLoggedInUser();

		elgg_unregister_event_handler('logout:before', 'user', [Values::class, 'getFalse']);
	}
}