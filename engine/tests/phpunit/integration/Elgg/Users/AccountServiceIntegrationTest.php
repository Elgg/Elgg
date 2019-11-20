<?php

namespace Elgg\Users;

use Elgg\IntegrationTestCase;

class AccountServiceIntegrationTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
	
	}

	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::down()
	 */
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
	
	public function testAccountDataValidationFails() {
		
		$result = elgg()->accounts->validateAccountData('username#', '1', '', 'username@');
		
		$failures = $result->getFailures();
		
		$this->assertCount(4, $failures);
	}
}

