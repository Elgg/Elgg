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

	/**
	 * @expectedException \RegistrationException
	 */
	public function testShortPasswordFailsValidation() {
		$length = elgg()->config->min_password_length;
		$password = str_repeat('a', $length - 1);
		elgg()->accounts->assertValidPassword($password);
	}

	/**
	 * @expectedException \RegistrationException
	 */
	public function testShortUsernameFailsValidation() {
		$length = elgg()->config->minusername;
		$username = str_repeat('a', $length - 1);
		elgg()->accounts->assertValidUsername($username);
	}

	/**
	 * @expectedException \RegistrationException
	 */
	public function testLongUsernameFailsValidation() {
		$length = 128;
		$username = str_repeat('a', $length + 1);
		elgg()->accounts->assertValidUsername($username);
	}

	/**
	 * @expectedException \RegistrationException
	 */
	public function testUsernameWithInvalidCharsFailsValidation() {
		elgg()->accounts->assertValidUsername('username#');
	}

	/**
	 * @expectedException \RegistrationException
	 */
	public function testInvalidUsernameFailsValidation() {
		elgg()->accounts->assertValidEmail('username@');
	}

	public function testAccountDataValidationFails() {

		$result = elgg()->accounts->validateAccountData('username#', '1', '', 'username@');

		$failures = $result->getFailures();

		$this->assertCount(4, $failures);
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


}

class CustomUser extends \ElggUser {

}