<?php

use Elgg\IntegrationTestCase;

/**
 * @group IntegrationTests
 * @group Users
 * @group ElggUser
 */
class ElggUserIntegrationTest extends IntegrationTestCase {
	
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
	 * @dataProvider correctAdminBannedValues
	 */
	public function testSetCorrectBannedValue($value, $boolean_value) {
		$user = $this->createUser();
		
		$user->banned = $value;
		
		$this->assertEquals($value, $user->banned);
		$this->assertEquals($boolean_value, $user->isBanned());
	}
	
	/**
	 * @dataProvider correctAdminBannedValues
	 */
	public function testSetCorrectAdminValue($value, $boolean_value) {
		$user = $this->createUser();
		
		$user->admin = $value;
		
		$this->assertEquals($value, $user->admin);
		$this->assertEquals($boolean_value, $user->isAdmin());
	}
	
	public function correctAdminBannedValues() {
		return [
			['no', false],
			['yes', true],
		];
	}
}
