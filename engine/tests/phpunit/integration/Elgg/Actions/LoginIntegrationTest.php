<?php

namespace Elgg\Actions;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Values;

/**
 * @group ActionsService
 * @group AccountActions
 */
class LoginIntegrationTest extends ActionResponseTestCase {

	public function up() {
		parent::up();

		self::createApplication(['isolate'=> true]);
	}

	public function down() {
		parent::down();
	}

	public function testLoginWithUsernameAndPassword() {

		$user = $this->createOne('user', [], [
			'password' => 123456,
			'language' => 'de',
		]);

		$user->save();

		elgg_set_user_validation_status($user->guid, true);

		$response = $this->executeAction('login', [
			'username' => $user->username,
			'password' => 123456,
			'persistent' => false,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$messages = _elgg_services()->systemMessages->dumpRegister();
		$this->assertNotEmpty($messages['success']);
		$this->assertEquals(elgg_echo('loginok', [], $user->language), array_shift($messages['success']));

		$this->assertEquals($user, _elgg_services()->session->getLoggedInUser());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testLoginWithEmailAndPassword() {

		$user = $this->createOne('user', [], [
			'password' => 123456,
		]);

		elgg_set_user_validation_status($user->guid, true);

		$response = $this->executeAction('login', [
			'username' => $user->email,
			'password' => 123456,
			'persistent' => false,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertEquals($user, _elgg_services()->session->getLoggedInUser());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testLoginFailsWithEmptyPassword() {

		$user = $this->createOne('user', [], [
			'password' => 123456,
		]);

		elgg_set_user_validation_status($user->guid, true);

		$response = $this->executeAction('login', [
			'username' => $user->username,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('login:empty'), $response->getContent());
	}

	public function testLoginFailsWithIncorrectPassword() {

		$user = $this->createOne('user', [
			'password' => 123456,
		]);

		elgg_set_user_validation_status($user->guid, true);

		$response = $this->executeAction('login', [
			'username' => $user->username,
			'password' => 654321,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('LoginException:UsernameFailure'), $response->getContent());
	}

	public function testLoginFailsWithNonExistentUser() {

		$response = $this->executeAction('login', [
			'username' => $this->getRandomUsername(),
			'password' => 123456,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('LoginException:UsernameFailure'), $response->getContent());
	}

	public function testLoginFailsWithBannedUser() {

		$user = $this->createOne('user', [], [
			'password' => 123456,
			'banned' => true,
		]);
		/* @var $user \ElggUser */

		elgg_set_user_validation_status($user->guid, true);

		$response = $this->executeAction('login', [
			'username' => $user->username,
			'password' => 123456,
			'persistent' => false,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('LoginException:BannedUser'), $response->getContent());
	}

	public function testCanPreventLoginWithHook() {

		$handler = function() {
			return false;
		};

		_elgg_services()->events->registerHandler('login:before', 'user', $handler);

		$user = $this->createOne('user', [], [
			'password' => 123456,
			'language' => 'de',
		]);

		elgg_set_user_validation_status($user->guid, true);

		$response = $this->executeAction('login', [
			'username' => $user->username,
			'password' => 123456,
			'persistent' => false,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('LoginException:Unknown'), $response->getContent());

		$this->assertEmpty(_elgg_services()->session->getLoggedInUser());

		_elgg_services()->events->unregisterHandler('before:login', 'user', $handler);
	}

	public function testCanPersistLogin() {

		// Test that the user can login with persistent cookie
		$this->markTestIncomplete();
	}

	public function testRespectsLastForwardFrom() {

		$user = $this->createOne('user', [], [
			'password' => 123456,
		]);

		elgg_set_user_validation_status($user->guid, true);

		$last_forward_form = elgg_normalize_site_url('where_i_came_from');
		$forward_to = elgg_normalize_site_url('where_i_want_to_be');

		$hook_calls = 0;

		$forward_handler = function (\Elgg\Hook $hook) use ($user, $last_forward_form, $forward_to, &$hook_calls) {
			$this->assertEquals($last_forward_form, $hook->getValue());
			$this->assertEquals('last_forward_from', $hook->getParam('source'));
			$this->assertEquals($user, $hook->getParam('user'));
			$hook_calls++;
			return $forward_to;
		};

		elgg_register_plugin_hook_handler('login:forward', 'user', $forward_handler);

		_elgg_services()->session->set('last_forward_from', $last_forward_form);

		$response = $this->executeAction('login', [
			'username' => $user->username,
			'password' => 123456,
			'persistent' => false,
		]);

		$this->assertEquals(1, $hook_calls);

		$this->assertInstanceOf(OkResponse::class, $response);
		$this->assertEquals($forward_to, $response->getForwardURL());

		$this->assertEquals($user, _elgg_services()->session->getLoggedInUser());

		_elgg_services()->session->removeLoggedInUser();

		elgg_unregister_plugin_hook_handler('login:forward', 'user', $forward_handler);

	}
}
