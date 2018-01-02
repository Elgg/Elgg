<?php

namespace Elgg;
use Elgg\Http\OkResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group HttpService
 * @group RouterService
 * @group UnitTests
 * @group Routing
 */
class RouteMatchingTest extends \Elgg\UnitTestCase {


	public function up() {

	}

	public function down() {

	}

	public function testCanRegisterSpecificRouteWithGlobalPagehandler() {

		$route_calls = 0;
		$page_handler_calls = 0;

		elgg_register_route('foo:bar', [
			'path' => '/foo/{bar}',
			'requirements' => ['bar' => '\w+'],
			'handler' => function () use (&$route_calls) {
				$route_calls++;
			}
		]);

		elgg_register_page_handler('foo', function () use (&$page_handler_calls) {
			$page_handler_calls++;
		});

		$request = $this->prepareHttpRequest('foo');
		_elgg_services()->router->route($request);

		$request = $this->prepareHttpRequest('foo/baz');
		_elgg_services()->router->route($request);

		$request = $this->prepareHttpRequest('foo/baz/bar');
		_elgg_services()->router->route($request);

		$this->assertEquals(1, $route_calls);
		$this->assertEquals(2, $page_handler_calls);

		elgg_unregister_route('foo:bar');
		elgg_unregister_page_handler('foo');
	}

	/**
	 * @dataProvider patternProvider
	 */
	public function testPatterns($route, $match_path, $is_match) {

		$calls = 0;

		$route['handler'] = function () use (&$calls) {
			$calls++;
		};

		elgg_register_route('foo', $route);

		$request = $this->prepareHttpRequest($match_path);
		_elgg_services()->router->route($request);

		if ($is_match) {
			$this->assertEquals(1, $calls);
		} else {
			$this->assertEquals(0, $calls);
		}

		elgg_unregister_route('foo');
	}

	public function patternProvider() {

		$config = [
			[
				'path' => '/foo/{segments?}',
				'defaults' => [],
				'requirements' => ['segments' => '.+'],
				'matches' => [
					'/foo' => true,
					'/foo/bar' => true,
					'/foo/bar/baz' => true,
					'/bar' => false,
				],
			],
			[
				'path' => '/foo/{guid}/{bar?}',
				'defaults' => [],
				'requirements' => [
					'bar' => '\w+',
				],
				'matches' => [
					'/foo' => false,
					'/foo/123' => true,
					'/foo/abc' => false,
					'/foo/123/abc3' => true,
				],
			],
			[
				'path' => '/foo/{username}/{bar?}',
				'defaults' => [],
				'requirements' => [
					'bar' => '\w+',
				],
				'matches' => [
					'/foo' => false,
					'/foo/123' => true,
					'/foo/abc123' => true,
					'/foo/abc123_abc/abc3' => true,
				],
			],
			[
				'path' => '/foo/{_underscore}/{bar?}',
				'defaults' => [],
				'requirements' => [
					'_underscore' => '\w+',
					'bar' => '\w+',
				],
				'matches' => [
					'/foo' => false,
					'/foo/123' => true,
					'/foo/abc123' => true,
					'/foo/abc123_abc/abc3' => true,
				],
			],
		];

		$provides = [];

		foreach ($config as $conf) {
			foreach ($conf['matches'] as $request_path => $is_match) {
				$route = [
					'path' => $conf['path'],
					'defaults' => $conf['defaults'],
					'requirements' => $conf['requirements'],
				];
				$provides[] = [$route, $request_path, $is_match];
			}
		}

		return $provides;
	}

	public function testCanGenerateURL() {

		elgg_register_route('foo', [
			'path' => '/hello/{guid}/{bar?}',
			'handler' => function() {},
		]);

		$this->assertEquals('/hello/123?baz=x', elgg_generate_url('foo', ['guid' => '123', 'baz' => 'x']));
		$this->assertEquals('/hello/123/x?baz=y', elgg_generate_url('foo', ['guid' => '123', 'bar' => 'x', 'baz' => 'y']));

		elgg_unregister_route('foo');
	}

	public function testResourceParameterIsNotReplaceableByQueryElements() {

		$this->viewsDir = $this->normalizeTestFilePath('views');
		_elgg_services()->views->autoregisterViews('', "$this->viewsDir/default", 'default');

		elgg_register_route('foo:bar', [
			'path' => '/foo/{bar}',
			'requirements' => ['bar' => '\w+'],
			'resource' => 'routes_match',
		]);

		$request = $this->prepareHttpRequest('foo/baz', 'GET', [
			'_resource' => 'custom_resource',
		]);

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();
		/* @var $response Response */

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals(serialize([
			'bar' => 'baz',
			'_route' => 'foo:bar',
		]), $response->getContent());

	}

	public function testHandlerParameterIsNotReplaceableByQueryElements() {

		$this->viewsDir = $this->normalizeTestFilePath('views');
		_elgg_services()->views->autoregisterViews('', "$this->viewsDir/default", 'default');

		$calls = 0;
		elgg_register_route('foo:bar', [
			'path' => '/foo/{bar}',
			'requirements' => ['bar' => '\w+'],
			'handler' => function() use (&$calls) {
				$calls++;
			}
		]);

		$request = $this->prepareHttpRequest('foo/baz', 'GET', [
			'_handler' => '_elgg_init',
		]);

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();
		/* @var $response Response */

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals(1, $calls);

	}

}
