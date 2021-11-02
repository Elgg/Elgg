<?php

namespace Elgg\Users;

use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\IntegrationTestCase;

class AccountServiceIntegrationTest extends IntegrationTestCase {

	public function testShortPasswordFailsValidation() {
		$length = elgg()->config->min_password_length;
		$password = str_repeat('a', $length - 1);
		
		$this->expectException(RegistrationException::class);
		elgg()->accounts->assertValidPassword($password);
	}
	
	public function testAccountDataValidationFails() {
		
		$result = elgg()->accounts->validateAccountData('username#', '1', '', 'username@');
		
		$failures = $result->getFailures();
		
		$this->assertCount(4, $failures);
	}
}
