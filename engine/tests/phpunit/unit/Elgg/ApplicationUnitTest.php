<?php

namespace Elgg;

use DI\Container;
use Elgg\Application\Database;
use Elgg\Database\Select;
use Elgg\Helpers\Application\FooController;
use Elgg\Helpers\Application\FooRedirectController;
use Elgg\Helpers\Application\FooExceptionController;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Http\RedirectResponse;
use Elgg\Menu\Service;
use Elgg\Mocks\Di\MockServiceProvider;
use Elgg\Security\UrlSigner;
use Elgg\Views\TableColumn\ColumnFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Elgg\Helpers\Application\FooNonHttpExceptionController;

/**
 * @group UnitTests
 * @group Application
 */
class ApplicationUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * @return Application
	 */
	function createMockApplication(array $params = []) {
		$config = self::getTestingConfig();
		$sp = new MockServiceProvider($config);

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();
		$sp->config->boot_complete = false;
		$sp->config->system_cache_enabled = false;
		$sp->config->site = new \ElggSite((object) [
			'guid' => 1,
		]);
		$sp->config->site->name = 'Testing Site';

		$app = Application::factory(array_merge([
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		], $params));

		Application::setInstance($app);

		return $app;
	}

	function testElggReturnsContainer() {
		$this->assertInstanceOf(Container::class, elgg());
	}

	/**
	 * @dataProvider publicServiceProvider
	 */
	function testCanAccessDiServices($svc, $class) {
		$this->assertNotNull(elgg()->$svc);
		$this->assertInstanceOf($class, elgg()->$svc);
		$this->assertEquals(elgg()->$svc, elgg()->get($svc));
	}

	function publicServiceProvider() {
		return [
			['db', Database::class],
			['menus', Service::class],
			['table_columns', ColumnFactory::class],
		];
	}

	function testPublicServiceReferencesCoreService() {
		$this->assertSame(elgg()->db, _elgg_services()->publicDb);
	}

	function testCanCallService() {
		$qb = Select::fromTable('entities', 'e');
		$qb->select('1');

		_elgg_services()->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'results' => [1],
		]);

		$result = elgg()->call(function (Database $db) use ($qb) {
			return $db->getDataRow($qb);
		});

		$this->assertEquals((object) 1, $result);
	}

	function testStartsTimer() {
		unset($GLOBALS['START_MICROTIME']);

		Application::factory([
			'handle_shutdown' => false,
			'handle_exceptions' => false,
			'config' => _elgg_services()->config,
		]);

		$this->assertTrue(is_float($GLOBALS['START_MICROTIME']));
	}

	function testCanGetDb() {
		$app = $this->createMockApplication();
		$this->assertInstanceOf(Database::class, $app->getDb());
		$this->assertEquals(_elgg_services()->db->prefix, $app->getDbConfig()->getTablePrefix());
	}

	function testCanLoadCore() {
		$app = $this->createMockApplication();
		
		// will fail if this throws an exception
		$app->loadCore();
	}

	function testCanBootCore() {
		$app = $this->createMockApplication();
		$app->bootCore();
		$this->assertTrue($app->_services->config->boot_complete);
	}

	function testBootLoadsCore() {
		$app = $this->createMockApplication();
		$app->bootCore();
		$this->assertTrue($app->isCoreLoaded());
	}

	function testCanStart() {
		$app = $this->createMockApplication();
		$app->start();
		$this->assertTrue($app->_services->config->boot_complete);
	}

	function testCanBuildRequestForNewApplication() {
		$backup = Application::getInstance();

		Application::setInstance(null);

		$request = Application::getRequest();

		$this->assertEquals(\Elgg\Http\Request::createFromGlobals(), $request);

		Application::setInstance($backup);
	}

	function testReturnsRequest() {
		$request = $this->prepareHttpRequest('foo');

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		$this->assertSame($request, Application::getRequest());
		$this->assertSame($request, $app->getRequest());
	}

	function testCanRouteRequest() {
		$app = $this->createMockApplication();

		$request = $this->prepareHttpRequest('foo');

		ob_start();
		$response = Application::route($request);
		$output = ob_get_clean();

		$instance = Application::getInstance();
		$this->assertSame($app, $instance);
		$this->assertSame($request, $instance->_services->request);

		$this->assertInstanceOf(Response::class, $response);
		$this->assertSame($response, $instance->_services->responseFactory->getSentResponse());
		$this->assertEquals($output, $response->getContent());
	}

	function testCanSendResponseUnbooted() {

		Application::setInstance(null);

		$builder = new OkResponse('hello');

		ob_start();
		$response = Application::respond($builder);
		$output = ob_get_clean();

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals($output, $response->getContent());

	}

	function testCanSendResponse() {

		$app = $this->createMockApplication();

		$builder = new OkResponse('hello');

		ob_start();
		$response = $app->respond($builder);
		$output = ob_get_clean();

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals($output, $response->getContent());
		$this->assertSame($response, $app->_services->responseFactory->getSentResponse());
	}

	function testCanSendErrorResponse() {

		$app = $this->createMockApplication();

		$builder = new ErrorResponse('hello', ELGG_HTTP_FORBIDDEN);

		ob_start();
		$response = $app->respond($builder);
		$output = ob_get_clean();

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_FORBIDDEN, $response->getStatusCode());
		$this->assertEquals($output, $response->getContent());
		$this->assertSame($response, $app->_services->responseFactory->getSentResponse());
	}

	function testCanSendRedirectResponse() {

		$app = $this->createMockApplication();

		$builder = new RedirectResponse('/somewhere');

		ob_start();
		$response = $app->respond($builder);
		$output = ob_get_clean();

		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\RedirectResponse::class, $response);
		$this->assertEquals(elgg_normalize_site_url('somewhere'), $response->getTargetURL());
		$this->assertEquals($output, $response->getContent());
		$this->assertSame($response, $app->_services->responseFactory->getSentResponse());
	}

	function testCanLoadIndex() {
		$app = $this->createMockApplication();

		ob_start();
		$response = $app->index();
		$output = ob_get_clean();

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals($output, $response->getContent());
	}

	function testCanUpgrade() {
		$request = $this->prepareHttpRequest('upgrade.php');

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		$app->_services->config->security_protect_upgrade = false;

		ob_start();
		$response = $app->upgrade();
		/* @var $response \Symfony\Component\HttpFoundation\RedirectResponse */

		$output = ob_get_clean();

		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\RedirectResponse::class, $response);
		$this->assertEquals($output, $response->getContent());
		$this->assertEquals(elgg_normalize_site_url('upgrade/init'), $response->getTargetUrl());
	}

	function testFailsUpgradeWithInvalidMac() {
		$request = $this->prepareHttpRequest('upgrade.php', 'GET', [
			UrlSigner::KEY_MAC => 'abcde',
		]);

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		$app->_services->config->security_protect_upgrade = true;

		ob_start();
		$response = $app->upgrade();
		/* @var $response \Symfony\Component\HttpFoundation\RedirectResponse */

		$output = ob_get_clean();

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_FORBIDDEN, $response->getStatusCode());
		$this->assertEquals($output, $response->getContent());

	}

	function testHandlesCacheRequest() {

		$ts = mt_rand();
		$request = $this->prepareHttpRequest("/cache/$ts/default/elgg.css");

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		ob_start();
		$response = $app->index();
		$output = ob_get_clean();

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals($output, $response->getContent());
	}

	function testHandlesServeFileRequest() {

		$app = $this->createMockApplication();

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('testing.txt');
		$file->open('write');
		$file->write('hello');
		$file->close();

		$url = $file->getDownloadURL(false);
		$url = substr($url, strlen(elgg_get_site_url()));

		$request = $this->prepareHttpRequest($url);
		$app->_services->setValue('request', $request);

		ob_start();
		$response = $app->index();
		/* @var $response \Symfony\Component\HttpFoundation\BinaryFileResponse */
		$output = ob_get_clean();

		$this->assertInstanceOf(BinaryFileResponse::class, $response);
		$this->assertEquals($output, $response->getContent());
		$this->assertEquals(strlen('hello'), $response->getFile()->getSize());

		$file->delete();
	}
	
	function testHandlesRefreshTokenRequest() {

		$request = $this->prepareHttpRequest('/refresh_token');

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		ob_start();
		$response = $app->index();
		$output = ob_get_clean();
		
		$decoded = json_decode($output, true);
		$this->assertArrayHasKey('token', $decoded);
		$this->assertArrayHasKey('__elgg_ts', $decoded['token']);
		$this->assertArrayHasKey('__elgg_token', $decoded['token']);
		$this->assertArrayHasKey('logged_in', $decoded['token']);
		
		$this->assertArrayHasKey('valid_tokens', $decoded);
		$this->assertArrayHasKey('session_token', $decoded);
		$this->assertArrayHasKey('user_guid', $decoded);

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals($output, $response->getContent());
	}
	
	function testHandlesRequestToRegisteredRoute() {

		$request = $this->prepareHttpRequest("/foo", 'GET', [
			'echo' => 'Hello, World!',
		]);

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		$app->_services->routes->register('foo', [
			'path' => 'foo',
			'controller' => FooController::class,
		]);

		ob_start();
		$response = $app->index();
		$output = ob_get_clean();

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals($output, $response->getContent());
		$this->assertEquals('Hello, World!', $output);
	}

	function testHandlesRequestToRegisteredRouteThatThrows() {

		$request = $this->prepareHttpRequest("/foo", 'GET', [
			'msg' => 'I am not here',
			'code' => ELGG_HTTP_NOT_FOUND,
		]);

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		$app->_services->routes->register('foo', [
			'path' => 'foo',
			'controller' => FooExceptionController::class,
		]);

		ob_start();
		$response = $app->index();
		$output = ob_get_clean();

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_NOT_FOUND, $response->getStatusCode());
		$this->assertEquals($output, $response->getContent());
		$this->assertMatchesRegularExpression('/I am not here/im', $output);
	}

	function testHandlesRequestToRegisteredRouteThatThrowsWithRedirect() {

		$request = $this->prepareHttpRequest("/foo", 'GET', [
			'msg' => 'I am not here',
			'code' => ELGG_HTTP_NOT_FOUND,
			'forward_url' => '/take_me_home',
		]);

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		$app->_services->routes->register('foo', [
			'path' => 'foo',
			'controller' => FooRedirectController::class,
		]);

		ob_start();
		$response = $app->index();
		$output = ob_get_clean();

		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals($output, $response->getContent());
		$this->assertEquals(elgg_normalize_site_url('/take_me_home'), $response->getTargetUrl());
	}

	function testHandlesRequestToRegisteredRouteWithGatekeeper() {

		$request = $this->prepareHttpRequest("/foo", 'GET', [
			'echo' => 'Hello',
		]);

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		$app->_services->routes->register('foo', [
			'path' => 'foo',
			'controller' => FooController::class,
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		]);

		ob_start();
		$response = $app->index();
		$output = ob_get_clean();

		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals($output, $response->getContent());
		$this->assertEquals(elgg_get_login_url(), $response->getTargetUrl());
	}

	function testHandlesRequestToRegisteredActionRoute() {

		$request = $this->prepareHttpRequest("/action/foo", 'GET', [
			'echo' => 'Hello',
		], 0, true);

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		$app->_services->routes->register('action:foo', [
			'path' => '/action/foo',
			'controller' => FooController::class,
		]);

		ob_start();
		$response = $app->index();
		$output = ob_get_clean();

		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\RedirectResponse::class, $response);
		$this->assertEquals(ELGG_HTTP_FOUND, $response->getStatusCode());
		$this->assertEquals($output, $response->getContent());
		$this->assertEquals(elgg_normalize_site_url('/phpunit'), $response->getTargetUrl());
	}
	
	function testHandlesRequestToRegisteredActionRouteWithHttpExceptionInXhr() {

		$request = $this->prepareHttpRequest('action/foo', 'POST', [], 1, true);

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		$app->_services->routes->register('action:foo', [
			'path' => '/action/foo',
			'controller' => FooController::class,
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		]);
		
		ob_start();
		$response = $app->index();
		$output = ob_get_clean();

		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
		$this->assertEquals(ELGG_HTTP_UNAUTHORIZED, $response->getStatusCode());
	}
	
	function testHandlesRequestToRegisteredActionRouteWithNonHttpExceptionInXhr() {

		$request = $this->prepareHttpRequest('action/foo', 'POST', ['echo' => 'Hello'], 1, true);

		$app = $this->createMockApplication([
			'request' => $request,
		]);

		$app->_services->routes->register('action:foo', [
			'path' => '/action/foo',
			'controller' => FooNonHttpExceptionController::class,
		]);
		
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Hello');
		
		$app->index();
	}
}
