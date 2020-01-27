<?php

namespace Elgg\Security;

use Elgg\UnitTestCase;

class PasswordGeneratorServiceUnitTest extends UnitTestCase {

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
	
	public function testGeneratePasswordWithSufficientLength() {
		$password = _elgg_services()->passwordGenerator->generatePassword(12);
		
		$this->assertIsString($password);
		$this->assertEquals(12, strlen($password));
	}
	
	public function testGeneratePasswordWithInsufficientLength() {
		$min_length = _elgg_services()->config->min_password_length;
		
		$password = _elgg_services()->passwordGenerator->generatePassword($min_length - 1);
		
		$this->assertIsString($password);
		$this->assertGreaterThanOrEqual($min_length, strlen($password));
	}
	
	public function testGeneratorGeneratesValidPassword() {
		
		$service = _elgg_services()->passwordGenerator;
		
		$password = $service->generatePassword();
		
		$this->assertTrue($service->isValidPassword($password));
		$this->assertNull($service->assertValidPassword($password));
	}
}
