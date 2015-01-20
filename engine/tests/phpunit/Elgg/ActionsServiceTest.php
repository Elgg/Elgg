<?php
namespace Elgg;

class ActionsServiceTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->actionsDir = dirname(dirname(__FILE__)) . "/test_files/actions";
	}

	/**
	 * Tests register, exists and unregisrer
	 */
	public function testCanRegisterFilesAsActions() {
		$actions = new \Elgg\ActionsService();
		
		$this->assertFalse($actions->exists('test/output'));
		$this->assertFalse($actions->exists('test/not_registered'));

		$this->assertTrue($actions->register('test/output', "$this->actionsDir/output.php", 'public'));
		$this->assertTrue($actions->register('test/non_ex_file', "$this->actionsDir/non_existing_file.php", 'public'));
		
		$this->assertTrue($actions->exists('test/output'));
		$this->assertFalse($actions->exists('test/non_ex_file'));
		$this->assertFalse($actions->exists('test/not_registered'));
		
		return $actions;
	}
	
	/**
	 * @depends testCanRegisterFilesAsActions
	 */
	public function testCanUnregisterActions($actions) {

		$this->assertTrue($actions->unregister('test/output'));
		$this->assertTrue($actions->unregister('test/non_ex_file'));
		$this->assertFalse($actions->unregister('test/not_registered'));
	
		$this->assertFalse($actions->exists('test/output'));
		$this->assertFalse($actions->exists('test/non_ex_file'));
		$this->assertFalse($actions->exists('test/not_registered'));
	}
	
	/**
	 * Tests overwriting existing action
	 */
	public function testCanOverrideRegisteredActions() {
		$actions = new \Elgg\ActionsService();
		
		$this->assertFalse($actions->exists('test/output'));
		
		$this->assertTrue($actions->register('test/output', "$this->actionsDir/output.php", 'public'));
		
		$this->assertTrue($actions->exists('test/output'));
		
		$this->assertTrue($actions->register('test/output', "$this->actionsDir/output2.php", 'public'));
		
		$this->assertTrue($actions->exists('test/output'));
	}
	
	public function testActionsAccessLevels() {
		$actions = new \Elgg\ActionsService();
		
		$this->assertFalse($actions->exists('test/output'));
		$this->assertFalse($actions->exists('test/not_registered'));

		$this->assertTrue($actions->register('test/output', "$this->actionsDir/output.php", 'public'));
		$this->assertTrue($actions->register('test/output_logged_in', "$this->actionsDir/output.php", 'logged_in'));
		$this->assertTrue($actions->register('test/output_admin', "$this->actionsDir/output.php", 'admin'));
		
		//TODO finish this test
		$this->markTestIncomplete("Can't test execution due to missing configuration.php dependencies");
// 		$actions->execute('test/not_registered');
	}

	public function testActionReturnValuesAreIgnored() {
		$this->markTestIncomplete();
	}
	
	//TODO call non existing

	
	//TODO token generation/validation
// 	public function testGenerateValidateTokens() {
// 		$actions = new \Elgg\ActionsService();
		
// 		$i = 40;
		
// 		while ($i-->0) {
// 			$timestamp = rand(100000000, 2000000000);
// 			$token = $actions->generateActionToken($timestamp);
// 			$this->assertTrue($actions->validateActionToken(false, $token, $timestamp));
// 			$this->assertFalse($actions->validateActionToken(false, $token, $timestamp+1));
// 			$this->assertFalse($actions->validateActionToken(false, $token, $timestamp-1));
// 		}
		
// 	}
	
	//TODO gatekeeper?
}

