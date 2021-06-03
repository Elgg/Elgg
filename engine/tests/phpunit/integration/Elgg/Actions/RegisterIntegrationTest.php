<?php

namespace Elgg\Actions;

use Elgg\ActionResponseTestCase;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Hook;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

/**
 * @group ActionsService
 * @group AccountActions
 * @group Registration
 */
class RegisterIntegrationTest extends ActionResponseTestCase {

	public function up() {
		parent::up();

		self::createApplication(['isolate' => true]);

		_elgg_services()->config->min_password_length = 3;
		_elgg_services()->config->minusername = 4;
		_elgg_services()->config->allow_registration = true;

		_elgg_services()->hooks->backup();
		
		elgg_register_plugin_hook_handler('registeruser:validate:password', 'all', [_elgg_services()->passwordGenerator, 'registerUserPasswordValidation']);
	}

	public function down() {
		_elgg_services()->hooks->restore();

		parent::down();
	}

	public function testRegistrationFailsWithShortPassword() {

		$username = $this->getRandomUsername();
		$email = $this->getRandomEmail();

		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '12',
			'password2' => '12',
			'email' => $email,
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('Security:InvalidPasswordLengthException', [3]), $response->getContent());

		$this->assertFalse(get_user_by_username($username));
	}

	public function testRegistrationFailsWithInvalidPassword() {

		$hook = $this->registerTestingHook('registeruser:validate:password', 'all', function (Hook $hook) {
			if (strpos($hook->getParam('password'), 'X') === false) {
				return;
			}

			return false;
		});

		$username = $this->getRandomUsername();
		$email = $this->getRandomEmail();
		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '123X',
			'password2' => '123X',
			'email' => $email,
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('registration:passwordnotvalid'), $response->getContent());

		$hook->assertNumberOfCalls(1);
		$hook->unregister();

		$this->assertFalse(get_user_by_username($username));
	}

	public function testRegistrationFailsWithEmptyPassword() {

		$username = $this->getRandomUsername();
		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '',
			'password2' => ' ',
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('RegistrationException:EmptyPassword'), $response->getContent());

		$this->assertFalse(get_user_by_username($username));
	}

	public function testRegistrationFailsWithMismatchingPassword() {
		$username = $this->getRandomUsername();
		$email = $this->getRandomEmail();

		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '1111111111111',
			'password2' => '1111111111111 ',
			'email' => $email,
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('RegistrationException:PasswordMismatch'), $response->getContent());

		$this->assertFalse(get_user_by_username($username));
	}

	public function testRegistrationFailsWithInvalidUsername() {

		$email = $this->getRandomEmail();

		$response = $this->executeAction('register', [
			'username' => 'username\r\n',
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $email,
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		//$this->assertEquals(elgg_echo('registerbad'), $response->getContent());

		$this->assertFalse(get_user_by_username('username\r\n'));
	}

	public function testRegistrationFailsWithInvalidUsernameContainingBlacklistChar() {

		$email = $this->getRandomEmail();

		$response = $this->executeAction('register', [
			'username' => 'username?#',
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $email,
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		//$this->assertEquals(elgg_echo('registerbad'), $response->getContent());

		$this->assertFalse(get_user_by_username('username?#'));
	}

	public function testRegistrationFailsWithShortUsername() {

		$email = $this->getRandomEmail();

		$response = $this->executeAction('register', [
			'username' => 'abc',
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $email,
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('registration:usernametooshort', [4]), $response->getContent());

		$this->assertFalse(get_user_by_username('abc'));
	}

	public function testRegistrationFailsWithLongUsername() {
		$username = $this->getRandomUsername();
		$email = $this->getRandomEmail();

		$username = str_repeat('a', 150);
		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $email,
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('registration:usernametoolong', [128]), $response->getContent());

		$this->assertFalse(get_user_by_username($username));
	}

	public function testRegistrationFailsWithInvalidEmail() {

		$username = $this->getRandomUsername();

		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => "$username@",
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('registration:notemail'), $response->getContent());

		$this->assertFalse(get_user_by_username($username));
	}

	public function testRegistrationFailsWithExistingEmail() {

		$username = $this->getRandomUsername();
		$this->createUser([], [
			'email' => "$username@example.com",
		]);

		$username2 = $this->getRandomUsername();
		$response = $this->executeAction('register', [
			'username' => $username2,
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => "$username@example.com",
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('registration:dupeemail'), $response->getContent());
	}

	public function testRegistrationFailsWithExistingUsername() {

		$username = $this->getRandomUsername();
		$this->createUser([], [
			'username' => $username,
		]);

		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('registration:userexists'), $response->getContent());
	}

	public function testRegistrationSucceeds() {

		$username = $this->getRandomUsername();

		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$user = get_user_by_username($username);
		$this->assertInstanceOf(\ElggUser::class, $user);

		$this->assertEquals($user->guid, elgg_get_logged_in_user_guid());

		_elgg_services()->session->removeLoggedInUser();
	}


	public function testRegistrationSucceedsButExceptionThrownFromHook() {

		$hook = $this->registerTestingHook('register', 'user', function () {
			throw new RegistrationException('Hello');
		});

		$username = $this->getRandomUsername();

		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals('Hello', $response->getContent());

		$user = get_user_by_username($username);
		$this->assertFalse($user);

		$hook->assertNumberOfCalls(1);
		$hook->unregister();
	}
	
	public function testRegisterWithAdminValidation() {
		
		_elgg_services()->config->require_admin_validation = true;
		
		// re-register admin validation hooks
		_elgg_services()->hooks->registerHandler('register', 'user', 'Elgg\Users\Validation::checkAdminValidation', 999);
		
		$username = $this->getRandomUsername();
		
		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		/* @var $user \ElggUser */
		$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () use ($username) {
			return get_user_by_username($username);
		});
		$this->assertInstanceOf(\ElggUser::class, $user);
		$this->assertFalse($user->isValidated());
		$this->assertEmpty($user->validated_method);
		$this->assertFalse($user->isEnabled());
		
		$this->assertEmpty(elgg_get_logged_in_user_entity());
	}
	
	public function testRegisterWithoutValidation() {
		
		_elgg_services()->config->require_admin_validation = false;
	
		$username = $this->getRandomUsername();
		
		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		/* @var $user \ElggUser */
		$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () use ($username) {
			return get_user_by_username($username);
		});
		$this->assertInstanceOf(\ElggUser::class, $user);
		$this->assertTrue($user->isValidated());
		$this->assertEquals('register_action', $user->validated_method);
		$this->assertTrue($user->isEnabled());
		
		$this->assertNotEmpty(elgg_get_logged_in_user_entity());
		logout($user);
	}
}
