<?php

namespace Elgg\Actions;

use Elgg\ActionResponseTestCase;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Exceptions\Http\Gatekeeper\RegistrationAllowedGatekeeperException;

/**
 * @group ActionsService
 * @group AccountActions
 * @group Registration
 */
class RegisterIntegrationTest extends ActionResponseTestCase {

	public function up() {
		parent::up();

		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'min_password_length' => 3,
				'minusername' => 4,
				'allow_registration' => true,
			],
		]);

		_elgg_services()->events->backup();
		
		elgg_register_event_handler('registeruser:validate:password', 'all', [_elgg_services()->passwordGenerator, 'registerUserPasswordValidation']);
	}

	public function down() {
		_elgg_services()->events->restore();

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

		$this->assertNull(elgg_get_user_by_username($username));
	}

	public function testRegistrationFailsWithInvalidPassword() {

		$event = $this->registerTestingEvent('registeruser:validate:password', 'all', function (\Elgg\Event $event) {
			if (strpos($event->getParam('password'), 'X') === false) {
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

		$event->assertNumberOfCalls(1);
		$event->unregister();

		$this->assertNull(elgg_get_user_by_username($username));
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

		$this->assertNull(elgg_get_user_by_username($username));
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

		$this->assertNull(elgg_get_user_by_username($username));
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

		$this->assertNull(elgg_get_user_by_username('username\r\n'));
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

		$this->assertNull(elgg_get_user_by_username('username?#'));
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

		$this->assertNull(elgg_get_user_by_username('abc'));
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

		$this->assertNull(elgg_get_user_by_username($username));
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

		$this->assertNull(elgg_get_user_by_username($username));
	}

	public function testRegistrationFailsWithExistingEmail() {

		$username = $this->getRandomUsername();
		$this->createUser([
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
		$this->createUser([
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

		$user = elgg_get_user_by_username($username);
		$this->assertInstanceOf(\ElggUser::class, $user);

		$this->assertEquals($user->guid, elgg_get_logged_in_user_guid());
	}

	public function testRegistrationSucceedsButExceptionThrownFromEvent() {

		$event = $this->registerTestingEvent('register', 'user', function () {
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

		$user = elgg_get_user_by_username($username);
		$this->assertNull($user);

		$event->assertNumberOfCalls(1);
		$event->unregister();
	}
	
	public function testRegisterWithAdminValidation() {
		
		_elgg_services()->config->require_admin_validation = true;
		
		// re-register admin validation events
		_elgg_services()->events->registerHandler('register', 'user', 'Elgg\Users\Validation::checkAdminValidation', 999);
		
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
			return elgg_get_user_by_username($username);
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
			return elgg_get_user_by_username($username);
		});
		$this->assertInstanceOf(\ElggUser::class, $user);
		$this->assertTrue($user->isValidated());
		$this->assertEquals('register_action', $user->validated_method);
		$this->assertTrue($user->isEnabled());
		
		$this->assertNotEmpty(elgg_get_logged_in_user_entity());
	}
	
	public function testRegistrationDisabledGatekeeper() {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'allow_registration' => false,
			],
		]);
		
		$this->expectException(RegistrationAllowedGatekeeperException::class);
		$this->expectExceptionMessage(elgg_echo('registerdisabled'));
		$this->executeAction('register', [
			'username' => $this->getRandomUsername(),
			'password' => '1234567890',
			'password2' => '1234567890',
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
		]);
	}
	
	public function testRegistrationDisabledGatekeeperWithValidInviteCode() {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'allow_registration' => false,
			],
		]);
		
		$inviting_user = $this->getRandomUser();
		
		$response = $this->executeAction('register', [
			'username' => $this->getRandomUsername(),
			'password' => '12',
			'password2' => '123', // intentional mistake
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
			'friend_guid' => $inviting_user->guid,
			'invitecode' => elgg_generate_invite_code($inviting_user->username),
		]);
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('RegistrationException:PasswordMismatch'), $response->getContent());
	}
	
	public function testRegistrationGatekeeperWithInvalidInviteCode() {
		$inviting_user = $this->getRandomUser();
		
		$this->expectException(RegistrationAllowedGatekeeperException::class);
		$this->expectExceptionMessage(elgg_echo('RegistrationAllowedGatekeeperException:invalid_invitecode'));
		$this->executeAction('register', [
			'username' => $this->getRandomUsername(),
			'password' => '1234567890',
			'password2' => '1234567890',
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
			'friend_guid' => $inviting_user->guid,
			'invitecode' => elgg_generate_invite_code($inviting_user->username) . 'fail',
		]);
	}
}
