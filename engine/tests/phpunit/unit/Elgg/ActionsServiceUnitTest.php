<?php

namespace Elgg;

use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Http\Request;
use Elgg\I18n\Translator;
use Elgg\Project\Paths;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group HttpService
 * @group ActionsService
 * @group UnitTests
 */
class ActionsServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var ActionsService
	 */
	private $actions;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var Translator
	 */
	private $translator;

	public function up() {
		$svc = _elgg_services();

		$this->actionsDir = $this->normalizeTestFilePath('actions');

		$request = $this->prepareHttpRequest();
		$this->createService($request);
	}

	public function down() {
		_elgg_services()->hooks->restore();
		_elgg_services()->logger->enable();
	}

	function createService(Request $request) {

		$app = self::createApplication([
			'request' => $request,
		]);

		$svc = $app->_services;

		$svc->session->start();

		$svc->hooks->backup();
		$svc->logger->disable();

		_elgg_services()->translator->addTranslation('en', ['__test__' => 'Test']);

		_elgg_register_routes();

	}

	function addCsrfTokens(Request $request) {
		$ts = time();
		$request->query->set('__elgg_ts', $ts);
		$request->query->set('__elgg_token', _elgg_services()->csrf->generateActionToken($ts));
	}

	/**
	 * @param Request $request
	 *
	 * @return bool
	 * @throws \Exception
	 */
	function route(Request $request) {
		$ex = false;

		ob_start();

		try {
			$ret = _elgg_services()->router->route($request);
		} catch (\Exception $ex) {

		}

		ob_end_clean();

		if ($ex) {
			throw $ex;
		}

		return $ret;
	}

	function registerActions() {
		$this->assertTrue(_elgg_services()->actions->register('test/output', "$this->actionsDir/output.php", 'public'));
		$this->assertTrue(_elgg_services()->actions->register('test/non_ex_file', "$this->actionsDir/non_existing_file.php", 'public'));
	}

	/**
	 * Tests register, exists and unregisrer
	 */
	public function testCanRegisterFilesAsActions() {

		$this->assertFalse(_elgg_services()->actions->exists('test/output'));
		$this->assertFalse(_elgg_services()->actions->exists('test/not_registered'));

		$this->registerActions();

		$this->assertTrue(_elgg_services()->actions->exists('test/output'));
		$this->assertFalse(_elgg_services()->actions->exists('test/non_ex_file'));
		$this->assertFalse(_elgg_services()->actions->exists('test/not_registered'));

		$actions = _elgg_services()->actions->getAllActions();
		$this->assertArrayHasKey('test/output', $actions);
		$this->assertEquals([
			'file' => "$this->actionsDir/output.php",
			'access' => 'public',
		], $actions['test/output']);

		return _elgg_services()->actions;
	}

	public function testCanUnregisterActions() {

		$this->registerActions();

		$this->assertTrue(_elgg_services()->actions->unregister('test/output'));
		$this->assertTrue(_elgg_services()->actions->unregister('test/non_ex_file'));
		$this->assertFalse(_elgg_services()->actions->unregister('test/not_registered'));

		$this->assertFalse(_elgg_services()->actions->exists('test/output'));
		$this->assertFalse(_elgg_services()->actions->exists('test/non_ex_file'));
		$this->assertFalse(_elgg_services()->actions->exists('test/not_registered'));
	}

	public function testCanOverrideRegisteredActions() {

		$this->assertFalse(_elgg_services()->actions->exists('test/output'));

		$this->assertTrue(_elgg_services()->actions->register('test/output', "$this->actionsDir/output.php", 'public'));

		$this->assertTrue(_elgg_services()->actions->exists('test/output'));

		$this->assertTrue(_elgg_services()->actions->register('test/output', "$this->actionsDir/output2.php", 'public'));

		$this->assertTrue(_elgg_services()->actions->exists('test/output'));
	}

	public function testActionsAccessLevels() {

		$this->assertFalse(_elgg_services()->actions->exists('test/output'));
		$this->assertFalse(_elgg_services()->actions->exists('test/not_registered'));

		$this->assertTrue(_elgg_services()->actions->register('test/output', "$this->actionsDir/output.php", 'public'));
		$this->assertTrue(_elgg_services()->actions->register('test/output_logged_in', "$this->actionsDir/output.php", 'logged_in'));
		$this->assertTrue(_elgg_services()->actions->register('test/output_admin', "$this->actionsDir/output.php", 'admin'));
	}

	public function testCanRegisterActionWithUnknownAccessLevel() {

		// Access level will fall back to admin
		_elgg_services()->logger->disable();
		$this->assertTrue(_elgg_services()->actions->register('test/output', "$this->actionsDir/output.php", 'pblc'));
		$logged = _elgg_services()->logger->enable();

		$this->assertEquals([
			[
				'message' => 'Unrecognized value \'pblc\' for $access in Elgg\\ActionsService::register',
				'level' => LogLevel::ERROR,
			]
		], $logged);

		$actions = _elgg_services()->actions->getAllActions();
		$this->assertArrayHasKey('test/output', $actions);
		$this->assertEquals([
			'file' => "$this->actionsDir/output.php",
			'access' => 'admin',
		], $actions['test/output']);
	}

	public function testCanRegisterActionWithoutFilename() {
		$this->assertTrue(_elgg_services()->actions->register('login'));

		$actions = _elgg_services()->actions->getAllActions();
		$this->assertArrayHasKey('login', $actions);
		$this->assertEquals([
			'file' => Paths::sanitize(Paths::elgg() . 'actions/login.php', false),
			'access' => 'logged_in',
		], $actions['login']);
	}

	/**
	 * See #9793
	 * @dataProvider invalidActionNamesDataProvider
	 */
	public function testCanCheckActionNamesForSanity($name) {
		$this->markTestSkipped();
		$this->assertFalse(_elgg_services()->actions->register($name, "$this->actionsDir/output.php", 'public'));
	}

	public function invalidActionNamesDataProvider() {
		return [
			['http://test/test'],
			['test//test'],
			['!test'],
			['test*'],
			['test%'],
			['test(.*).php'],
			['test/test.php'],
			['test.jpg'],
			['test[]'],
		];
	}

	public function testActionReturnValuesAreIgnoredIfNotResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output6', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output6', "$this->actionsDir/output6.php", 'public'));

		ob_start();
		$response = _elgg_services()->router->getResponse($request);
		$output = ob_get_clean();

		$this->assertInstanceOf(OkResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals('', $response->getContent());
		$this->assertEquals(REFERRER, $response->getForwardURL());

		$this->assertEmpty($output);
	}

	public function testCanGenerateValidTokens() {
		$dt = new \DateTime();
		$time = $dt->getTimestamp();
		_elgg_services()->csrf->setCurrentTime($dt);

		$token = _elgg_services()->csrf->generateActionToken($time);
		$this->assertTrue(_elgg_services()->csrf->isValidToken($token, $time));

		$this->assertFalse(_elgg_services()->csrf->isValidToken($token, $time + 1));
		$this->assertFalse(_elgg_services()->csrf->isValidToken($token, $time - 1));
	}

	public function testCanNotValidateExpiredToken() {
		$dt = new \DateTime();
		_elgg_services()->csrf->setCurrentTime($dt);
		$timeout = _elgg_services()->csrf->getActionTokenTimeout();
		$timestamp = $dt->getTimestamp() - $timeout - 10;
		$token = _elgg_services()->csrf->generateActionToken($timestamp);
		$this->assertFalse(_elgg_services()->csrf->isValidToken($token, $timestamp));
	}

	public function testCanNotValidateTokenAfterSessionExpiry() {
		$dt = new \DateTime();
		_elgg_services()->csrf->setCurrentTime($dt);
		$timeout = _elgg_services()->csrf->getActionTokenTimeout();
		$timestamp = $dt->getTimestamp();
		$token = _elgg_services()->csrf->generateActionToken($timestamp);
		_elgg_services()->session->invalidate();
		_elgg_services()->session->start();
		$this->assertFalse(_elgg_services()->csrf->isValidToken($token, $timestamp));
	}

	public function testActionGatekeeperForLoginAction() {
		// test action/login token validation
		$this->markTestIncomplete();
	}

	public function testCanExecute() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		set_input('output', 'output3');

		ob_start();
		$result = _elgg_services()->router->getResponse($request);
		ob_end_clean();

		$this->assertInstanceOf(OkResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_OK, $result->getStatusCode());
		$this->assertEquals('output3', $result->getContent());
		$this->assertEquals(REFERRER, $result->getForwardURL());
	}

	/**
	 * @expectedException \Elgg\CsrfException
	 */
	public function testCanNotExecuteWithInvalidTokens() {
		$dt = new \DateTime();

		$request = $this->prepareHttpRequest('action/output3', 'POST', [
			'__elgg_ts' => $dt->getTimestamp(),
			'__elgg_token' => 'abcdefghi123456',
		], false, false);

		$this->createService($request);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		_elgg_services()->csrf->setCurrentTime($dt);

		set_input('output', 'output3');

		_elgg_services()->router->getResponse($request);
	}

	/**
	 * @expectedException \Elgg\PageNotFoundException
	 */
	public function testCanNotExecuteUnregisteredAction() {

		$request = $this->prepareHttpRequest('action/unregistered', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$result = _elgg_services()->router->getResponse($request);

		$this->assertInstanceOf(ErrorResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $result->getStatusCode());
		$this->assertEquals('', $result->getContent());
		$this->assertEquals(REFERRER, $result->getForwardURL());
	}

	/**
	 * @expectedException \Elgg\GatekeeperException
	 */
	public function testCanNotExecuteLoggedInActionIfLoggedOut() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php"));

		_elgg_services()->router->getResponse($request);
	}

	/**
	 * @expectedException \Elgg\GatekeeperException
	 */
	public function testCanNotExecuteAdminActionIfNotAdmin() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'admin'));

		_elgg_services()->router->getResponse($request);
	}

	/**
	 * @expectedException \Elgg\PageNotFoundException
	 */
	public function testCanNotExecuteIfActionFileIsMissing() {
		$request = $this->prepareHttpRequest('action/no_file', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('no_file', "$this->actionsDir/no_file.php", 'public'));

		_elgg_services()->router->getResponse($request);
	}

	public function testActionHookIsTriggered() {
		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->hooks->registerHandler('action', 'output3', function ($hook, $type, $return, $params) {
			echo 'hello';

			return false;
		});

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		$result = _elgg_services()->router->getResponse($request);

		$this->assertInstanceOf(OkResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_OK, $result->getStatusCode());
		$this->assertEquals(REFERRER, $result->getForwardURL());
		$this->assertEquals('hello', $result->getContent());
	}

	/**
	 * @expectedException \Elgg\ValidationException
	 */
	public function testValidateHookIsTriggered() {
		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->hooks->registerHandler('action:validate', 'output3', function ($hook, $type, $return, $params) {
			throw new ValidationException('Invalid');
		});

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		_elgg_services()->router->route($request);
	}

	/**
	 * @expectedException \Elgg\PageNotFoundException
	 */
	public function testCanNotExecuteActionWithoutActionFile() {
		$request = $this->prepareHttpRequest('action/no_file', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('no_file', "$this->actionsDir/no_file.php", 'public'));

		_elgg_services()->router->getResponse($request);
	}

	/**
	 * @group AjaxService
	 */
	public function testCanDecodeJson() {
		$data = ['foo' => 'bar'];
		$json = json_encode($data);
		$this->assertEquals($data, _elgg_services()->ajax->decodeJson($data));
		$this->assertEquals((object) $data, _elgg_services()->ajax->decodeJson($json));
		$this->assertEquals('abc', _elgg_services()->ajax->decodeJson('abc'));
	}

	/**
	 * Non-xhr call to an action must always result in a redirect
	 * This test will implement the flow without triggering ajax forward hook
	 */
	public function testCanRespondToNonAjaxRequest() {
		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals($request->headers->get('Referer'), $response->getTargetURL());
		// Symfony adds html content with refresh meta tag
		//$this->assertEquals('', $response->getContent());
	}

	/**
	 * This test will implement the flow without triggering ajax forward hook
	 */
	public function testCanRespondToAjaxRequest() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 1, true);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'output' => 'output3',
			'status' => 0,
			'system_messages' => [
				'error' => [],
				'success' => [
					'success',
				]
			],
			'current_url' => current_page_url(),
			'forward_url' => $request->headers->get('Referer'),
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * This test will implement the flow without triggering ajax forward hook
	 * @group AjaxService
	 */
	public function testCanRespondToAjax2Request() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		// Ajax API doesn't set charset
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		$output = json_encode([
			'value' => 'output3',
			'current_url' => elgg_normalize_url('action/output3'),
			'forward_url' => elgg_normalize_url('phpunit'),
			'_elgg_msgs' => [
				'success' => [
					'success',
				],
			],
			'_elgg_deps' => []
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanFilterAjax2Response() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->hooks->registerHandler(Services\AjaxResponse::RESPONSE_HOOK, 'action:output3', function ($hook, $type, $api_response) {
			/* @var $api_response Services\AjaxResponse */
			$api_response->setTtl(1000);
			$api_response->setData((object) ['value' => 'output3_modified']);

			return $api_response;
		});

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$this->assertNotEmpty($date = $response->headers->get('Date'));
		$this->assertNotEmpty($expires = $response->headers->get('Expires'));
		$this->assertEquals(strtotime($date) + 1000, strtotime($expires));
		$this->assertContains('max-age=1000', $response->headers->get('Cache-Control'));
		$this->assertContains('private', $response->headers->get('Cache-Control'));

		$output = json_encode([
			'value' => 'output3_modified',
			'_elgg_msgs' => [
				'success' => [
					'success',
				],
			],
			'_elgg_deps' => []
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanCancelAjax2Response() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->hooks->registerHandler(Services\AjaxResponse::RESPONSE_HOOK, 'action:output3', function ($hook, $type, $api_response) {
			/* @var $api_response Services\AjaxResponse */
			return $api_response->cancel();
		});

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'error' => 'The response was cancelled',
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 * @expectedException \RuntimeException
	 */
	public function testThrowsExceptionForInvalidAjax2ResponseFilter() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->hooks->registerHandler(Services\AjaxResponse::RESPONSE_HOOK, 'action:output3', [
			Values::class,
			'getFalse'
		]);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		$this->route($request);
	}

	/**
	 * Non-xhr call to an action must always result in a redirect
	 */
	public function testCanRespondWithErrorToNonAjaxRequest() {
		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		set_input('output', 'output3');
		set_input('error_message', 'error');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals($request->headers->get('Referer'), $response->getTargetURL());
		// Symfony adds html content with refresh meta tag
		//$this->assertEquals('', $response->getContent());
	}

	public function testCanRespondWithErrorToAjaxRequest() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 1);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		set_input('output', 'output3');
		set_input('error_message', 'error');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'output' => 'output3',
			'status' => -1,
			'system_messages' => [
				'error' => ['error'],
				'success' => []
			],
			'current_url' => current_page_url(),
			'forward_url' => $request->headers->get('Referer'),
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRespondWithErrorToAjax2Request() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		set_input('output', 'output3');
		set_input('error_message', 'error');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => 'output3',
			'current_url' => elgg_normalize_url('action/output3'),
			'forward_url' => elgg_normalize_url('phpunit'),
			'_elgg_msgs' => [
				'error' => ['error']
			],
			'_elgg_deps' => [],
		]);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToNonAjaxRequestFromOkResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_url', 'index');
		set_input('forward_reason', ELGG_HTTP_OK);

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('index'), $response->getTargetURL());
		$this->assertContains('success', _elgg_services()->systemMessages->dumpRegister()['success']);
	}

	public function testCanRespondToNonAjaxRequestFromErrorResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_url', 'index');
		set_input('forward_reason', ELGG_HTTP_BAD_REQUEST);

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('index'), $response->getTargetURL());
		$this->assertContains('error', _elgg_services()->systemMessages->dumpRegister()['error']);
	}

	public function testCanRespondToNonAjaxRequestFromRedirectResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('output', ['foo', 'bar']);
		set_input('forward_url', 'index');
		set_input('forward_reason', ELGG_HTTP_FOUND);

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('index'), $response->getTargetURL());
	}

	public function testCanRespondToAjaxRequestFromOkResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 1);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'output' => ['foo', 'bar'],
			'status' => 0,
			'system_messages' => [
				'error' => [],
				'success' => ['success']
			],
			'current_url' => current_page_url(),
			'forward_url' => elgg_normalize_url('index'),
		]);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToAjaxRequestFromErrorResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 1);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_reason', ELGG_HTTP_BAD_REQUEST);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'output' => 'error', // registered error message
			'status' => -1,
			'system_messages' => [
				'error' => ['error'],
				'success' => []
			],
			'current_url' => current_page_url(),
			'forward_url' => elgg_normalize_url('index'),
		]);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToAjaxRequestFromRedirectResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 1);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('output', ['foo', 'bar']);
		set_input('forward_reason', ELGG_HTTP_FOUND);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'output' => '',
			'status' => 0,
			'system_messages' => [
				'error' => [],
				'success' => []
			],
			'current_url' => current_page_url(),
			'forward_url' => elgg_normalize_url('index'),
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRespondToAjax2RequestFromOkResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => ['foo', 'bar'],
			'current_url' => elgg_normalize_url('action/output4'),
			'forward_url' => elgg_normalize_url('index'),
			'_elgg_msgs' => [
				'success' => ['success']
			],
			'_elgg_deps' => []
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRespondToAjax2RequestFromErrorResponseBuilderWithOkStatusCode() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => 'error',
			'current_url' => elgg_normalize_url('action/output4'),
			'forward_url' => elgg_normalize_url('index'),
			'_elgg_msgs' => [
				'error' => ['error'],
			],
			'_elgg_deps' => [],
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRespondToAjax2RequestFromErrorResponseBuilderWithErrorStatusCode() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_reason', ELGG_HTTP_BAD_REQUEST);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'error' => 'error'
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRespondToAjax2RequestFromRedirectResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('forward_reason', ELGG_HTTP_FOUND);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => '',
			'current_url' => elgg_normalize_url('action/output4'),
			'forward_url' => elgg_normalize_url('index'),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		]);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRedirectOnNonAjaxRequest() {
		$request = $this->prepareHttpRequest('action/output5', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output5', "$this->actionsDir/output5.php", 'public'));

		set_input('output', 'foo');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
	}

	public function testCanRedirectOnAjaxRequest() {
		$request = $this->prepareHttpRequest('action/output5', 'POST', [], 1);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output5', "$this->actionsDir/output5.php", 'public'));

		set_input('output', 'foo');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'output' => 'foo',
			'status' => 0,
			'system_messages' => [
				'error' => [],
				'success' => []
			],
			'current_url' => current_page_url(),
			'forward_url' => $request->headers->get('Referer'),
		]);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRedirectOnAjax2Request() {
		$request = $this->prepareHttpRequest('action/output5', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->assertTrue(_elgg_services()->actions->register('output5', "$this->actionsDir/output5.php", 'public'));

		set_input('output', 'foo');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => 'foo',
			'current_url' => elgg_normalize_url('action/output5'),
			'forward_url' => elgg_normalize_url('phpunit'),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => []
		]);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanFilterResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->hooks->registerHandler('response', 'action:output4', function ($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('action:output4', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			$response->setContent('good bye');
			$response->setStatusCode(ELGG_HTTP_BAD_REQUEST);

			return $response;
		});

		$this->assertTrue(_elgg_services()->actions->register('output4', "$this->actionsDir/output4.php", 'public'));

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'error' => 'good bye',
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRefreshTokens() {
		$request = $this->prepareHttpRequest('refresh_token', 'POST', [], 1);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$dt = new \DateTime();
		_elgg_services()->csrf->setCurrentTime($dt);

		$ts = $dt->getTimestamp();
		$token = _elgg_services()->csrf->generateActionToken($ts);
		$session_token = elgg_get_session()->get('__elgg_session');

		set_input('pairs', [
			// validate two
			"$ts,$token",
			"$ts,fake",
		]);
		set_input('session_token', $session_token);

		_elgg_services()->logger->disable();
		$this->assertTrue($this->route($request));
		_elgg_services()->logger->enable();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$expected = json_encode([
			'token' => [
				'__elgg_ts' => $ts,
				'__elgg_token' => $token,
				'logged_in' => false,
			],
			'valid_tokens' => [
				// only valid one
				$token => true,
			],
			'session_token' => $session_token,
			'user_guid' => 0,
		]);

		$this->assertEquals($expected, $response->getContent());
	}

}
