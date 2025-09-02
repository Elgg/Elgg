<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\Gatekeeper\WalledGardenException;
use Elgg\Http\Request;
use Elgg\IntegrationTestCase;
use Symfony\Component\HttpFoundation\Response;

class WalledGardenIntegrationTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function up() {
		_elgg_services()->events->backup();
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		_elgg_services()->events->restore();
	}
	
	protected function createService(Request $request) {
		$app = self::createApplication([
			'request' => $request,
		]);
		
		$app->internal_services->logger->disable();
	}
	
	protected function route(Request $request) {
		$ex = false;
		
		ob_start();
		
		try {
			$ret = _elgg_services()->router->route($request);
		} catch (\Throwable $ex) {
			// nothing yet
		}
		
		ob_end_clean();
		
		if ($ex instanceof \Throwable) {
			throw $ex;
		}
		
		return $ret;
	}
	
	/**
	 * @dataProvider publicPagesProvider
	 */
	public function testCanDetectPublicPage($path, $expected) {
		$instance = new WalledGarden();
		
		$this->assertEquals($expected, $this->invokeInaccessableMethod($instance, 'isPublicPage', elgg_normalize_url($path)));
	}
	
	public static function publicPagesProvider() {
		return [
			['ajax/view/languages.js', true],
			['css/stylesheet.css', true],
			['js/javascript.js', true],
			['cache/0/foo/bar', true],
			['cache/foo/bar', false],
			['serve-file/foo', true],
			['custom', false],
		];
	}
	
	public function testCanFilterPublicPages() {
		_elgg_services()->events->registerHandler('public_pages', 'walled_garden', function (\Elgg\Event $event) {
			$return = $event->getValue();
			
			$return[] = 'allowed/.*';
			
			return $return;
		});
		
		$instance = new WalledGarden();
		
		$this->assertTrue($this->invokeInaccessableMethod($instance, 'isPublicPage', elgg_normalize_url('allowed/foo/bar')));
	}
	
	public function testCanRoutePublicPageInWalledGardenMode() {
		$request = $this->prepareHttpRequest('foo/bar');
		$this->createService($request);
		
		elgg_register_route('foo', [
			'path' => '/foo/bar',
			'handler' => function() {
				return elgg_ok_response('foo');
			},
		]);
		
		_elgg_services()->events->registerHandler('public_pages', 'walled_garden', function (\Elgg\Event $event) {
			$return = $event->getValue();
			
			$return[] = 'foo/.*';
			
			return $return;
		});
		
		elgg_set_config('walled_garden', true);
		
		$this->route($request);
		
		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
	}
	
	public function testCanRouteNonPublicPageInWalledGardenMode() {
		$request = $this->prepareHttpRequest('bar/foo');
		$this->createService($request);
		
		elgg_register_route('foo', [
			'path' => '/bar/foo',
			'handler' => function () {
				return elgg_ok_response('hello');
			},
		]);
		
		elgg_set_config('walled_garden', true);
		
		$this->expectException(WalledGardenException::class);
		$this->route($request);
	}
	
	public function testIgnoresWalledGardenRulesWhenLoggedIn() {
		$request = $this->prepareHttpRequest('bar/foo');
		$this->createService($request);
		
		$user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($user);
		
		elgg_register_route('foo', [
			'path' => '/bar/foo',
			'handler' => function() {
				return elgg_ok_response('foo');
			},
		]);
		
		elgg_set_config('walled_garden', true);
		
		$this->route($request);
		
		$response = _elgg_services()->responseFactory->getSentResponse();
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
	}
}
