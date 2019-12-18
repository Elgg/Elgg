<?php

namespace Elgg\Security;

use Elgg\IntegrationTestCase;
use Hackzilla\PasswordGenerator\Generator\RequirementPasswordGenerator;
use Elgg\Exceptions\Security\InvalidPasswordLengthException;
use Elgg\Exceptions\Security\InvalidPasswordCharacterRequirementsException;

class PasswordGeneratorServiceIntegrationTest extends IntegrationTestCase {

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
	 * @dataProvider invalidPasswordProvider
	 */
	public function testInvalidPasswords($min_length, $lower, $upper, $number, $special, $password) {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'min_password_length' => $min_length,
				'min_password_lower' => $lower,
				'min_password_upper' => $upper,
				'min_password_number' => $number,
				'min_password_special' => $special,
			],
		]);
		
		$this->assertFalse(_elgg_services()->passwordGenerator->isValidPassword($password));
	}
	
	public function testAssertTooShortPassword() {
		$this->expectException(InvalidPasswordLengthException::class);
		_elgg_services()->passwordGenerator->assertValidPassword('a1');
	}
	
	/**
	 * @dataProvider invalidPasswordProvider
	 */
	public function testAssertInvalidPasswordRequirements($min_length, $lower, $upper, $number, $special, $password) {
		
		if (strlen($password) < $min_length) {
			$this->markTestSkipped();
		}
		
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'min_password_length' => $min_length,
				'min_password_lower' => $lower,
				'min_password_upper' => $upper,
				'min_password_number' => $number,
				'min_password_special' => $special,
			],
		]);
		
		$this->expectException(InvalidPasswordCharacterRequirementsException::class);
		_elgg_services()->passwordGenerator->assertValidPassword($password);
	}
	
	public function invalidPasswordProvider() {
		return [
			[6, null, null, null, null, '12345'],
			[6, 1, null, null, null, '123456'],
			[6, null, 1, null, null, '123456'],
			[6, null, null, 1, null, 'abcdef'],
			[6, null, null, null, 1, 'abcdef'],
			[6, 1, 1, null, null, '123!@#'],
			[6, null, 1, 1, null, 'abc!@#'],
			[6, null, null, 1, 1, 'abcDEF'],
			[6, 1, null, null, 1, '123DEF'],
		];
	}
	
	/**
	 * @dataProvider validPasswordProvider
	 */
	public function testValidPasswords($min_length, $lower, $upper, $number, $special, $password) {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'min_password_length' => $min_length,
				'min_password_lower' => $lower,
				'min_password_upper' => $upper,
				'min_password_number' => $number,
				'min_password_special' => $special,
			],
		]);
		
		$this->assertTrue(_elgg_services()->passwordGenerator->isValidPassword($password));
	}
	
	/**
	 * @dataProvider validPasswordProvider
	 */
	public function testAssertValidPasswordRequirements($min_length, $lower, $upper, $number, $special, $password) {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'min_password_length' => $min_length,
				'min_password_lower' => $lower,
				'min_password_upper' => $upper,
				'min_password_number' => $number,
				'min_password_special' => $special,
			],
		]);
		
		$this->assertEmpty(_elgg_services()->passwordGenerator->assertValidPassword($password));
	}
	
	public function validPasswordProvider() {
		return [
			[6, null, null, null, null, 'ac12CD#$'],
			[6, 1, null, null, null, 'ac12CD#$'],
			[6, null, 1, null, null, 'ac12CD#$'],
			[6, null, null, 1, null, 'ac12CD#$'],
			[6, null, null, null, 1, 'ac12CD#$'],
			[6, 1, 1, null, null, 'ac12CD#$'],
			[6, null, 1, 1, null, 'ac12CD#$'],
			[6, null, null, 1, 1, 'ac12CD#$'],
			[6, 1, null, null, 1, 'ac12CD#$'],
		];
	}
	
	/**
	 * @dataProvider getInputRegexProvider
	 */
	public function testGetInputRegex($lower, $upper, $number, $special) {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'min_password_lower' => $lower,
				'min_password_upper' => $upper,
				'min_password_number' => $number,
				'min_password_special' => $special,
			],
		]);
		
		$expected = '';
		if (isset($lower)) {
			$lower = $lower;
			if ($lower < 1) {
				$expected .= '(?!.*[a-z])';
			} else {
				$expected .= '(?=' . str_repeat('.*[a-z]', $lower) . ')';
			}
		}
		
		if (isset($upper)) {
			$upper = (int) $upper;
			if ($upper < 1) {
				$expected .= '(?!.*[A-Z])';
			} else {
				$expected .= '(?=' . str_repeat('.*[A-Z]', $upper) . ')';
			}
		}
		
		if (isset($number)) {
			$number = (int) $number;
			if ($number < 1) {
				$expected .= '(?!.*[0-9])';
			} else {
				$expected .= '(?=' . str_repeat('.*[0-9]', $number) . ')';
			}
		}
		
		if (isset($special)) {
			$generator = new RequirementPasswordGenerator();
			$special_chars = $generator->getParameter(RequirementPasswordGenerator::PARAMETER_SYMBOLS);
			$special_chars = str_replace(']', '\\]', $special_chars);
			$special_chars = str_replace('-', '\\-', $special_chars);
			
			$special = (int) $special;
			if ($special < 1) {
				$expected .= '(?!.*[' . $special_chars . '])';
			} else {
				$expected .= '(?=' . str_repeat('.*[' . $special_chars . ']', $special) . ')';
			}
		}
		
		$expected .= '.{6,}';
		
		$regex = _elgg_services()->passwordGenerator->getInputRegEx();
		
		$this->assertEquals($expected, $regex);
	}
	
	
	/**
	 * @dataProvider getInputRegexProvider
	 */
	public function testGetPasswordRequirementsDescription($lower, $upper, $number, $special) {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'min_password_lower' => $lower,
				'min_password_upper' => $upper,
				'min_password_number' => $number,
				'min_password_special' => $special,
			],
		]);
		
		$translator = _elgg_services()->translator;
		$config = _elgg_services()->config;
		
		$result = [];
		$result[] = $translator->translate('password:requirements:min_length', [$config->min_password_length]);
		
		$lower = $config->min_password_lower;
		if (isset($lower)) {
			$lower = $lower;
			if ($lower > 0) {
				$result[] = $translator->translate('password:requirements:lower', [$lower]);
			} else {
				$result[] = $translator->translate('password:requirements:no_lower');
			}
		}
		
		$upper = $config->min_password_upper;
		if (isset($upper)) {
			$upper = $upper;
			if ($upper > 0) {
				$result[] = $translator->translate('password:requirements:upper', [$upper]);
			} else {
				$result[] = $translator->translate('password:requirements:no_upper');
			}
		}
		
		$number = $config->min_password_number;
		if (isset($number)) {
			$number = $number;
			if ($number > 0) {
				$result[] = $translator->translate('password:requirements:number', [$number]);
			} else {
				$result[] = $translator->translate('password:requirements:no_number');
			}
		}
		
		$special = $config->min_password_special;
		if (isset($special)) {
			$special = $special;
			if ($special > 0) {
				$result[] = $translator->translate('password:requirements:special', [$special]);
			} else {
				$result[] = $translator->translate('password:requirements:no_special');
			}
		}
		
		$this->assertEquals(implode(' ', $result), _elgg_services()->passwordGenerator->getPasswordRequirementsDescription());
	}
	
	public function getInputRegexProvider() {
		return [
			[null, null, null, null],
			[0, 0, 0, 0],
			[2, 2, 2, 2],
		];
	}
	
	public function testInputPasswordViewVarsCallbackNoAction() {
		
		$result = _elgg_services()->hooks->trigger('view_vars', 'input/password', [], []);
		
		$this->assertEquals([], $result);
	}
	
	public function testInputPasswordViewVarsCallbackWithAction() {
		$passwordGenerator = _elgg_services()->passwordGenerator;
		
		$result = _elgg_services()->hooks->trigger('view_vars', 'input/password', [], ['add_security_requirements' => true]);
		
		$this->assertEquals([
			'add_security_requirements' => true,
			'pattern' => $passwordGenerator->getInputRegEx(),
			'title' => $passwordGenerator->getPasswordRequirementsDescription(),
		], $result);
	}
}
