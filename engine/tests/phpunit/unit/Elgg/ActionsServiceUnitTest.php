<?php

namespace Elgg;

use Elgg\Exceptions\Http\CsrfException;
use Elgg\Exceptions\Http\GatekeeperException;
use Elgg\Exceptions\Http\PageNotFoundException;
use Elgg\Exceptions\Http\ValidationException;
use Elgg\Exceptions\DomainException;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Http\Request;
use Elgg\Project\Paths;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ActionsServiceUnitTest extends \Elgg\UnitTestCase {

	use MessageTesting;
	
	/**
	 * @var string
	 */
	protected $actionsDir;

	public function up() {
		$this->actionsDir = $this->normalizeTestFilePath('actions');

		$request = $this->prepareHttpRequest();
		$this->createService($request);
	}

	public function down() {
		_elgg_services()->events->restore();
	}

	function createService(Request $request) {

		$app = self::createApplication([
			'request' => $request,
		]);

		$svc = $app->internal_services;

		$svc->session->start();

		$svc->events->backup();
		$svc->logger->disable();

		_elgg_services()->translator->addTranslation('en', ['__test__' => 'Test']);
	}

	function addCsrfTokens(Request $request) {
		$ts = _elgg_services()->csrf->getCurrentTime()->getTimestamp();
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
		_elgg_services()->actions->register('test/output', "{$this->actionsDir}/output.php", 'public');
		_elgg_services()->actions->register('test/non_ex_file', "{$this->actionsDir}/non_existing_file.php", 'public');
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
			'file' => "{$this->actionsDir}/output.php",
			'access' => 'public',
		], $actions['test/output']);

		return _elgg_services()->actions;
	}

	public function testCanUnregisterActions() {

		$this->registerActions();

		_elgg_services()->actions->unregister('test/output');
		_elgg_services()->actions->unregister('test/non_ex_file');
		_elgg_services()->actions->unregister('test/not_registered');

		$this->assertFalse(_elgg_services()->actions->exists('test/output'));
		$this->assertFalse(_elgg_services()->actions->exists('test/non_ex_file'));
		$this->assertFalse(_elgg_services()->actions->exists('test/not_registered'));
	}

	public function testCanOverrideRegisteredActions() {

		$this->assertFalse(_elgg_services()->actions->exists('test/output'));

		_elgg_services()->actions->register('test/output', "{$this->actionsDir}/output.php", 'public');

		$this->assertTrue(_elgg_services()->actions->exists('test/output'));

		_elgg_services()->actions->register('test/output', "{$this->actionsDir}/output2.php", 'public');

		$this->assertTrue(_elgg_services()->actions->exists('test/output'));
	}

	public function testActionsAccessLevels() {

		$this->assertFalse(_elgg_services()->actions->exists('test/output'));
		$this->assertFalse(_elgg_services()->actions->exists('test/not_registered'));

		_elgg_services()->actions->register('test/output', "{$this->actionsDir}/output.php", 'public');
		_elgg_services()->actions->register('test/output_logged_in', "{$this->actionsDir}/output.php", 'logged_in');
		_elgg_services()->actions->register('test/output_logged_out', "{$this->actionsDir}/output.php", 'logged_out');
		_elgg_services()->actions->register('test/output_admin', "{$this->actionsDir}/output.php", 'admin');
	}

	public function testCanNotRegisterActionWithUnknownAccessLevel() {

		$this->expectException(DomainException::class);
		$this->expectExceptionMessage('Unrecognized value \'pblc\' for $access in Elgg\\ActionsService::register');
		_elgg_services()->actions->register('test/output', "{$this->actionsDir}/output.php", 'pblc');
	}
	
	public function testCanRegisterActionWithAdditionalMiddleware() {
		$this->assertFalse(_elgg_services()->actions->exists('test/output'));
		_elgg_services()->actions->register('test/output', "{$this->actionsDir}/output.php", 'public', [
			'middleware' => \Elgg\Router\Middleware\AjaxGatekeeper::class,
		]);
		
		$this->assertTrue(_elgg_services()->actions->exists('test/output'));
		$route = _elgg_services()->routes->get('action:test/output');
		$this->assertInstanceOf(\Elgg\Router\Route::class, $route);
		
		$middleware = $route->getDefault('_middleware');
		$this->assertIsArray($middleware);
		$this->assertContains(\Elgg\Router\Middleware\AjaxGatekeeper::class, $middleware);
	}
	
	public function testCanRegisterActionWithAdditionalOptions() {
		$this->assertFalse(_elgg_services()->actions->exists('test/output'));
		_elgg_services()->actions->register('test/output', "{$this->actionsDir}/output.php", 'public', [
			'options' => [
				'entity_type' => 'foo',
				'entity_subtype' => 'bar',
			],
		]);
		
		$this->assertTrue(_elgg_services()->actions->exists('test/output'));
		$route = _elgg_services()->routes->get('action:test/output');
		$this->assertInstanceOf(\Elgg\Router\Route::class, $route);
		
		$this->assertEquals('foo', $route->getOption('entity_type'));
		$this->assertEquals('bar', $route->getOption('entity_subtype'));
	}

	public function testCanRegisterActionWithoutFilename() {
		_elgg_services()->actions->register('login');

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
		_elgg_services()->actions->register($name, "{$this->actionsDir}/output.php", 'public');
	}

	public static function invalidActionNamesDataProvider() {
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

		_elgg_services()->actions->register('output6', "{$this->actionsDir}/output6.php", 'public');

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

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

		set_input('output', 'output3');

		ob_start();
		$result = _elgg_services()->router->getResponse($request);
		ob_end_clean();

		$this->assertInstanceOf(OkResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_OK, $result->getStatusCode());
		$this->assertEquals('output3', $result->getContent());
		$this->assertEquals(REFERRER, $result->getForwardURL());
	}

	public function testCanNotExecuteWithInvalidTokens() {
		$dt = new \DateTime();

		$request = $this->prepareHttpRequest('action/output3', 'POST', [
			'__elgg_ts' => $dt->getTimestamp(),
			'__elgg_token' => 'abcdefghi123456',
		], false, false);

		$this->createService($request);

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

		_elgg_services()->csrf->setCurrentTime($dt);

		set_input('output', 'output3');

		$this->expectException(CsrfException::class);
		_elgg_services()->router->getResponse($request);
	}

	public function testCanNotExecuteUnregisteredAction() {

		$request = $this->prepareHttpRequest('action/unregistered', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		$this->expectException(PageNotFoundException::class);
		$result = _elgg_services()->router->getResponse($request);

		$this->assertInstanceOf(ErrorResponse::class, $result);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $result->getStatusCode());
		$this->assertEquals('', $result->getContent());
		$this->assertEquals(REFERRER, $result->getForwardURL());
	}

	public function testCanNotExecuteLoggedInActionIfLoggedOut() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php");

		$this->expectException(GatekeeperException::class);
		_elgg_services()->router->getResponse($request);
	}

	public function testCanNotExecuteAdminActionIfNotAdmin() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'admin');

		$this->expectException(GatekeeperException::class);
		_elgg_services()->router->getResponse($request);
	}

	public function testCanNotExecuteIfActionFileIsMissing() {
		$request = $this->prepareHttpRequest('action/no_file', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('no_file', "{$this->actionsDir}/no_file.php", 'public');

		$this->expectException(PageNotFoundException::class);
		_elgg_services()->router->getResponse($request);
	}

	public function testValidateEventIsTriggered() {
		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->events->registerHandler('action:validate', 'output3', function (\Elgg\Event $event) {
			throw new ValidationException();
		});

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

		$this->expectException(ValidationException::class);
		_elgg_services()->router->route($request);
	}

	public function testCanNotExecuteActionWithoutActionFile() {
		$request = $this->prepareHttpRequest('action/no_file', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('no_file', "{$this->actionsDir}/no_file.php", 'public');

		$this->expectException(PageNotFoundException::class);
		_elgg_services()->router->getResponse($request);
	}

	public function testCanDecodeJson() {
		$data = ['foo' => 'bar'];
		$json = json_encode($data);
		$this->assertEquals($data, _elgg_services()->ajax->decodeJson($data));
		$this->assertEquals((object) $data, _elgg_services()->ajax->decodeJson($json));
		$this->assertEquals('abc', _elgg_services()->ajax->decodeJson('abc'));
	}

	/**
	 * Non-xhr call to an action must always result in a redirect
	 * This test will implement the flow without triggering ajax forward event
	 */
	public function testCanRespondToNonAjaxRequest() {
		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

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
	 * This test will implement the flow without triggering ajax forward event
	 */
	public function testCanRespondToAjaxRequest() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 1, true);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		$this->assertStringContainsStringIgnoringCase('charset=utf-8', $response->headers->get('Content-Type'));
		$output = json_encode([
			'value' => 'output3',
			'current_url' => elgg_get_current_url(),
			'forward_url' => $request->headers->get('Referer'),
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * This test will implement the flow without triggering ajax forward event
	 */
	public function testCanRespondToAjax2Request() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		// Ajax API doesn't set charset
		//$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));

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

	public function testCanFilterAjax2Response() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->events->registerHandler(Services\AjaxResponse::RESPONSE_EVENT, 'action:output3', function (\Elgg\Event $event) {
			/* @var $api_response Services\AjaxResponse */
			$api_response = $event->getValue();
			
			$api_response->setTtl(1000);
			$api_response->setData((object) ['value' => 'output3_modified']);

			return $api_response;
		});

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));

		$this->assertNotEmpty($date = $response->headers->get('Date'));
		$this->assertNotEmpty($expires = $response->headers->get('Expires'));
		$max_age = strtotime($expires) - strtotime($date);
		$this->assertGreaterThanOrEqual(999, $max_age); // allow for time drift of 1 sec
		$this->assertLessThanOrEqual(1001, $max_age); // allow for time drift of 1 sec
		$this->assertStringContainsString("max-age={$max_age}", $response->headers->get('Cache-Control'));
		$this->assertStringContainsString('private', $response->headers->get('Cache-Control'));

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

	public function testCanCancelAjax2Response() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->events->registerHandler(Services\AjaxResponse::RESPONSE_EVENT, 'action:output3', function (\Elgg\Event $event) {
			/* @var $api_response Services\AjaxResponse */
			$api_response = $event->getValue();
			
			return $api_response->cancel();
		});

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

		set_input('output', 'output3');
		set_input('system_message', 'success');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'error' => 'The response was cancelled',
		]);

		$this->assertEquals($output, $response->getContent());
	}

	public function testThrowsExceptionForInvalidAjax2ResponseFilter() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->events->registerHandler(Services\AjaxResponse::RESPONSE_EVENT, 'action:output3', [
			Values::class,
			'getFalse'
		]);

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

		$this->expectException(\RuntimeException::class);
		$this->route($request);
	}

	/**
	 * Non-xhr call to an action must always result in a redirect
	 */
	public function testCanRespondWithErrorToNonAjaxRequest() {
		$request = $this->prepareHttpRequest('action/output3', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

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

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

		set_input('output', 'output3');
		set_input('error_message', 'error');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => 'output3',
			'current_url' => elgg_get_current_url(),
			'forward_url' => $request->headers->get('Referer'),
		]);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondWithErrorToAjax2Request() {

		$request = $this->prepareHttpRequest('action/output3', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output3', "{$this->actionsDir}/output3.php", 'public');

		set_input('output', 'output3');
		set_input('error_message', 'error');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		//$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
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

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_url', 'index');
		set_input('forward_reason', ELGG_HTTP_OK);

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('index'), $response->getTargetURL());
		
		$this->assertSystemMessageEmitted('success');
	}

	public function testCanRespondToNonAjaxRequestFromErrorResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_url', 'index');
		set_input('forward_reason', ELGG_HTTP_BAD_REQUEST);

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('index'), $response->getTargetURL());
		
		$this->assertErrorMessageEmitted('error');
	}

	public function testCanRespondToNonAjaxRequestFromRedirectResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], false);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

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

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => ['foo', 'bar'],
			'current_url' => elgg_get_current_url(),
			'forward_url' => elgg_normalize_url('index'),
		]);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToAjaxRequestFromErrorResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 1);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_reason', ELGG_HTTP_BAD_REQUEST);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		$this->assertEquals('error', $response->getContent());
	}

	public function testCanRespondToAjaxRequestFromRedirectResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 1);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

		set_input('output', ['foo', 'bar']);
		set_input('forward_reason', ELGG_HTTP_FOUND);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		
		$output = json_encode([
			'value' => '',
			'current_url' => elgg_get_current_url(),
			'forward_url' => elgg_normalize_url('index'),
		]);

		$this->assertEquals($output, $response->getContent());
		
		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRespondToAjax2RequestFromOkResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		//$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
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

	public function testCanRespondToAjax2RequestFromErrorResponseBuilderWithOkStatusCode() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		//$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
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

	public function testCanRespondToAjax2RequestFromErrorResponseBuilderWithErrorStatusCode() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

		set_input('output', ['foo', 'bar']);
		set_input('error_message', 'error');
		set_input('forward_reason', ELGG_HTTP_BAD_REQUEST);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		//$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'error' => 'error'
		]);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToAjax2RequestFromRedirectResponseBuilder() {

		$request = $this->prepareHttpRequest('action/output4', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

		set_input('forward_reason', ELGG_HTTP_FOUND);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		//$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
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

		_elgg_services()->actions->register('output5', "{$this->actionsDir}/output5.php", 'public');

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

		_elgg_services()->actions->register('output5', "{$this->actionsDir}/output5.php", 'public');

		set_input('output', 'foo');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'value' => 'foo',
			'current_url' => elgg_get_current_url(),
			'forward_url' => $request->headers->get('Referer'),
		]);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRedirectOnAjax2Request() {
		$request = $this->prepareHttpRequest('action/output5', 'POST', [], 2);
		$this->createService($request);
		$this->addCsrfTokens($request);

		_elgg_services()->actions->register('output5', "{$this->actionsDir}/output5.php", 'public');

		set_input('output', 'foo');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		//$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
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

		_elgg_services()->events->registerHandler('response', 'action:output4', function (\Elgg\Event $event) {
			$response = $event->getValue();
			
			$this->assertEquals('response', $event->getName());
			$this->assertEquals('action:output4', $event->getType());
			$this->assertInstanceOf(OkResponse::class, $response);

			$response->setContent('good bye');
			$response->setStatusCode(ELGG_HTTP_BAD_REQUEST);

			return $response;
		});

		_elgg_services()->actions->register('output4', "{$this->actionsDir}/output4.php", 'public');

		set_input('output', ['foo', 'bar']);
		set_input('system_message', 'success');
		set_input('forward_reason', ELGG_HTTP_OK);
		set_input('forward_url', 'index');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
		//$this->assertStringContainsString('charset=utf-8', strtolower($response->headers->get('Content-Type')));
		$output = json_encode([
			'error' => 'good bye',
		]);

		$this->assertEquals($output, $response->getContent());
	}
}
