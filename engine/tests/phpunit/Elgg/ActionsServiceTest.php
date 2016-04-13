<?php

namespace Elgg;

class ActionsServiceTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Elgg\ActionsService
	 */
	private $actions;

	public function setUp() {
		$this->actionsDir = dirname(dirname(__FILE__)) . "/test_files/actions";

		$session = \ElggSession::getMock();
		_elgg_services()->setValue('session', $session);
		_elgg_services()->session->start();

		$config = _elgg_testing_config();
		_elgg_services()->setValue('config', $config);

		$this->actions = new \Elgg\ActionsService($config, $session, _elgg_services()->crypto);
		_elgg_services()->setValue('actions', $this->actions);
	}

	function createRequest($uri = '', $method = 'POST', $parameters = []) {
		$site_url = elgg_get_site_url();
		$path = substr(elgg_normalize_url($uri), strlen($site_url));
		$path_key = \Elgg\Application::GET_PATH_KEY;
		$request = \Elgg\Http\Request::create("?$path_key=$path", $method, $parameters);

		$cookie_name = _elgg_services()->config->getCookieConfig()['session']['name'];
		$session_id = _elgg_services()->session->getId();
		$request->cookies->set($cookie_name, $session_id);
		return $request;
	}

	/**
	 * Tests register, exists and unregisrer
	 * @group ActionsService
	 */
	public function testCanRegisterFilesAsActions() {

		$this->assertFalse($this->actions->exists('test/output'));
		$this->assertFalse($this->actions->exists('test/not_registered'));

		$this->assertTrue($this->actions->register('test/output', "$this->actionsDir/output.php", 'public'));
		$this->assertTrue($this->actions->register('test/non_ex_file', "$this->actionsDir/non_existing_file.php", 'public'));

		$this->assertTrue($this->actions->exists('test/output'));
		$this->assertFalse($this->actions->exists('test/non_ex_file'));
		$this->assertFalse($this->actions->exists('test/not_registered'));

		return $this->actions;
	}

	/**
	 * @depends testCanRegisterFilesAsActions
	 * @group ActionsService
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
	 * @group ActionsService
	 */
	public function testCanOverrideRegisteredActions() {

		$this->assertFalse($this->actions->exists('test/output'));

		$this->assertTrue($this->actions->register('test/output', "$this->actionsDir/output.php", 'public'));

		$this->assertTrue($this->actions->exists('test/output'));

		$this->assertTrue($this->actions->register('test/output', "$this->actionsDir/output2.php", 'public'));

		$this->assertTrue($this->actions->exists('test/output'));
	}

	/**
	 * @group ActionsService
	 */
	public function testActionsAccessLevels() {

		$this->assertFalse($this->actions->exists('test/output'));
		$this->assertFalse($this->actions->exists('test/not_registered'));

		$this->assertTrue($this->actions->register('test/output', "$this->actionsDir/output.php", 'public'));
		$this->assertTrue($this->actions->register('test/output_logged_in', "$this->actionsDir/output.php", 'logged_in'));
		$this->assertTrue($this->actions->register('test/output_admin', "$this->actionsDir/output.php", 'admin'));

		//TODO finish this test
		$this->markTestIncomplete("Can't test execution due to missing configuration.php dependencies");
		//$this->actions->execute('test/not_registered');
	}

	/**
	 * @group ActionsService
	 */
	public function testActionReturnValuesAreIgnored() {
		$this->markTestIncomplete();
	}

	/**
	 * @group ActionsService
	 */
	public function testCanGenerateValidTokens() {

		$timeout = $this->actions->getActionTokenTimeout();
		for ($i = 1; $i <= 100; $i++) {
			$timestamp = rand(time(), time() + $timeout);
			$token = $this->actions->generateActionToken($timestamp);
			$this->assertTrue($this->actions->validateActionToken(false, $token, $timestamp), "Test failed at pass $i");
			$this->assertFalse($this->actions->validateActionToken(false, $token, $timestamp + 1), "Test failed at pass $i");
			$this->assertFalse($this->actions->validateActionToken(false, $token, $timestamp - 1), "Test failed at pass $i");
		}
	}

	/**
	 * @group ActionsService
	 */
	public function testCanNotValidateExpiredToken() {
		$timeout = $this->actions->getActionTokenTimeout();
		$timestamp = time() - $timeout - 10;
		$token = $this->actions->generateActionToken($timestamp);
		$this->assertFalse($this->actions->validateActionToken(false, $token, $timestamp));
	}

	/**
	 * @group ActionsService
	 */
	public function testCanNotValidateTokenAfterSessionExpiry() {
		$timeout = $this->actions->getActionTokenTimeout();
		$timestamp = time();
		$token = $this->actions->generateActionToken($timestamp);
		_elgg_services()->session->invalidate();
		_elgg_services()->session->start();
		$this->assertFalse($this->actions->validateActionToken(false, $token, $timestamp));
	}

	/**
	 * @group ActionsService
	 */
	public function testRefreshTokenSends400ForNonAjaxRequest() {
		$request = $this->createRequest('refresh-token', 'GET');
		_elgg_services()->setValue('request', $request);

		$response = $this->actions->handleRefreshTokenRequest($request);
		$this->assertEquals(400, $response->getStatusCode());
	}

	/**
	 * @group ActionsService
	 */
	public function testRefreshTokenSendsValidToken() {

		$request = $this->createRequest('refresh-token', 'GET');
		$request->headers->set('X-Requested-With', 'XMLHttpRequest');
		_elgg_services()->setValue('request', $request);

		$response = $this->actions->handleRefreshTokenRequest($request);
		$this->assertEquals(200, $response->getStatusCode());

		$content = $response->getContent();
		$this->assertNotEmpty($content);
		$this->assertNotEmpty($json = json_decode($content, true));

		$this->assertArrayHasKey('__elgg_ts', $json);
		$this->assertArrayHasKey('__elgg_token', $json);
		$this->assertArrayHasKey('logged_in', $json);

		$this->assertEquals($json['__elgg_token'], $this->actions->generateActionToken($json['__elgg_ts']));
		$this->assertTrue($this->actions->validateActionToken(false, $json['__elgg_token'], $json['__elgg_ts']));
	}

	//TODO gatekeeper?
}
