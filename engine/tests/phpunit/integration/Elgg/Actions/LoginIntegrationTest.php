<?php

namespace Elgg\Actions;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @group ActionsService
 * @group AccountActions
 */
class LoginIntegrationTest extends ActionResponseTestCase {

	/**
	 * @var \ElggUser User during tests
	 */
	private $user;
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\ActionResponseTestCase::up()
	 */
	public function up() {
		parent::up();

		self::createApplication(['isolate'=> true]);
	}

	/**
	 * {@inheritDoc}
	 * @see \Elgg\ActionResponseTestCase::down()
	 */
	public function down() {
		
		if ($this->user instanceof \ElggUser) {
			$this->user->delete();
		}
		
		parent::down();
	}

	public function testLoginWithUsernameAndPassword() {

		$user = $this->user = $this->createUser([], [
			'password' => 123456,
			'language' => 'de',
		]);

		$user->setValidationStatus(true, 'login_test');

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

		$user = $this->user = $this->createUser([], [
			'password' => 123456,
		]);

		$user->setValidationStatus(true, 'login_test');

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

		$user = $this->user = $this->createUser([], [
			'password' => 123456,
		]);

		$user->setValidationStatus(true, 'login_test');

		$response = $this->executeAction('login', [
			'username' => $user->username,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('login:empty'), $response->getContent());
	}

	public function testLoginFailsWithIncorrectPassword() {

		$user = $this->user = $this->createUser([], [
			'password' => 123456,
		]);

		$user->setValidationStatus(true, 'login_test');

		$response = $this->executeAction('login', [
			'username' => $user->username,
			'password' => 654321,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('LoginException:PasswordFailure'), $response->getContent());
	}
	
	public function testLoginFailsWithDisabledUser() {

		elgg()->hooks->backup();

		$username = $this->getRandomUsername();
		$user = $this->user = $this->createUser([], [
			'username' => $username,
			'password' => 123456,
		]);

		elgg()->hooks->restore();

		elgg_call(ELGG_IGNORE_ACCESS, function () use ($user) {
			$user->setValidationStatus(true, 'login_test');
			$user->disable('', false);
			$user->save();
		});
		
		/* @var $user \ElggUser */
		$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($username) {
			return get_user_by_username($username);
		});
		
		$this->assertFalse($user->isEnabled());
		$user->invalidateCache();
		
		$response = $this->executeAction('login', [
			'username' => $user->username,
			'password' => 123456,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('LoginException:DisabledUser'), $response->getContent());
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

		$user = $this->user = $this->createUser([], [
			'password' => 123456,
			'banned' => true,
		]);

		$user->setValidationStatus(true, 'login_test');

		$response = $this->executeAction('login', [
			'username' => $user->username,
			'password' => 123456,
			'persistent' => false,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('LoginException:BannedUser'), $response->getContent());
	}

	public function testCanPreventLoginWithHook() {

		$handler = function(\Elgg\Event $hook) {
			return false;
		};

		_elgg_services()->events->registerHandler('login:before', 'user', $handler);

		$user = $this->user = $this->createUser([], [
			'password' => 123456,
			'language' => 'de',
		]);

		$user->setValidationStatus(true, 'login_test');

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
	
	public function testLoginEvents() {
		$user = $this->user = $this->createUser();
		
		$login_before_event = $this->registerTestingEvent('login:before', 'user', function(\Elgg\Event $event) {});
		$login_after_event = $this->registerTestingEvent('login:after', 'user', function(\Elgg\Event $event) {});
		$first_login_event = $this->registerTestingEvent('login:first', 'user', function(\Elgg\Event $event) {});
		
		$this->assertEmpty($user->first_login);
		$this->assertTrue(login($user));
		$login_before_event->assertNumberOfCalls(1);
		$login_after_event->assertNumberOfCalls(1);
		$first_login_event->assertNumberOfCalls(1);
		$first_login = $user->first_login;
		$this->assertNotEmpty($first_login);
		$user->first_login = $first_login - 1; // make sure it is different from current time
		$this->assertEquals($first_login - 1, $user->first_login);
		
		$login_before_event->assertObject($user);
		$login_after_event->assertObject($user);
		$first_login_event->assertObject($user);
		
		$this->assertTrue(logout($user));
		$this->assertTrue(login($user));
		
		$this->assertEquals($first_login - 1, $user->first_login);
		
		$login_before_event->assertNumberOfCalls(2);
		$login_after_event->assertNumberOfCalls(2);
		$first_login_event->assertNumberOfCalls(1);
		
		$this->assertTrue(logout($user));
	}

	public function testCanPersistLogin() {

		$user = $this->user = $this->createUser([], [
			'password' => 123456,
			'language' => 'de',
		]);
		
		$user->setValidationStatus(true, 'login_test');
		
		$action_response = $this->executeAction('login', [
			'username' => $user->username,
			'password' => 123456,
			'persistent' => true,
		]);
		
		$this->assertInstanceOf(OkResponse::class, $action_response);
		
		$messages = _elgg_services()->systemMessages->dumpRegister();
		$this->assertNotEmpty($messages['success']);
		$this->assertEquals(elgg_echo('loginok', [], $user->language), array_shift($messages['success']));
		
		$this->assertEquals($user, _elgg_services()->session->getLoggedInUser());
		
		_elgg_services()->session->removeLoggedInUser();
		
		ob_start();
		$response = _elgg_services()->responseFactory->respond($action_response);
		ob_end_clean();
		
		$this->assertInstanceOf(Response::class, $response);
		
		$response_cookies = $response->headers->getCookies();
		$this->assertNotEmpty($response_cookies);
		$this->assertIsArray($response_cookies);
		
		$persistent_cookie = false;
		$persistent_cookie_name = elgg()->config->getCookieConfig()['remember_me']['name'];
		/* @var $cookie \Symfony\Component\HttpFoundation\Cookie */
		foreach ($response_cookies as $cookie) {
			if ($cookie->getName() !== $persistent_cookie_name) {
				continue;
			}
			
			$persistent_cookie = $cookie;
			break;
		}
		
		$this->assertInstanceOf(Cookie::class, $persistent_cookie, 'No remember_me cookie found');
		$this->assertNotEmpty($persistent_cookie->getValue());
	}

	public function testRespectsLastForwardFrom() {

		$user = $this->user = $this->createUser([], [
			'password' => 123456,
		]);

		$user->setValidationStatus(true, 'login_test');

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
