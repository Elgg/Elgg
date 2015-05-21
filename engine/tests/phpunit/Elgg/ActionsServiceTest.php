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
		$actions = new ActionsService();
		
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
	public function testCanUnregisterActions(ActionsService $actions) {

		$this->assertTrue($actions->unregister('test/output'));
		$this->assertTrue($actions->unregister('test/non_ex_file'));
		$this->assertFalse($actions->unregister('test/not_registered'));
	
		$this->assertFalse($actions->exists('test/output'));
		$this->assertFalse($actions->exists('test/non_ex_file'));
		$this->assertFalse($actions->exists('test/not_registered'));
	}

	public function testCanUseClassNamesAsActions() {
		$actions = new ActionsService();
		InvokableMock::reset();
		InvokableMock::$invoke_handler = function (ActionRequest $request) {
			echo $request->getName();
		};

		$this->assertTrue($actions->register('test/good', InvokableMock::class, 'public'));
		$this->assertTrue($actions->register('test/bad', 'NotReallyAClass', 'public'));

		$this->assertTrue($actions->exists('test/good'));
		$this->assertFalse($actions->exists('test/bad'));

		$actions->_bypass_gatekeeper = true;
		ob_start();
		try {
			$actions->execute('test/good');
		} catch (ForwardException $e) {}

		$output = ob_get_clean();
		$this->assertEquals('test/good', $output);

		InvokableMock::reset();
	}
	
	/**
	 * Tests overwriting existing action
	 */
	public function testCanOverrideRegisteredActions() {
		$actions = new ActionsService();
		
		$this->assertFalse($actions->exists('test/output'));
		
		$this->assertTrue($actions->register('test/output', "$this->actionsDir/output.php", 'public'));
		
		$this->assertTrue($actions->exists('test/output'));
		
		$this->assertTrue($actions->register('test/output', "$this->actionsDir/output2.php", 'public'));
		
		$this->assertTrue($actions->exists('test/output'));
	}
	
	public function testActionsAccessLevels() {
		$actions = new ActionsService();
		
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

	public function testActionErrorsOnNonExistingAction() {
		$this->markTestIncomplete();
	}

	public function testTokenGeneration() {
		$this->markTestIncomplete();
	}

	public function testTokenValidation() {
		$this->markTestIncomplete();
	}

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

	public function testActionGatekeeper() {
		$this->markTestIncomplete();
	}
}
