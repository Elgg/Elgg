<?php

namespace Elgg;

use Elgg\Ajax\Service;
use Elgg\Http\ErrorResponse;
use Elgg\Http\Input;
use Elgg\Http\OkResponse;
use Elgg\Http\Request;
use Elgg\Http\ResponseFactory;
use Elgg\I18n\Translator;
use ElggSession;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group HttpService
 * @group ActionsService
 */
class ActionsServiceTest extends \Elgg\TestCase {

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

	public function setUp() {

		$this->actionsDir = dirname(dirname(__FILE__)) . "/test_files/actions";

		$session = ElggSession::getMock();
		_elgg_services()->setValue('session', $session);
		_elgg_services()->session->start();

		$config = $this->config();
		_elgg_services()->setValue('config', $config);

		$this->input = new Input();
		_elgg_services()->setValue('input', $this->input);

		$this->actions = new ActionsService($config, $session, _elgg_services()->crypto);
		_elgg_services()->setValue('actions', $this->actions);

		$this->request = $this->prepareHttpRequest();
		_elgg_services()->setValue('request', $this->request);

		$this->translator = new Translator();
		$this->translator->addTranslation('en', ['__test__' => 'Test']);

		$this->hooks = new PluginHooksService();
		$this->system_messages = new SystemMessagesService(elgg_get_session());

		_elgg_services()->logger->disable();
	}

	public function tearDown() {
		_elgg_services()->logger->enable();
	}
	
	function createService() {

		_elgg_services()->setValue('systemMessages', $this->system_messages); // we don't want system messages propagating across tests
		_elgg_services()->setValue('actions', $this->actions);
		_elgg_services()->setValue('hooks', $this->hooks);
		_elgg_services()->setValue('request', $this->request);
		_elgg_services()->setValue('translator', $this->translator);
		
		$this->amd_config = _elgg_services()->amdConfig;
		$this->ajax = new Service($this->hooks, $this->system_messages, $this->input, $this->amd_config);
		_elgg_services()->setValue('ajax', $this->ajax);

		$transport = new \Elgg\Http\OutputBufferTransport();
		$this->response_factory = new ResponseFactory($this->request, $this->hooks, $this->ajax, $transport);
		_elgg_services()->setValue('responseFactory', $this->response_factory);

		// register page handlers
		elgg_register_page_handler('action', '_elgg_action_handler');
	}

	function route() {
		ob_start();
		_elgg_services()->router->route($this->request);
		return ob_get_clean();
	}

	/**
	 * Tests register, exists and unregisrer
	 */
	public function testCanRegisterFilesAsActions() {

		$this->assertFalse($this->actions->exists('test/output'));
		$this->assertFalse($this->actions->exists('test/not_registered'));

		$this->assertTrue($this->actions->register('test/output', "$this->actionsDir/output.php", 'public'));
		$this->assertTrue($this->actions->register('test/non_ex_file', "$this->actionsDir/non_existing_file.php", 'public'));

		$this->assertTrue($this->actions->exists('test/output'));
		$this->assertFalse($this->actions->exists('test/non_ex_file'));
		$this->assertFalse($this->actions->exists('test/not_registered'));

		$actions = $this->actions->getAllActions();
		$this->assertArrayHasKey('test/output', $actions);
		$this->assertEquals([
			'file' => "$this->actionsDir/output.php",
			'access' => 'public',
				], $actions['test/output']);

		return $this->actions;
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

	public function testCanOverrideRegisteredActions() {

		$this->assertFalse($this->actions->exists('test/output'));

		$this->assertTrue($this->actions->register('test/output', "$this->actionsDir/output.php", 'public'));

		$this->assertTrue($this->actions->exists('test/output'));

		$this->assertTrue($this->actions->register('test/output', "$this->actionsDir/output2.php", 'public'));

		$this->assertTrue($this->actions->exists('test/output'));
	}

	public function testActionsAccessLevels() {

		$this->assertFalse($this->actions->exists('test/output'));
		$this->assertFalse($this->actions->exists('test/not_registered'));

		$this->assertTrue($this->actions->register('test/output', "$this->actionsDir/output.php", 'public'));
		$this->assertTrue($this->actions->register('test/output_logged_in', "$this->actionsDir/output.php", 'logged_in'));
		$this->assertTrue($this->actions->register('test/output_admin', "$this->actionsDir/output.php", 'admin'));
	}

	public function testCanRegisterActionWithUnknownAccessLevel() {

		// Access level will fall back to admin
		_elgg_services()->logger->disable();
		$this->assertTrue($this->actions->register('test/output', "$this->actionsDir/output.php", 'pblc'));
		$logged = _elgg_services()->logger->enable();

		$this->assertEquals([
			[
				'message' => 'Unrecognized value \'pblc\' for $access in Elgg\\ActionsService::register',
				'level' => Logger::ERROR,
			]
				], $logged);

		$actions = $this->actions->getAllActions();
		$this->assertArrayHasKey('test/output', $actions);
		$this->assertEquals([
			'file' => "$this->actionsDir/output.php",
			'access' => 'admin',
				], $actions['test/output']);
	}

	public function testCanRegisterActionWithoutFilename() {
		$this->assertTrue($this->actions->register('login'));

		$actions = $this->actions->getAllActions();
		$this->assertArrayHasKey('login', $actions);
		$this->assertEquals([
			'file' => realpath(Filesystem\Directory\Local::root()->getPath() . 'actions/login.php'),
			'access' => 'logged_in',
				], $actions['login']);
	}

	/**
	 * See #9793
	 * @dataProvider invalidActionNamesDataProvider
	 */
	public function testCanCheckActionNamesForSanity($name) {
		$this->markTestSkipped();
		$this->assertFalse($this->actions->register($name, "$this->actionsDir/output.php", 'public'));
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

		$this->request = $this->prepareHttpRequest('action/output6', 'POST', [], false, true);
		$this->assertTrue($this->actions->register('output6', "$this->actionsDir/output6.php", 'public'));

		$this->createService();

		ob_start();
		$result = $this->actions->execute('output6');
		$output = ob_get_clean();

		$this->assertInstanceOf(OkResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_OK, $result->getStatusCode());
		$this->assertEquals('', $result->getContent());
		$this->assertEquals(REFERRER, $result->getForwardURL());

		$this->assertEmpty($output);
	}

	public function testCanGenerateValidTokens() {
		$dt = new \DateTime();
		$time = $dt->getTimestamp();
		$this->actions->setCurrentTime($dt);

		$token = $this->actions->generateActionToken($time);
		$this->assertTrue($this->actions->validateActionToken(false, $token, $time));

		$this->assertFalse($this->actions->validateActionToken(false, $token, $time + 1));
		$this->assertFalse($this->actions->validateActionToken(false, $token, $time - 1));
	}

	public function testCanNotValidateExpiredToken() {
		$dt = new \DateTime();
		$this->actions->setCurrentTime($dt);
		$timeout = $this->actions->getActionTokenTimeout();
		$timestamp = $dt->getTimestamp() - $timeout - 10;
		$token = $this->actions->generateActionToken($timestamp);
		$this->assertFalse($this->actions->validateActionToken(false, $token, $timestamp));
	}

	public function testCanNotValidateTokenAfterSessionExpiry() {
		$dt = new \DateTime();
		$this->actions->setCurrentTime($dt);
		$timeout = $this->actions->getActionTokenTimeout();
		$timestamp = $dt->getTimestamp();
		$token = $this->actions->generateActionToken($timestamp);
		_elgg_services()->session->invalidate();
		_elgg_services()->session->start();
		$this->assertFalse($this->actions->validateActionToken(false, $token, $timestamp));
	}

	public function testActionGatekeeper() {

		$dt = new \DateTime();
		$this->actions->setCurrentTime($dt);

		ob_start();
		$result = $this->actions->gatekeeper('test');
		ob_end_clean();

		$this->assertFalse($result);
		$this->assertInstanceOf(RedirectResponse::class, _elgg_services()->responseFactory->getSentResponse());

		$ts = $dt->getTimestamp();
		set_input('__elgg_ts', $ts);
		set_input('__elgg_token', $this->actions->generateActionToken($ts));

		$this->assertTrue($this->actions->gatekeeper('test'));
	}

	public function testActionGatekeeperForLoginAction() {
		// test action/login token validation
		$this->markTestIncomplete();
	}

	public function testCanExecute() {
		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], false, true);
		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		$this->createService();

		set_input('output', 'output3');

		ob_start();
		$result = $this->actions->execute('output3');
		ob_end_clean();

		$this->assertInstanceOf(OkResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_OK, $result->getStatusCode());
		$this->assertEquals('output3', $result->getContent());
		$this->assertEquals(REFERRER, $result->getForwardURL());
	}

	/**
	 * The logic is a bit odd. See #9792
	 * 
	 * @dataProvider executeForwardUrlDataProvider
	 */
	public function testCanResolveForwardUrl($url, $expected) {
		$this->request = $this->prepareHttpRequest('action/fail', 'POST', [], false, true);
		$this->createService();
		$result = $this->actions->execute('fail', $url);
		$this->assertInstanceOf(ErrorResponse::class, $result);
		$this->assertEquals($expected, $result->getForwardURL());
	}

	public function executeForwardUrlDataProvider() {
		return [
			['/home', 'home'],
			['http://localhost/home/', 'home/'],
			['http://example.com/home/', 'example.com/home/'],
			['@me/home/', 'me/home/'],
		];
	}

	public function testCanNotExecuteWithInvalidTokens() {
		$dt = new \DateTime();
		$this->actions->setCurrentTime($dt);

		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [
			'__elgg_ts' => $dt->getTimestamp(),
			'__elgg_token' => 'abcdefghi123456',
				], false, false);

		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));

		$this->createService();

		set_input('output', 'output3');

		ob_start();
		$result = $this->actions->execute('output3');
		ob_end_clean();

		$this->assertEquals(null, $result);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_SEE_OTHER, $response->getStatusCode());
	}

	public function testCanNotExecuteUnregisteredAction() {

		$this->request = $this->prepareHttpRequest('action/unregistered', 'POST', [], false, true);
		$this->createService();

		$result = $this->actions->execute('unregistered', 'referrer');

		$this->assertInstanceOf(ErrorResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_NOT_IMPLEMENTED, $result->getStatusCode());
		$this->assertEquals(elgg_echo('actionundefined', ['unregistered']), $result->getContent());
		$this->assertEquals('referrer', $result->getForwardURL());
	}

	public function testCanNotExecuteLoggedInActionIfLoggedOut() {

		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], false, true);
		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php"));
		$this->createService();

		$result = $this->actions->execute('output3', 'referrer');

		$this->assertInstanceOf(ErrorResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_FORBIDDEN, $result->getStatusCode());
		$this->assertEquals(elgg_echo('actionloggedout', ['output3']), $result->getContent());
		$this->assertEquals('referrer', $result->getForwardURL());
	}

	public function testCanNotExecuteAdminActionIfNotAdmin() {

		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], false, true);
		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'admin'));
		$this->createService();

		$result = $this->actions->execute('output3', 'referrer');

		$this->assertInstanceOf(ErrorResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_FORBIDDEN, $result->getStatusCode());
		$this->assertEquals(elgg_echo('actionunauthorized'), $result->getContent());
		$this->assertEquals('referrer', $result->getForwardURL());
	}

	public function testCanNotExecuteIfActionFileIsMissing() {
		$this->request = $this->prepareHttpRequest('action/no_file', 'POST', [], false, true);
		$this->assertTrue($this->actions->register('no_file', "$this->actionsDir/no_file.php", 'public'));
		$this->createService();

		$result = $this->actions->execute('no_file', 'referrer');

		$this->assertInstanceOf(ErrorResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_NOT_IMPLEMENTED, $result->getStatusCode());
		$this->assertEquals(elgg_echo('actionnotfound', ['no_file']), $result->getContent());
		$this->assertEquals('referrer', $result->getForwardURL());
	}

	public function testActionHookIsTriggered() {

		$this->hooks->registerHandler('action', 'output3', function($hook, $type, $return, $params) {
			echo 'hello';
			return false;
		});

		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], false, true);
		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));
		$this->createService();

		$result = $this->actions->execute('output3', 'referrer');

		$this->assertInstanceOf(OkResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_OK, $result->getStatusCode());
		$this->assertEquals('referrer', $result->getForwardURL());
		$this->assertEquals('hello', $result->getContent());
	}

	public function testCanNotExecuteActionWithoutActionFile() {
		$this->request = $this->prepareHttpRequest('action/no_file', 'POST', [], false, true);
		$this->assertTrue($this->actions->register('no_file', "$this->actionsDir/no_file.php", 'public'));
		$this->createService();

		$result = $this->actions->execute('no_file', 'referrer');

		$this->assertInstanceOf(ErrorResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_NOT_IMPLEMENTED, $result->getStatusCode());
		$this->assertEquals(elgg_echo('actionnotfound', ['no_file']), $result->getContent());
		$this->assertEquals('referrer', $result->getForwardURL());
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
		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], false, true);
		$this->createService();

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals($this->request->headers->get('Referer'), $response->getTargetURL());
		// Symfony adds html content with refresh meta tag
		//$this->assertEquals('', $response->getContent());
	}

	/**
	 * This test will implement the flow without triggering ajax forward hook
	 */
	public function testCanRespondToAjaxRequest() {

		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], 1, true);
		$this->createService();

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route();

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
			'forward_url' => $this->request->headers->get('Referer'),
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * This test will implement the flow without triggering ajax forward hook
	 * @group AjaxService
	 */
	public function testCanRespondToAjax2Request() {

		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], 2, true);
		$this->createService();

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		// Ajax API doesn't set charset	
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		$output = json_encode([
			'value' => 'output3',
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

		$this->hooks->registerHandler(Services\AjaxResponse::RESPONSE_HOOK, 'action:output3', function($hook, $type, $api_response) {
			/* @var $api_response Services\AjaxResponse */
			$api_response->setTtl(1000);
			$api_response->setData((object) ['value' => 'output3_modified']);
			return $api_response;
		});

		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], 2, true);
		$this->createService();

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route();

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

		$this->hooks->registerHandler(Services\AjaxResponse::RESPONSE_HOOK, 'action:output3', function($hook, $type, $api_response) {
			/* @var $api_response Services\AjaxResponse */
			return $api_response->cancel();
		});

		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], 2, true);
		$this->createService();

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route();

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
	 */
	public function testThrowsExceptionForInvalidAjax2ResponseFilter() {

		$this->hooks->registerHandler(Services\AjaxResponse::RESPONSE_HOOK, 'action:output3', [Values::class, 'getFalse']);

		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], 2, true);
		$this->createService();

		try {
			$this->route();
			$this->fail('route did not throw RuntimeException');
		} catch (\RuntimeException $e) {
			ob_end_clean();
			return;
		}
	}

	/**
	 * Non-xhr call to an action must always result in a redirect
	 */
	public function testCanRespondWithErrorToNonAjaxRequest() {
		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], false, true);
		$this->createService();

		set_input('output', 'output3');
		set_input('error_message', 'error');

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals($this->request->headers->get('Referer'), $response->getTargetURL());
		// Symfony adds html content with refresh meta tag
		//$this->assertEquals('', $response->getContent());
	}

	public function testCanRespondWithErrorToAjaxRequest() {

		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], 1, true);
		$this->createService();

		set_input('output', 'output3');
		set_input('error_message', 'error');

		$this->route();

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
			'forward_url' => $this->request->headers->get('Referer'),
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRespondWithErrorToAjax2Request() {

		$this->assertTrue($this->actions->register('output3', "$this->actionsDir/output3.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output3', 'POST', [], 2, true);
		$this->createService();

		set_input('output', 'output3');
		set_input('error_message', 'error');

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => 'output3',
			'_elgg_msgs' => [
				'error' => ['error']
			],
			'_elgg_deps' => [],
		]);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToNonAjaxRequestFromOkResponseBuilder() {

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], false, true);
		$this->createService();

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_url', 'index');
		set_input('forward_reason', ELGG_HTTP_OK);

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('index'), $response->getTargetURL());
		$this->assertContains('success', _elgg_services()->systemMessages->dumpRegister()['success']);
	}

	public function testCanRespondToNonAjaxRequestFromErrorResponseBuilder() {

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], false, true);
		$this->createService();

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_url', 'index');
		set_input('forward_reason', ELGG_HTTP_BAD_REQUEST);

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('index'), $response->getTargetURL());
		$this->assertContains('error', _elgg_services()->systemMessages->dumpRegister()['error']);
	}

	public function testCanRespondToNonAjaxRequestFromRedirectResponseBuilder() {

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], false, true);
		$this->createService();

		set_input('output', ['foo', 'bar']);
		set_input('forward_url', 'index');
		set_input('forward_reason', ELGG_HTTP_FOUND);

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('index'), $response->getTargetURL());
	}

	public function testCanRespondToAjaxRequestFromOkResponseBuilder() {

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], 1, true);
		$this->createService();

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route();

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

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], 1, true);
		$this->createService();

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_reason', ELGG_HTTP_BAD_REQUEST);
		set_input('forward_url', 'index');

		$this->route();

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

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], 1, true);
		$this->createService();

		set_input('output', ['foo', 'bar']);
		set_input('forward_reason', ELGG_HTTP_FOUND);
		set_input('forward_url', 'index');

		$this->route();

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

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], 2, true);
		$this->createService();

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => ['foo', 'bar'],
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

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], 2, true);
		$this->createService();

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => 'error',
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

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], 2, true);
		$this->createService();

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_reason', ELGG_HTTP_BAD_REQUEST);
		set_input('forward_url', 'index');

		$this->route();

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

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], 2, true);
		$this->createService();

		set_input('forward_reason', ELGG_HTTP_FOUND);
		set_input('forward_url', 'index');

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => '',
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		]);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRedirectOnNonAjaxRequest() {
		$this->assertTrue($this->actions->register('output5', "$this->actionsDir/output5.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output5', 'POST', [], false, true);
		$this->createService();

		set_input('output', 'foo');

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
	}

	public function testCanRedirectOnAjaxRequest() {
		$this->assertTrue($this->actions->register('output5', "$this->actionsDir/output5.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output5', 'POST', [], 1, true);
		$this->createService();

		set_input('output', 'foo');

		$this->route();

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
			'forward_url' => $this->request->headers->get('Referer'),
		]);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRedirectOnAjax2Request() {
		$this->assertTrue($this->actions->register('output5', "$this->actionsDir/output5.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output5', 'POST', [], 2, true);
		$this->createService();

		set_input('output', 'foo');

		$this->route();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		//$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => 'foo',
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => []
		]);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanFilterResponseBuilder() {

		$this->hooks->registerHandler('response', 'action:output4', function($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('action:output4', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			$response->setContent('good bye');
			$response->setStatusCode(ELGG_HTTP_BAD_REQUEST);
			return $response;
		});

		$this->assertTrue($this->actions->register('output4', "$this->actionsDir/output4.php", 'public'));
		$this->request = $this->prepareHttpRequest('action/output4', 'POST', [], 2, true);
		$this->createService();

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route();

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
		elgg_register_page_handler('refresh_token', [$this->actions, 'handleTokenRefreshRequest']);

		$dt = new \DateTime();
		$this->actions->setCurrentTime($dt);

		$ts = $dt->getTimestamp();
		$token = $this->actions->generateActionToken($ts);
		$session_token = elgg_get_session()->get('__elgg_session');

		$this->request = $this->prepareHttpRequest('refresh_token', 'POST', [], 1);
		$this->createService();

		set_input('pairs', [
			// validate two
			"$ts,$token",
			"$ts,fake",
		]);
		set_input('session_token', $session_token);

		$this->route();

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
