<?php

namespace Elgg\Users;

use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\Helpers\CustomUser;
use Elgg\UnitTestCase;

/**
 * @group Accounts
 * @group Registration
 */
class AccountsServiceUnitTest extends UnitTestCase {

	/**
	 * @var int minimal username length during testing
	 */
	protected $minusername = 6;
	
	/**
	 * @var int backup of the config setting for minimal username length
	 */
	protected $minusername_backup;
	
	public function up() {
		$this->minusername_backup = elgg()->config->minusername;
		elgg()->config->minusername = $this->minusername;
	}

	public function down() {
		elgg()->config->minusername = $this->minusername_backup;
	}

	/**
	 * @dataProvider invalidUsernameProvider
	 */
	public function testInvalidUsernameFailsValidation($username) {
		$this->expectException(RegistrationException::class);
		elgg()->accounts->assertValidUsername($username);
	}
	
	public function invalidUsernameProvider() {
		return [
			[str_repeat('a', $this->minusername - 1)], // too short
			[str_repeat('a', 129)], // too long, this is hard coded
			['username#'],
			['username@'],
		];
	}

	public function testInvalidEmailFailsValidation() {
		$this->expectException(RegistrationException::class);
		elgg()->accounts->assertValidEmail('username@');
	}
	
	/**
	 * @dataProvider validUsernameProvider
	 */
	public function testValidUsername($username) {
		elgg()->accounts->assertValidUsername($username);
	}
	
	public function validUsernameProvider() {
		return [
			['username'],
			['úsernâmé'],
			['user1234'],
			['123456789'],
			['user-name'],
			['user.name'],
			['user_name'],
			['देवनागरी'], // https://github.com/Elgg/Elgg/issues/12518 and https://github.com/Elgg/Elgg/issues/13067
		];
	}
	
	public function testAccountDataValidationFails() {

		$result = elgg()->accounts->validateAccountData('username#', '1', '', 'username@');

		$failures = $result->getFailures();

		$this->assertCount(3, $failures);
	}

	public function testCanRegister() {

		elgg_set_entity_class('user', 'custom', CustomUser::class);

		$pwd_length = _elgg_services()->config->min_password_length;

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
		
		$this->expectException(InvalidParameterException::class);
		elgg()->accounts->requestNewEmailValidation($user, $new_email);
	}
}
