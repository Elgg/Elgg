<?php

namespace Elgg;

use Elgg\Ajax\Service;
use Elgg\Http\Input;
use Elgg\Http\OkResponse;
use Elgg\Http\Request;
use Elgg\Http\ResponseFactory;
use Elgg\I18n\Translator;
use Elgg\Router\Middleware\WalledGarden;
use Elgg\Router\RouteRegistrationService;
use ElggSession;
use Flintstone\Exception;
use stdClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group HttpService
 * @group RouterService
 * @group Routing
 * @group UnitTests
 */
class RouterUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var Router
	 */
	protected $router;

	/**
	 * @var RouteRegistrationService
	 */
	protected $routes;

	/**
	 * @var string
	 */
	protected $pages;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * @var string
	 */
	protected $viewsDir;

	/**
	 * @var int
	 */
	protected $fooHandlerCalls = 0;

	public function up() {

		$this->pages = $this->normalizeTestFilePath('pages');
		$this->fooHandlerCalls = 0;
		$this->viewsDir = $this->normalizeTestFilePath('views');

		$request = $this->prepareHttpRequest('', 'GET');
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

		$svc->hooks->backup();
		$svc->logger->disable();

		$svc->translator->addTranslation('en', ['__test__' => 'Test']);

		$svc->views->autoregisterViews('', "$this->viewsDir/default", 'default');
		$svc->views->autoregisterViews('', "$this->viewsDir/json", 'json');
		$svc->views->setViewtype('');

		_elgg_register_routes();

	}

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

	function hello_page_handler($segments, $identifier) {
		include "{$this->pages}/hello.php";

		return true;
	}

	function testCanRegisterFunctionsAsPageHandlers() {
		$registered = _elgg_services()->routes->registerPageHandler('hello', [$this, 'hello_page_handler']);

		$this->assertTrue($registered);

		$path = "hello/1/%E2%82%AC"; // euro sign

		ob_start();
		$handled = _elgg_services()->router->route($this->prepareHttpRequest($path));
		ob_end_clean();
		$this->assertTrue($handled);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);

		$this->assertEquals($path, $response->getContent());
	}

	function testFailToRegisterInvalidCallback() {
		$registered = _elgg_services()->routes->registerPageHandler('hello', new stdClass());

		$this->assertFalse($registered);
	}

	/**
	 * @expectedException \Elgg\PageNotFoundException
	 */
	function testCanUnregisterPageHandlers() {
		$request = $this->prepareHttpRequest('hello');
		$this->createService($request);

		_elgg_services()->routes->registerPageHandler('hello', [$this, 'hello_page_handler']);
		_elgg_services()->routes->unregisterPageHandler('hello');

		_elgg_services()->router->route($request);
	}

	/**
	 * 1. Register a page handler for `/foo`
	 * 2. Register a plugin hook that uses the "handler" result param
	 *    to route all `/bar/*` requests to the `/foo` handler.
	 * 3. Route a request for a `/bar` page.
	 * 4. Check that the `/foo` handler was called.
	 */
	function testRouteSupportsSettingHandlerInHookResultForBackwardsCompatibility() {
		$request = $this->prepareHttpRequest('bar/baz');
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('route', 'bar', [$this, 'bar_route_handler']);

		ob_start();
		_elgg_services()->router->route($request);
		ob_end_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();
		/* @var $response RedirectResponse */
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(elgg_normalize_url('foo/baz'), $response->getTargetUrl());
	}

	/**
	 * 1. Register a page handler for `/foo`
	 * 2. Register a plugin hook that uses the "handler" result param
	 *    to route all `/bar/*` requests to the `/foo` handler.
	 * 3. Route a request for a `/bar` page.
	 * 4. Check that the `/foo` handler was called.
	 */
	function testRouteSupportsSettingIdentifierInHookResultForBackwardsCompatibility() {
		$request = $this->prepareHttpRequest('bar/baz');
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('route', 'bar', [$this, 'bar_route_identifier']);

		ob_start();
		_elgg_services()->router->route($request);
		ob_end_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(elgg_normalize_url('foo/baz'), $response->getTargetUrl());
	}

	function testRouteOverridenFromHook() {
		$request = $this->prepareHttpRequest('foo');
		$this->createService($request);

		_elgg_services()->routes->registerPageHandler('foo', [$this, 'foo_page_handler']);
		_elgg_services()->hooks->registerHandler('route', 'foo', [$this, 'bar_route_override']);

		ob_start();
		_elgg_services()->router->route($request);
		$result = ob_get_clean();

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
		$request = $this->prepareHttpRequest('foo/bar/baz');
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('route:rewrite', 'foo', function ($hook, $type, $return, $params) {
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

		$return = _elgg_services()->router->allowRewrite($request);

		$this->assertInstanceOf(Request::class, $return);

		$segments = $return->getUrlSegments();
		$identifier = array_shift($segments);
		$this->assertEquals('foo2', $identifier);
		$this->assertEquals(['bar2', 'baz2'], $segments);
	}

	public function testCanRespondToNonAjaxRequestThroughRouteHook() {
		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET');
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('route', 'foo', function ($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			echo 'good bye';

			return false;
		});

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals('good bye', $response->getContent());
	}

	public function testCanRedirectNonAjaxRequestFromRouteHook() {
		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET');
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('route', 'foo', function ($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			_elgg_services()->responseFactory->redirect('foo2/bar2/baz2');

			return false;
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('foo2/bar2/baz2'), $response->getTargetUrl());
	}

	public function testCanFilterResponse() {
		$request = $this->prepareHttpRequest('foo/bar', 'GET');
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('response', 'path:foo/bar', function ($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('path:foo/bar', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			return elgg_error_response('', REFERRER, ELGG_HTTP_BAD_REQUEST);
		});

		elgg_register_page_handler('foo', function () {
			echo 'hello';

			return true;
		});

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_BAD_REQUEST, $response->getStatusCode());
		$this->assertEquals(elgg_view_resource('error', ['type' => (string) ELGG_HTTP_BAD_REQUEST]), $response->getContent());
	}

	public function testCanRespondToNonAjaxRequestFromOkResponseBuilder() {

		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET');
		$this->createService($request);

		elgg_register_page_handler('foo', function ($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);

			return elgg_ok_response('hello');
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals('hello', $response->getContent());
	}

	public function testCanRespondToNonAjaxRequestFromErrorResponseBuilder() {

		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET');
		$this->createService($request);

		elgg_register_page_handler('foo', function ($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);

			return elgg_error_response('', 'phpunit', ELGG_HTTP_NOT_FOUND);
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $response->getStatusCode());
		$error_page = elgg_view_resource('error', [
			'type' => (string) ELGG_HTTP_NOT_FOUND,
		]);
		$this->assertEquals($error_page, $response->getContent());
	}

	public function testCanRespondToNonAjaxRequestFromRedirectResponseBuilder() {

		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET');
		$this->createService($request);

		elgg_register_page_handler('foo', function ($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);

			return elgg_redirect_response('foo2/bar2/baz2');
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals(elgg_normalize_url('foo2/bar2/baz2'), $response->getTargetUrl());
	}

	public function testCanRespondToNonAjaxRequestInNonDefaultViewtype() {

		$request = $this->prepareHttpRequest('phpunit', 'GET', ['view' => 'json']);
		$this->createService($request);

		elgg_register_page_handler('phpunit', function () {
			$output = elgg_view('response');

			return elgg_ok_response($output);
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(elgg_view('response', [], 'json'), $response->getContent());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	public function testCanRespondToNonAjaxRequestForPageThatForwards() {

		$request = $this->prepareHttpRequest('phpunit', 'GET');
		$this->createService($request);

		elgg_register_page_handler('phpunit', function () {
			_elgg_services()->responseFactory->redirect('index');

			return elgg_ok_response('foo');
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
	}

	public function testCanRespondToNonAjaxRequestForPageThatForwardsToErrorPage() {
		$request = $this->prepareHttpRequest('phpunit', 'GET');
		$this->createService($request);

		elgg_register_page_handler('phpunit', function () {
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_NOT_FOUND);

			return elgg_ok_response('foo');
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $response->getStatusCode());
		$error_page = elgg_view_resource('error', [
			'type' => (string) ELGG_HTTP_NOT_FOUND,
		]);
		$this->assertEquals($error_page, $response->getContent());
	}

	public function testCanSafelyRedirectWithinRedirect() {
		$request = $this->prepareHttpRequest('phpunit', 'GET');
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('forward', (string) ELGG_HTTP_NOT_FOUND, function () {
			$this->fooHandlerCalls++;
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_BAD_REQUEST);
		});

		_elgg_services()->hooks->registerHandler('forward', (string) ELGG_HTTP_BAD_REQUEST, function () {
			$this->fooHandlerCalls++;
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_INTERNAL_SERVER_ERROR);
		});

		elgg_register_page_handler('phpunit', function () {
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_NOT_FOUND);

			return elgg_ok_response('foo');
		});

		$this->assertTrue($this->route($request));

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
		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET', [], 1);
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('route', 'foo', function ($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			echo 'good bye';

			return false;
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		// no forward call, so API outputs content
		$this->assertEquals('good bye', $response->getContent());
	}

	public function testCanRedirectAjaxRequestFromRouteHook() {
		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET', [], 1);
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('route', 'foo', function ($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			_elgg_services()->responseFactory->redirect('foo2/bar2/baz2');

			return false;
		});

		$this->assertTrue($this->route($request));

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

		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET', [], 1);
		$this->createService($request);

		elgg_register_page_handler('foo', function ($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);

			return elgg_ok_response('hello');
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		// no forward call, so API outputs content
		$this->assertEquals('hello', $response->getContent());
	}

	public function testCanRespondToAjaxRequestFromErrorResponseBuilder() {

		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET', [], 1);
		$this->createService($request);

		elgg_register_page_handler('foo', function ($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);

			return elgg_error_response('', 'phpunit', ELGG_HTTP_NOT_FOUND);
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $response->getStatusCode());

		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		// no forward, so API outputs html
		$this->assertEquals('', $response->getContent());
	}

	public function testCanRespondToAjaxRequestFromRedirectResponseBuilder() {

		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET', [], 1);
		$this->createService($request);

		elgg_register_page_handler('foo', function ($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);

			return elgg_redirect_response('foo2/bar2/baz2');
		});

		$this->assertTrue($this->route($request));

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

		$request = $this->prepareHttpRequest('phpunit', 'GET', ['view' => 'json'], 1);
		$this->createService($request);

		elgg_register_page_handler('phpunit', function () {
			$output = elgg_view('response');

			return elgg_ok_response($output);
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));

		// no forward, so API outputs view contents
		$this->assertEquals(elgg_view('response', [], 'json'), $response->getContent());
	}

	public function testCanRespondToAjaxRequestForPageThatForwards() {

		$request = $this->prepareHttpRequest('phpunit', 'GET', [], 1);
		$this->createService($request);

		elgg_register_page_handler('phpunit', function () {
			_elgg_services()->responseFactory->redirect('index');

			return elgg_ok_response('foo');
		});

		$this->assertTrue($this->route($request));

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
		$request = $this->prepareHttpRequest('phpunit', 'GET', [], 1);
		$this->createService($request);

		elgg_register_page_handler('phpunit', function () {
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_NOT_FOUND);

			return elgg_ok_response('foo');
		});

		$this->assertTrue($this->route($request));

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
		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET', [], 2);
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('route', 'foo', function ($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			echo 'good bye';

			return false;
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => 'good bye',
			'current_url' => elgg_normalize_url('foo/bar/baz'),
			'forward_url' => elgg_normalize_url(''),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRedirectAjax2RequestFromRouteHook() {
		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET', [], 2);
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('route', 'foo', function ($hook, $type, $return, $params) {
			$this->assertEquals($return, $params);
			$this->assertEquals('foo', $return['identifier']);
			$this->assertEquals(['bar', 'baz'], $return['segments']);
			_elgg_services()->responseFactory->redirect('foo2/bar2/baz2');

			return false;
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => '',
			'current_url' => elgg_normalize_url('foo/bar/baz'),
			'forward_url' => elgg_normalize_url('foo2/bar2/baz2'),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	/**
	 * @group Current
	 */
	public function testCanRespondToAjax2RequestFromOkResponseBuilder() {

		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET', [], 2);
		$this->createService($request);

		elgg_register_page_handler('foo', function ($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);

			return elgg_ok_response('hello');
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => 'hello',
			'current_url' => elgg_normalize_url('foo/bar/baz'),
			'forward_url' => elgg_normalize_url(''),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToAjax2RequestFromErrorResponseBuilder() {

		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET', [], 2);
		$this->createService($request);

		elgg_register_page_handler('foo', function ($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);

			return elgg_error_response('', 'phpunit', ELGG_HTTP_NOT_FOUND);
		});

		$this->assertTrue($this->route($request));

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

		$request = $this->prepareHttpRequest('foo/bar/baz', 'GET', [], 2);
		$this->createService($request);

		elgg_register_page_handler('foo', function ($segments, $identifier) {
			$this->assertEquals(['bar', 'baz'], $segments);
			$this->assertEquals('foo', $identifier);

			return elgg_redirect_response('foo2/bar2/baz2');
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => '',
			'current_url' => elgg_normalize_url('foo/bar/baz'),
			'forward_url' => elgg_normalize_url('foo2/bar2/baz2'),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRespondToAjax2RequestInNonDefaultViewtype() {

		$request = $this->prepareHttpRequest('phpunit', 'GET', ['view' => 'json'], 2);
		$this->createService($request);

		elgg_register_page_handler('phpunit', function () {
			$output = elgg_view('response');

			return elgg_ok_response($output);
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => [
				'foo' => 'bar',
			],
			'current_url' => elgg_http_add_url_query_elements(elgg_normalize_url('phpunit'), [
				'view' => 'json',
			]),
			'forward_url' => elgg_normalize_url(''),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	public function testCanRespondToAjax2RequestForPageThatForwards() {

		$request = $this->prepareHttpRequest('phpunit', 'GET', [], 2);
		$this->createService($request);

		elgg_register_page_handler('phpunit', function () {
			_elgg_services()->responseFactory->redirect('index');

			return elgg_ok_response('foo');
		});

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => '',
			'current_url' => elgg_normalize_url('phpunit'),
			'forward_url' => elgg_normalize_url('index'),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());

		// compensate for fact that ResponseFactory::redirect closes a buffer it didn't open
		ob_start();
	}

	public function testCanRespondToAjax2RequestForPageThatForwardsToErrorPage() {
		$request = $this->prepareHttpRequest('phpunit', 'GET', [], 2);
		$this->createService($request);

		elgg_register_page_handler('phpunit', function () {
			_elgg_services()->responseFactory->redirect('error', ELGG_HTTP_NOT_FOUND);

			return elgg_ok_response('foo');
		});

		$this->assertTrue($this->route($request));

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
	 * @expectedException \Elgg\PageNotFoundException
	 */
	public function testCanRespondToUnregisteredRoute() {

		$request = $this->prepareHttpRequest('unknown', 'GET');
		$this->createService($request);

		$this->route($request);
	}

	/**
	 * @expectedException \Elgg\BadRequestException
	 */
	public function testRespondsWithErrorToNonAjaxRequestForAjaxView() {

		$request = $this->prepareHttpRequest('ajax/view/unallowed', 'GET');
		$this->createService($request);

		$this->route($request);
	}

	public function testCanRespondWithErrorToAjaxViewRequestForUnallowedView() {

		$request = $this->prepareHttpRequest('ajax/view/unallowed', 'GET', [], 1);
		$this->createService($request);

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_FORBIDDEN, $response->getStatusCode());
		$this->assertEquals("Ajax view 'unallowed' was not registered", $response->getContent());
	}

	public function testCanRespondToAjaxViewRequestForCacheableView() {

		$request = $this->prepareHttpRequest('ajax/view/cacheable.xml', 'GET', [], 1);
		$this->createService($request);

		$this->assertTrue(_elgg_services()->views->isCacheableView('cacheable.xml'));

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(file_get_contents($this->viewsDir . '/default/cacheable.xml'), $response->getContent());
		$this->assertContains('/xml', $response->headers->get('Content-Type'));
	}

	public function testCanRespondToAjaxViewRequestForCSS() {

		$request = $this->prepareHttpRequest('ajax/view/css/styles.css', 'GET', [], 1);
		$this->createService($request);

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(file_get_contents($this->viewsDir . '/default/styles.css'), $response->getContent());
		$this->assertContains('text/css', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	public function testCanRespondToAjaxViewRequestForJS() {

		$request = $this->prepareHttpRequest('ajax/view/js/javascript.js', 'GET', [], 1);
		$this->createService($request);

		$this->route($request);

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

		$request = $this->prepareHttpRequest('ajax/view/query_view', 'GET', $vars, 1);
		$this->createService($request);

		elgg_register_ajax_view('query_view');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals('hello', $response->getContent());
		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	public function testCanFilterResponseToAjaxViewRequestForARegisteredView() {
		$vars = [
			'query_value' => 'hello',
		];

		$request = $this->prepareHttpRequest('ajax/view/query_view', 'GET', $vars, 1);
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('response', 'view:query_view', function ($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('view:query_view', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			return elgg_error_response('good bye', REFERRER, ELGG_HTTP_BAD_REQUEST);
		});

		elgg_register_ajax_view('query_view');

		$this->route($request);

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

		$request = $this->prepareHttpRequest('ajax/view/forwards', 'GET', $vars, 1);
		$this->createService($request);

		elgg_register_ajax_view('forwards');

		$this->route($request);

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

		$request = $this->prepareHttpRequest('ajax/view/unallowed', 'GET', [], 2);
		$this->createService($request);

		$this->route($request);

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
	 * @see   #9797
	 */
	public function testCanRespondToAjax2ViewRequestForCacheableView() {

		$request = $this->prepareHttpRequest('ajax/view/cacheable.xml', 'GET', [], 2);
		$this->createService($request);

		$this->assertTrue(_elgg_services()->views->isCacheableView('cacheable.xml'));

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => file_get_contents($this->viewsDir . '/default/cacheable.xml'),
			'current_url' => elgg_normalize_url('ajax/view/cacheable.xml'),
			'forward_url' => elgg_normalize_url(''),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 * @see   #9797
	 */
	public function testCanRespondToAjax2ViewRequestForCSS() {

		$request = $this->prepareHttpRequest('ajax/view/css/styles.css', 'GET', [], 2);
		$this->createService($request);

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => file_get_contents($this->viewsDir . '/default/styles.css'),
			'current_url' => elgg_normalize_url('ajax/view/css/styles.css'),
			'forward_url' => elgg_normalize_url(''),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 * @see   #9797
	 */
	public function testCanRespondToAjax2ViewRequestForJS() {

		$request = $this->prepareHttpRequest('ajax/view/js/javascript.js', 'GET', [], 2);
		$this->createService($request);

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => file_get_contents($this->viewsDir . '/default/javascript.js'),
			'current_url' => elgg_normalize_url('ajax/view/js/javascript.js'),
			'forward_url' => elgg_normalize_url(''),
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

		$request = $this->prepareHttpRequest('ajax/view/query_view', 'GET', $vars, 2);
		$this->createService($request);

		elgg_register_ajax_view('query_view');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => 'hello',
			'current_url' => elgg_http_add_url_query_elements(elgg_normalize_url('ajax/view/query_view'), [
				'query_value' => 'hello',
			]),
			'forward_url' => elgg_normalize_url(''),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanFilterResponseToAjax2ViewRequestForARegisteredView() {
		$vars = [
			'query_value' => 'hello',
		];

		$request = $this->prepareHttpRequest('ajax/view/query_view', 'GET', $vars, 2);
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('response', 'view:query_view', function ($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('view:query_view', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			return elgg_error_response('good bye', REFERRER, ELGG_HTTP_BAD_REQUEST);
		});

		elgg_register_ajax_view('query_view');

		$this->route($request);

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

		$request = $this->prepareHttpRequest('ajax/view/forwards', 'GET', $vars, 2);
		$this->createService($request);

		elgg_register_ajax_view('forwards');

		$this->route($request);

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

		$request = $this->prepareHttpRequest('ajax/form/query_view', 'GET', $vars, 1);
		$this->createService($request);

		elgg_register_ajax_view('forms/query_view');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(elgg_view_form('query_view', [], $vars), $response->getContent());
		$this->assertContains('text/html', $response->headers->get('Content-Type'));
		$this->assertContains('charset=utf-8', strtolower($response->headers->get('Content-Type')));
	}

	public function testCanFilterResponseToAjaxViewRequestForARegisteredFormView() {
		$vars = [
			'query_value' => 'hello',
		];

		$request = $this->prepareHttpRequest('ajax/form/query_view', 'GET', $vars, 1);
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('response', 'form:query_view', function ($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('form:query_view', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			return elgg_error_response('good bye', REFERRER, ELGG_HTTP_BAD_REQUEST);
		});

		elgg_register_ajax_view('form/query_view');

		$this->route($request);

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

		$request = $this->prepareHttpRequest('ajax/form/query_view', 'GET', $vars, 2);
		$this->createService($request);

		elgg_register_ajax_view('forms/query_view');

		$this->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertContains('application/json', $response->headers->get('Content-Type'));

		$output = json_encode([
			'value' => elgg_view_form('query_view', [], $vars),
			'current_url' => elgg_http_add_url_query_elements(elgg_normalize_url('ajax/form/query_view'), [
				'query_value' => 'hello',
			]),
			'forward_url' => elgg_normalize_url(''),
			'_elgg_msgs' => (object) [],
			'_elgg_deps' => [],
		], ELGG_JSON_ENCODING);

		$this->assertEquals($output, $response->getContent());
	}

	/**
	 * @group AjaxService
	 */
	public function testCanFilterResponseToAjax2ViewRequestForARegisteredFormView() {
		$vars = [
			'query_value' => 'hello',
		];
		$request = $this->prepareHttpRequest('ajax/form/query_view', 'GET', $vars, 2);
		$this->createService($request);

		_elgg_services()->hooks->registerHandler('response', 'form:query_view', function ($hook, $type, $response, $params) {
			$this->assertEquals('response', $hook);
			$this->assertEquals('form:query_view', $type);
			$this->assertEquals($response, $params);
			$this->assertInstanceOf(OkResponse::class, $response);

			return elgg_error_response('good bye', REFERRER, ELGG_HTTP_BAD_REQUEST);
		});

		elgg_register_ajax_view('form/query_view');

		$this->route($request);

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
	 * @dataProvider publicPagesProvider
	 * @group        WalledGarden
	 */
	public function testCanDetectPublicPage($path, $expected) {
		$this->assertEquals($expected, WalledGarden::isPublicPage(elgg_normalize_url($path)));
	}

	function publicPagesProvider() {
		return [
			['walled_garden/core/', true],
			['action/default/action', true],
			['login', true],
			['register', true],
			['forgotpassword', true],
			['changepassword', true],
			['refresh_token', true],
			['ajax/view/languages.js', true],
			['upgrade.php', true],
			['css/stylesheet.css', true],
			['js/javascript.js', true],
			['cache/0/foo/bar', true],
			['cache/foo/bar', false],
			['cron/run', true],
			['services/rest/json', true],
			['serve-file/foo', true],
			['/robots.txt', true], // need slash to properly normalize url
			['/favicon.ico', true],
			['custom', false],
		];
	}

	/**
	 * @group WalledGarden
	 */
	public function testCanFilterPublicPages() {

		_elgg_services()->hooks->backup();

		_elgg_services()->hooks->registerHandler('public_pages', 'walled_garden', function ($hook, $type, $return) {
			$return[] = 'allowed/.*';

			return $return;
		});

		$this->assertTrue(WalledGarden::isPublicPage(elgg_normalize_url('allowed/foo/bar')));

		_elgg_services()->hooks->restore();
	}

	/**
	 * @group WalledGarden
	 */
	public function testCanRoutePublicPageInWalledGardenMode() {

		$request = $this->prepareHttpRequest('foo/bar');
		$this->createService($request);

		_elgg_services()->hooks->backup();

		_elgg_services()->hooks->registerHandler('route', 'foo', function () {
			echo 'foo';

			return false;
		});

		_elgg_services()->hooks->registerHandler('public_pages', 'walled_garden', function ($hook, $type, $return) {
			$return[] = 'foo/.*';

			return $return;
		});

		elgg_set_config('walled_garden', true);

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		_elgg_services()->hooks->restore();
	}

	/**
	 * @group WalledGarden
	 * @expectedException \Elgg\WalledGardenException
	 */
	public function testCanRouteNonPublicPageInWalledGardenMode() {
		$request = $this->prepareHttpRequest('bar/foo');
		$this->createService($request);

		elgg_register_route('foo', [
			'path' => '/bar/foo',
			'handler' => function () {
				//		echo 'hello';
			}
		]);

		elgg_set_config('walled_garden', true);

		_elgg_services()->hooks->backup();

		$ex = new \Exception();

		try {
			$this->route($request);
		} catch (\Exception $ex) {

		}

		_elgg_services()->hooks->restore();

		throw $ex;
	}

	/**
	 * @group WalledGarden
	 */
	public function testIgnoresWalledGardenRulesWhenLoggedIn() {
		$request = $this->prepareHttpRequest('bar/foo');
		$this->createService($request);

		$user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($user);

		_elgg_services()->hooks->backup();
		_elgg_services()->hooks->registerHandler('route', 'bar', function () {
			echo 'bar';

			return false;
		});

		elgg_set_config('walled_garden', true);

		$this->assertTrue($this->route($request));

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		_elgg_services()->hooks->restore();

		_elgg_services()->session->removeLoggedInUser($user);
	}

	/**
	 * @group Middleware
	 */
	public function testCanUseRouteMiddleware() {

		$request = $this->prepareHttpRequest('bar/foo/baz', 'GET', [
			'q1' => 'v1',
		]);
		$this->createService($request);

		$calls = 0;

		elgg_register_route('foo', [
			'path' => '/bar/foo/{baz}',
			'handler' => function ($segments, $identifier, \Elgg\Request $request) {
				$this->assertEquals('altered', $request->getParam('baz'));
			},
			'middleware' => [
				function (\Elgg\Request $request) use (&$calls) {
					$this->assertEquals('baz', $request->getParam('baz'));
					$this->assertEquals('v1', $request->getParam('q1'));
					$this->assertEquals('GET', $request->getMethod());
					$this->assertEquals(elgg_normalize_url('bar/foo/baz?q1=v1'), $request->getURL());
					$this->assertEquals('bar/foo/baz', $request->getPath());
					$this->assertNull($request->getEntityParam());
					$this->assertNull($request->getUserParam());
					$this->assertFalse($request->isXhr());

					$request->setParam('baz', 'altered');
					$calls++;
				}
			]
		]);

		_elgg_services()->hooks->backup();

		$this->route($request);

		_elgg_services()->hooks->restore();

		$this->assertEquals(1, $calls);
	}

	public function testCanUseRouteFile() {

		$request = $this->prepareHttpRequest('bar/foo/baz', 'POST', [
			'q1' => 'v1',
		]);

		$this->createService($request);

		$calls = 0;

		elgg_register_route('foo', [
			'path' => '/bar/foo/{baz}',
			'file' => $this->normalizeTestFilePath('actions/route.php'),
		]);

		_elgg_services()->hooks->backup();

		$this->route($request);

		_elgg_services()->hooks->restore();

		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(json_encode([
			'q1' => 'v1',
			'baz' => 'baz',
			'_route' => 'foo',
		]), $response->getContent());
	}

	public function testCanUseRouteController() {

		$request = $this->prepareHttpRequest('bar/foo/baz', 'GET', [
			'q1' => 'v1',
		]);
		$this->createService($request);

		$calls = 0;

		elgg_register_route('foo', [
			'path' => '/bar/foo/{baz}',
			'controller' => function (\Elgg\Request $request) use (&$calls) {
				$this->assertEquals('baz', $request->getParam('baz'));
				$this->assertEquals('v1', $request->getParam('q1'));
				$this->assertEquals('GET', $request->getMethod());
				$this->assertEquals(elgg_normalize_url('bar/foo/baz?q1=v1'), $request->getURL());
				$this->assertEquals('bar/foo/baz', $request->getPath());
				$this->assertNull($request->getEntityParam());
				$this->assertNull($request->getUserParam());
				$this->assertFalse($request->isXhr());
				$calls++;

				return elgg_ok_response('');
			}
		]);

		_elgg_services()->hooks->backup();

		$this->route($request);

		_elgg_services()->hooks->restore();

		$this->assertEquals(1, $calls);
	}
}
