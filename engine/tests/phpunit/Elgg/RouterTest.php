<?php

namespace Elgg;

use Elgg\Ajax\Service;
use Elgg\Http\Input;
use Elgg\Http\OkResponse;
use Elgg\Http\Request;
use Elgg\Http\ResponseFactory;
use Elgg\I18n\Translator;
use ElggSession;
use PHPUnit_Framework_TestCase;
use stdClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group HttpService
 * @group RouterService
 */
class RouterTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var Router
	 */
	protected $router;

	/**
	 * @var string
	 */
	protected $pages;

	/**
	 * @var string
	 */
	protected $viewsDir;

	/**
	 * @var int
	 */
	protected $fooHandlerCalls = 0;

	function setUp() {
		$this->pages = dirname(dirname(__FILE__)) . '/test_files/pages';
		$this->fooHandlerCalls = 0;

		$session = ElggSession::getMock();
		_elgg_services()->setValue('session', $session);
		_elgg_services()->session->start();

		$config = _elgg_testing_config();
		_elgg_services()->setValue('config', $config);

		$input = new Input();
		_elgg_services()->setValue('input', $input);

		$this->request = _elgg_testing_request('', 'GET');
		_elgg_services()->setValue('request', $this->request);

		$this->translator = new Translator();
		$this->translator->addTranslation('en', ['__test__' => 'Test']);

		$this->hooks = new PluginHooksService();
		$this->router = new Router($this->hooks);

		$this->system_messages = new SystemMessagesService(elgg_get_session());

		$this->viewsDir = dirname(dirname(__FILE__)) . "/test_files/views";

		$this->createService();
	}

	function createService() {

		_elgg_services()->setValue('systemMessages', $this->system_messages); // we don't want system messages propagating across tests
		_elgg_services()->setValue('hooks', $this->hooks);
		_elgg_services()->setValue('request', $this->request);
		_elgg_services()->setValue('translator', $this->translator);
		_elgg_services()->setValue('router', new Router(_elgg_services()->hooks));
		_elgg_services()->setValue('ajax', new Service(_elgg_services()->hooks, _elgg_services()->systemMessages, _elgg_services()->input, _elgg_services()->amdConfig));
		_elgg_services()->setValue('responseFactory', new ResponseFactory(_elgg_services()->request, _elgg_services()->hooks, _elgg_services()->ajax));

		elgg_register_page_handler('ajax', '_elgg_ajax_page_handler');

		_elgg_services()->views->autoregisterViews('', "$this->viewsDir/default", 'default');
		_elgg_services()->views->autoregisterViews('', "$this->viewsDir/json", 'json');
	}

	function hello_page_handler($segments, $identifier) {
		include "{$this->pages}/hello.php";

		return true;
	}

	function testCanRegisterFunctionsAsPageHandlers() {
		$registered = $this->router->registerPageHandler('hello', array($this, 'hello_page_handler'));

		$this->assertTrue($registered);

		$path = "hello/1/\xE2\x82\xAC"; // euro sign

		$handled = $this->router->route(_elgg_testing_request($path));
		$this->assertTrue($handled);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);

		$this->assertEquals($path, $response->getContent());

		$this->assertEquals(array(
			'hello' => array($this, 'hello_page_handler')
				), $this->router->getPageHandlers());
	}

	function testFailToRegisterInvalidCallback() {
		$registered = $this->router->registerPageHandler('hello', new stdClass());

		$this->assertFalse($registered);
	}

	function testCanUnregisterPageHandlers() {
		$this->router->registerPageHandler('hello', array($this, 'hello_page_handler'));
		$this->router->unregisterPageHandler('hello');

		ob_start();
		$handled = $this->router->route(_elgg_testing_request('hello'));
		$output = ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertFalse($response);

		// Normally we would expect the router to return false for this request,
		// but since it checks for headers_sent() and PHPUnit issues output before
		// this test runs, the headers have already been sent. It's enough to verify
		// that the output we buffered is empty.
		// $this->assertFalse($handled);
		$this->assertEmpty($output);
	}

	/**
	 * 1. Register a page handler for `/foo`
	 * 2. Register a plugin hook that uses the "handler" result param
	 *    to route all `/bar/*` requests to the `/foo` handler.
	 * 3. Route a request for a `/bar` page.
	 * 4. Check that the `/foo` handler was called.
	 */
	function testRouteSupportsSettingHandlerInHookResultForBackwardsCompatibility() {
		$this->router->registerPageHandler('foo', array($this, 'foo_page_handler'));
		$this->hooks->registerHandler('route', 'bar', array($this, 'bar_route_handler'));

		ob_start();
		$this->router->route(_elgg_testing_request('bar/baz'));
		ob_end_clean();

		$this->assertEquals(1, $this->fooHandlerCalls);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
	}

	/**
	 * 1. Register a page handler for `/foo`
	 * 2. Register a plugin hook that uses the "handler" result param
	 *    to route all `/bar/*` requests to the `/foo` handler.
	 * 3. Route a request for a `/bar` page.
	 * 4. Check that the `/foo` handler was called.
	 */
	function testRouteSupportsSettingIdentifierInHookResultForBackwardsCompatibility() {
		$this->router->registerPageHandler('foo', array($this, 'foo_page_handler'));
		$this->hooks->registerHandler('route', 'bar', array($this, 'bar_route_identifier'));

		ob_start();
		$this->router->route(_elgg_testing_request('bar/baz'));
		ob_end_clean();

		$this->assertEquals(1, $this->fooHandlerCalls);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
	}

	function testRouteOverridenFromHook() {
		$this->router->registerPageHandler('foo', array($this, 'foo_page_handler'));
		$this->hooks->registerHandler('route', 'foo', array($this, 'bar_route_override'));

		ob_start();
		$this->router->route(_elgg_testing_request('foo'));
		$result = ob_get_contents();
		ob_end_clean();

		$this->assertEquals("Page handler override from hook", $result);
		$this->assertEquals(0, $this->fooHandlerCalls);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);

		$this->assertEquals("Page handler override from hook", $response->getContent());
	}

	function foo_page_handler() {
		$this->fooHandlerCalls++;
		return true;
	}

	function bar_route_handler($hook, $type, $value, $params) {
		$value['handler'] = 'foo';
		return $value;
	}

	function bar_route_identifier($hook, $type, $value, $params) {
		$value['identifier'] = 'foo';
		return $value;
	}

	function bar_route_override($hook, $type, $value, $params) {
		echo "Page handler override from hook";
		return false;
	}

	public function testCanAllowRewrite() {

		$this->hooks->registerHandler('route:rewrite', 'foo', function($hook, $type, $return, $params) {
			$this->assertEquals('route:rewrite', $hook);
			$this->assertEquals('foo', $type);
			$this->assertEquals($return, $params);

			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);

			return [
				'identifier' => 'foo2',
				'segments' => [
					'bar2',
					'baz2',
				]
			];
		});

		$this->request = _elgg_testing_request('foo/bar/baz');

		$this->createService();

		$return = _elgg_services()->router->allowRewrite($this->request);

		$this->assertInstanceOf(Request::class, $return);

		$segments = $return->getUrlSegments();
		$identifier = array_shift($segments);
		$this->assertEquals('foo2', $identifier);
		$this->assertEquals(['bar2', 'baz2'], $segments);
	}

	public function testCanRespondToNonAjaxRequestThroughRouteHook() {

		$this->hooks->registerHandler('route', 'foo', function($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			echo 'good bye';
			return false;
		});

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET');
		$this->createService();

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals('good bye', $response->getContent());
	}

	public function testCanRedirectNonAjaxRequestFromRouteHook() {

		$this->hooks->registerHandler('route', 'foo', function($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			_elgg_services()->responseFactory->redirect('foo2/bar2/baz2');
			return false;
		});

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET');
		$this->createService();

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('foo2/bar2/baz2'), $response->getTargetUrl());
	}

	public function testCanFilterResponse() {

		$this->hooks->registerHandler('response', 'path:foo/bar', function($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('path:foo/bar', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);
			return elgg_error_response('', REFERRER, ELGG_HTTP_BAD_REQUEST);
		});

		$this->request = _elgg_testing_request('foo/bar', 'GET');
		$this->createService();

		elgg_register_page_handler('foo', function() {
			echo 'hello';
			return true;
		});
		
		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertEquals(elgg_view_resource('error', ['type' => (string) ELGG_HTTP_BAD_REQUEST]), $response->getContent());
	}

	public function testCanRespondToNonAjaxRequestFromOkResponseBuilder() {

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET');
		$this->createService();

		elgg_register_page_handler('foo', function($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);
			return elgg_ok_response('hello');
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals('hello', $response->getContent());
	}

	public function testCanRespondToNonAjaxRequestFromErrorResponseBuilder() {

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET');
		$this->createService();

		elgg_register_page_handler('foo', function($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);
			return elgg_error_response('', 'phpunit', ELGG_HTTP_NOT_FOUND);
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $response->getStatusCode());
		$error_page = elgg_view_resource('error', [
			'type' => (string) ELGG_HTTP_NOT_FOUND,
		]);
		$this->assertEquals($error_page, $response->getContent());
	}

	public function testCanRespondToNonAjaxRequestFromRedirectResponseBuilder() {

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET');
		$this->createService();

		elgg_register_page_handler('foo', function($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);
			return elgg_redirect_response('foo2/bar2/baz2');
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('foo2/bar2/baz2'), $response->getTargetUrl());
	}

	public function testCanRespondToNonAjaxRequestInNonDefaultViewtype() {

		$this->request = _elgg_testing_request('phpunit', 'GET', ['view' => 'json']);
		$this->createService();

		elgg_register_page_handler('phpunit', function() {
			$output = elgg_view('response');
			return elgg_ok_response($output);
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(elgg_view('response', [], false, false, 'json'), $response->getContent());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	public function testCanRespondToNonAjaxRequestForPageThatForwards() {

		$this->request = _elgg_testing_request('phpunit', 'GET');
		$this->createService();

		elgg_register_page_handler('phpunit', function() {
			_elgg_services()->responseFactory->redirect('index');
			return elgg_ok_response('foo');
		});

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
	}

	public function testCanRespondToNonAjaxRequestForPageThatForwardsToErrorPage() {
		$this->request = _elgg_testing_request('phpunit', 'GET');
		$this->createService();

		elgg_register_page_handler('phpunit', function() {
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_NOT_FOUND);
			return elgg_ok_response('foo');
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $response->getStatusCode());
		$error_page = elgg_view_resource('error', [
			'type' => (string) ELGG_HTTP_NOT_FOUND,
		]);
		$this->assertEquals($error_page, $response->getContent());
	}

	public function testCanSafelyRedirectWithinRedirect() {

		$this->hooks->registerHandler('forward', (string) ELGG_HTTP_NOT_FOUND, function() {
			$this->fooHandlerCalls++;
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_BAD_REQUEST);
		});

		$this->hooks->registerHandler('forward', (string) ELGG_HTTP_BAD_REQUEST, function() {
			$this->fooHandlerCalls++;
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_INTERNAL_SERVER_ERROR);
		});

		$this->request = _elgg_testing_request('phpunit', 'GET');
		$this->createService();

		elgg_register_page_handler('phpunit', function() {
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_NOT_FOUND);
			return elgg_ok_response('foo');
		});


		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
		$error_page = elgg_view_resource('error', [
			'type' => (string) ELGG_HTTP_INTERNAL_SERVER_ERROR,
		]);

		$this->assertEquals($error_page, $response->getContent());

		$this->assertTrue($this->fooHandlerCalls > 0);
	}

	public function testCanRespondToAjaxRequestThroughRouteHook() {

		$this->hooks->registerHandler('route', 'foo', function($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			echo 'good bye';
			return false;
		});

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET', [], 1);
		$this->createService();

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		// no forward call, so API outputs content
		$this->assertEquals('good bye', $response->getContent());
	}

	public function testCanRedirectAjaxRequestFromRouteHook() {

		$this->hooks->registerHandler('route', 'foo', function($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			_elgg_services()->responseFactory->redirect('foo2/bar2/baz2');
			return false;
		});

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET', [], 1);
		$this->createService();

		$this->assertTrue(_elgg_services()->router->route($this->request));

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
			'forward_url' => elgg_normalize_url('foo2/bar2/baz2'),
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRespondToAjaxRequestFromOkResponseBuilder() {

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET', [], 1);
		$this->createService();

		elgg_register_page_handler('foo', function($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);
			return elgg_ok_response('hello');
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		// no forward call, so API outputs content
		$this->assertEquals('hello', $response->getContent());
	}

	public function testCanRespondToAjaxRequestFromErrorResponseBuilder() {

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET', [], 1);
		$this->createService();

		elgg_register_page_handler('foo', function($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);
			return elgg_error_response('', 'phpunit', ELGG_HTTP_NOT_FOUND);
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $response->getStatusCode());

		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		// no forward, so API outputs html
		$this->assertEquals('', $response->getContent());
	}

	public function testCanRespondToAjaxRequestFromRedirectResponseBuilder() {

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET', [], 1);
		$this->createService();

		elgg_register_page_handler('foo', function($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);
			return elgg_redirect_response('foo2/bar2/baz2');
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

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
			'forward_url' => elgg_normalize_url('foo2/bar2/baz2'),
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRespondToAjaxRequestInNonDefaultViewtype() {

		$this->request = _elgg_testing_request('phpunit', 'GET', ['view' => 'json'], 1);
		$this->createService();

		elgg_register_page_handler('phpunit', function() {
			$output = elgg_view('response');
			return elgg_ok_response($output);
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		// no forward, so API outputs view contents
		$this->assertEquals(elgg_view('response', [], false, false, 'json'), $response->getContent());
	}

	public function testCanRespondToAjaxRequestForPageThatForwards() {

		$this->request = _elgg_testing_request('phpunit', 'GET', [], 1);
		$this->createService();

		elgg_register_page_handler('phpunit', function() {
			_elgg_services()->responseFactory->redirect('index');
			return elgg_ok_response('foo');
		});

		_elgg_services()->router->route($this->request);

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
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRespondToAjaxRequestForPageThatForwardsToErrorPage() {
		$this->request = _elgg_testing_request('phpunit', 'GET', [], 1);
		$this->createService();

		elgg_register_page_handler('phpunit', function() {
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_NOT_FOUND);
			return elgg_ok_response('foo');
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

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
			'forward_url' => elgg_normalize_url('error'),
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRespondToAjax2RequestThroughRouteHook() {

		$this->hooks->registerHandler('route', 'foo', function($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			echo 'good bye';
			return false;
		});

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET', [], 2);
		$this->createService();

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => 'good bye',
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRedirectAjax2RequestFromRouteHook() {

		$this->hooks->registerHandler('route', 'foo', function($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			_elgg_services()->responseFactory->redirect('foo2/bar2/baz2');
			return false;
		});

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET', [], 2);
		$this->createService();

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => '',
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRespondToAjax2RequestFromOkResponseBuilder() {

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET', [], 2);
		$this->createService();

		elgg_register_page_handler('foo', function($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);
			return elgg_ok_response('hello');
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => 'hello',
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToAjax2RequestFromErrorResponseBuilder() {

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET', [], 2);
		$this->createService();

		elgg_register_page_handler('foo', function($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);
			return elgg_error_response('', 'phpunit', ELGG_HTTP_NOT_FOUND);
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'error' => '',
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToAjax2RequestFromRedirectResponseBuilder() {

		$this->request = _elgg_testing_request('foo/bar/baz', 'GET', [], 2);
		$this->createService();

		elgg_register_page_handler('foo', function($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);
			return elgg_redirect_response('foo2/bar2/baz2');
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => '',
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRespondToAjax2RequestInNonDefaultViewtype() {

		$this->request = _elgg_testing_request('phpunit', 'GET', ['view' => 'json'], 2);
		$this->createService();

		elgg_register_page_handler('phpunit', function() {
			$output = elgg_view('response');
			return elgg_ok_response($output);
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => [
				'foo' => 'bar',
			],
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToAjax2RequestForPageThatForwards() {

		$this->request = _elgg_testing_request('phpunit', 'GET', [], 2);
		$this->createService();

		elgg_register_page_handler('phpunit', function() {
			_elgg_services()->responseFactory->redirect('index');
			return elgg_ok_response('foo');
		});

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$output = $this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => '',
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRespondToAjax2RequestForPageThatForwardsToErrorPage() {
		$this->request = _elgg_testing_request('phpunit', 'GET', [], 2);
		$this->createService();

		elgg_register_page_handler('phpunit', function() {
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_NOT_FOUND);
			return elgg_ok_response('foo');
		});

		$this->assertTrue(_elgg_services()->router->route($this->request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'error' => ''
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	/**
	 * See #9796
	 */
	public function testCanRespondToUnregisteredRoute() {

		$this->request = _elgg_testing_request('unknown', 'GET');
		$this->createService();

		// Normally we would assert that this is false, but since PHPUnit is sending it's own headers
		// we will just make sure the response is not sent
		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertFalse($response);

		$this->markTestIncomplete();
	}

	public function testRespondsWithErrorToNonAjaxRequestForAjaxView() {

		/**
		 * @todo: revisit once gatekeepers have been refactored in a service
		 */
		$this->markTestSkipped();

		$this->request = _elgg_testing_request('ajax/view/unallowed', 'GET');
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals($this->request->headers->get('Referer'), $response->getTargetUrl());
	}

	public function testCanRespondWithErrorToAjaxViewRequestForUnallowedView() {

		$this->request = _elgg_testing_request('ajax/view/unallowed', 'GET', [], 1);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_FORBIDDEN, $response->getStatusCode());
		$this->assertEquals("Ajax view 'unallowed' was not registered", $response->getContent());
	}

	public function testCanRespondToAjaxViewRequestForCacheableView() {

		$this->assertTrue(_elgg_services()->views->isCacheableView('cacheable.xml'));

		$this->request = _elgg_testing_request('ajax/view/cacheable.xml', 'GET', [], 1);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(file_get_contents($this->viewsDir . '/default/cacheable.xml'), $response->getContent());
		$this->assertContains('application/xml', $response->headers->get('Content-Type'));
	}

	public function testCanRespondToAjaxViewRequestForCSS() {

		$this->request = _elgg_testing_request('ajax/view/css/styles.css', 'GET', [], 1);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(file_get_contents($this->viewsDir . '/default/styles.css'), $response->getContent());
		$this->assertContains('text/css', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	public function testCanRespondToAjaxViewRequestForJS() {

		$this->request = _elgg_testing_request('ajax/view/js/javascript.js', 'GET', [], 1);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(file_get_contents($this->viewsDir . '/default/javascript.js'), $response->getContent());
		$this->assertContains('text/javascript', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	public function testCanRespondToAjaxViewRequestForARegisteredView() {

		$vars = [
			'query_value' => 'hello',
		];

		elgg_register_ajax_view('query_view');

		$this->request = _elgg_testing_request('ajax/view/query_view', 'GET', $vars, 1);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals('hello', $response->getContent());
		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	public function testCanFilterResponseToAjaxViewRequestForARegisteredView() {

		$this->hooks->registerHandler('response', 'view:query_view', function($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('view:query_view', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			return elgg_error_response('good bye', REFERRER, ELGG_HTTP_BAD_REQUEST);
		});

		$vars = [
			'query_value' => 'hello',
		];

		elgg_register_ajax_view('query_view');

		$this->request = _elgg_testing_request('ajax/view/query_view', 'GET', $vars, 1);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertEquals('good bye', $response->getContent());
		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	public function testCanRespondToAjaxViewRequestForARegisteredViewWhichForwards() {

		$vars = [
			'output' => 'hello',
			'forward_url' => 'forwards',
			'forward_reason' => ELGG_HTTP_BAD_REQUEST,
			'error' => 'error',
		];

		elgg_register_ajax_view('forwards');

		$this->request = _elgg_testing_request('ajax/view/forwards', 'GET', $vars, 1);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		$output = json_encode([
			'output' => 'hello',
			'status' => -1,
			'system_messages' => [
				'error' => ['error'],
				'success' => []
			],
			'current_url' => current_page_url(),
			'forward_url' => elgg_normalize_url('forwards'),
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRespondWithErrorToAjax2ViewRequestForUnallowedView() {

		$this->request = _elgg_testing_request('ajax/view/unallowed', 'GET', [], 2);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_FORBIDDEN, $response->getStatusCode());

		$output = json_encode([
			'error' => "Ajax view 'unallowed' was not registered",
				], ELGG_JSON_ENCODING); // Symfony JsonResponse

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 * @see #9797
	 */
	public function testCanRespondToAjax2ViewRequestForCacheableView() {

		$this->assertTrue(_elgg_services()->views->isCacheableView('cacheable.xml'));

		$this->request = _elgg_testing_request('ajax/view/cacheable.xml', 'GET', [], 2);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => file_get_contents($this->viewsDir . '/default/cacheable.xml'),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 * @see #9797
	 */
	public function testCanRespondToAjax2ViewRequestForCSS() {

		$this->request = _elgg_testing_request('ajax/view/css/styles.css', 'GET', [], 2);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => file_get_contents($this->viewsDir . '/default/styles.css'),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 * @see #9797
	 */
	public function testCanRespondToAjax2ViewRequestForJS() {

		$this->request = _elgg_testing_request('ajax/view/js/javascript.js', 'GET', [], 2);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => file_get_contents($this->viewsDir . '/default/javascript.js'),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRespondToAjax2ViewRequestForARegisteredView() {

		$vars = [
			'query_value' => 'hello',
		];

		elgg_register_ajax_view('query_view');

		$this->request = _elgg_testing_request('ajax/view/query_view', 'GET', $vars, 2);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => 'hello',
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanFilterResponseToAjax2ViewRequestForARegisteredView() {

		$this->hooks->registerHandler('response', 'view:query_view', function($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('view:query_view', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			return elgg_error_response('good bye', REFERRER, ELGG_HTTP_BAD_REQUEST);
		});

		$vars = [
			'query_value' => 'hello',
		];

		elgg_register_ajax_view('query_view');

		$this->request = _elgg_testing_request('ajax/view/query_view', 'GET', $vars, 2);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'error' => 'good bye',
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRespondToAjax2ViewRequestForARegisteredViewWhichForwards() {

		$vars = [
			'output' => 'hello',
			'forward_url' => 'forwards',
			'forward_reason' => ELGG_HTTP_BAD_REQUEST,
			'error' => 'error',
		];

		elgg_register_ajax_view('forwards');

		$this->request = _elgg_testing_request('ajax/view/forwards', 'GET', $vars, 2);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		/**
		 * @todo: decide what the output should be
		 * Do we use the buffer output when responding with error to Ajax2?
		 * See #9798
		 */
		$output = json_encode([
			'error' => 'hello',
				], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		$this->markTestIncomplete();
	}

	public function testCanRespondToAjaxViewRequestForARegisteredFormView() {

		$vars = [
			'query_value' => 'hello',
		];

		elgg_register_ajax_view('forms/query_view');

		$this->request = _elgg_testing_request('ajax/form/query_view', 'GET', $vars, 1);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(elgg_view_form('query_view', [], $vars), $response->getContent());
		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	public function testCanFilterResponseToAjaxViewRequestForARegisteredFormView() {

		$this->hooks->registerHandler('response', 'form:query_view', function($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('form:query_view', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			return elgg_error_response('good bye', REFERRER, ELGG_HTTP_BAD_REQUEST);
		});

		$vars = [
			'query_value' => 'hello',
		];

		elgg_register_ajax_view('form/query_view');

		$this->request = _elgg_testing_request('ajax/form/query_view', 'GET', $vars, 1);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertEquals('good bye', $response->getContent());
		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	/**
	 * @group AjaxService
	 */
	public function testCanRespondToAjax2ViewRequestForARegisteredFormView() {

		$vars = [
			'query_value' => 'hello',
		];

		elgg_register_ajax_view('forms/query_view');

		$this->request = _elgg_testing_request('ajax/form/query_view', 'GET', $vars, 2);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => elgg_view_form('query_view', [], $vars),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		]);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanFilterResponseToAjax2ViewRequestForARegisteredFormView() {

		$this->hooks->registerHandler('response', 'form:query_view', function($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('form:query_view', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			return elgg_error_response('good bye', REFERRER, ELGG_HTTP_BAD_REQUEST);
		});

		$vars = [
			'query_value' => 'hello',
		];

		elgg_register_ajax_view('form/query_view');

		$this->request = _elgg_testing_request('ajax/form/query_view', 'GET', $vars, 2);
		$this->createService();

		_elgg_services()->router->route($this->request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'error' => 'good bye',
		]);

		$this->assertEquals($output, $response->getContent());
	}

}
