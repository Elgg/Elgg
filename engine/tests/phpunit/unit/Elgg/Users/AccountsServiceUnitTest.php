<?php

namespace Elgg\Users;

use Elgg\UnitTestCase;

/**
 * @group Accounts
 * @group Registration
 */
class AccountsServiceUnitTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testShortUsernameFailsValidation() {
		$length = elgg()->config->minusername;
		$username = str_repeat('a', $length - 1);
		
		$this->expectException(\RegistrationException::class);
		elgg()->accounts->assertValidUsername($username);
	}

	public function testLongUsernameFailsValidation() {
		$length = 128;
		$username = str_repeat('a', $length + 1);
		
		$this->expectException(\RegistrationException::class);
		elgg()->accounts->assertValidUsername($username);
	}

	public function testUsernameWithInvalidCharsFailsValidation() {
		$this->expectException(\RegistrationException::class);
		elgg()->accounts->assertValidUsername('username#');
	}

	public function testInvalidUsernameFailsValidation() {
		$this->expectException(\RegistrationException::class);
		elgg()->accounts->assertValidEmail('username@');
	}

	public function testAccountDataValidationFails() {

		$result = elgg()->accounts->validateAccountData('username#', '1', '', 'username@');

		$failures = $result->getFailures();

		$this->assertCount(3, $failures);
	}

	public function testCanRegister() {

		elgg_set_entity_class('user', 'custom', CustomUser::class);

		$pwd_length = _elgg_config()->min_password_length;

		$username = 'username' . rand(100, 999);
		$password = str_repeat('a', $pwd_length + 1);
		$name = 'Random User';
		$email = "$username@example.com";

		$result = elgg()->accounts->validateAccountData($username, $password, $name, $email);

		$failures = $result->getFailures();

		$this->assertFalse($failures);

		$guid = elgg()->accounts->register($username, $password, $name, $email, false, 'custom');

		$this->assertNotFalse($guid);

		$user = get_entity($guid);

		$this->assertInstanceOf(CustomUser::class, $user);

		$this->assertEquals($username, $user->username);
		$this->assertEquals($name, $user->name);
		$this->assertEquals($email, $email);
	}

	public function testEmailChangeRequest() {
		$user = $this->createUser();
		$new_email = uniqid() . '@example.com';
		
		$this->assertTrue(elgg()->accounts->requestNewEmailValidation($user, $new_email));
	}
	
	public function testEmailChangeRequestWithInvalidEmail() {
		$user = $this->createUser();
		$new_email = 'example.com';
		
		$this->expectException(\InvalidParameterException::class);
		elgg()->accounts->requestNewEmailValidation($user, $new_email);
	}
}

class CustomUser extends \ElggUser {

}
