<?php

namespace Elgg;

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

		$ex = false;
		try {
			_elgg_services()->router->route($request);
		} catch (\Exception $ex) {

		}

		if ($is_match) {
			$this->assertEquals(1, $calls);
		} else {
			$this->assertInstanceOf(PageNotFoundException::class, $ex);
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
	
	public function testGenerateURLForUnknownRoute() {
		$this->assertFalse(elgg_generate_url('unknown:route'));
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

	public function testCanGenerateEntityUrl() {

		$entity = $this->createObject([
			'title' => 'My object',
			'foo' => 'bar',
			'foo2' => 'wrong',
		]);

		// register view route
		elgg_register_route("view:object:{$entity->subtype}", [
			'path' => '/view/{guid}/{title}/{foo?}/{foo2?}',
			'handler' => function() {},
		]);

		// register view route with subview
		elgg_register_route("view:object:{$entity->subtype}:sub", [
			'path' => '/view/sub/{guid}/{title}/{foo?}/{foo2?}',
			'handler' => function() {},
		]);

		// test view route
		$url = elgg_generate_entity_url($entity, 'view', null, [
			'baz' => 'bam',
			'foo2' => 'right',
		]);

		$this->assertEquals("/view/{$entity->guid}/my-object/bar/right?baz=bam", $url);

		// test view route with subview
		$url = elgg_generate_entity_url($entity, 'view', 'sub', [
			'baz' => 'bam',
			'foo2' => 'right',
		]);

		$this->assertEquals("/view/sub/{$entity->guid}/my-object/bar/right?baz=bam", $url);

		// test unknown route for entity
		$url = elgg_generate_entity_url($entity, 'unknown', null, [
			'baz' => 'bam',
			'foo2' => 'right',
		]);
		
		$this->assertFalse($url);
	}

	public function testCanGenerateActionUrl() {

		$dt = new \DateTime();
		_elgg_services()->csrf->setCurrentTime($dt);

		$url = elgg_generate_action_url('test', [
			'foo' => [
				'bar1',
				'bar2',
			],
		]);

		$expected = elgg_http_add_url_query_elements('action/test', [
			'foo' => [
				'bar1',
				'bar2',
			],
		]);

		$expected = elgg_normalize_url($expected);
		$expected = elgg_add_action_tokens_to_url($expected);

		$this->assertEquals($expected, $url);
	}
}
