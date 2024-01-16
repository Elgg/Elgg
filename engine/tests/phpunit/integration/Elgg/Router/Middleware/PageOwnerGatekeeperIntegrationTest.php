<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\IntegrationTestCase;

class PageOwnerGatekeeperIntegrationTest extends IntegrationTestCase {
	
	/**
	 * {@inheritDoc}
	 */
	public static function prepareHttpRequest($uri = '', $method = 'GET', $parameters = [], $ajax = 0, $add_csrf_tokens = false) {
		$request = parent::prepareHttpRequest($uri, $method, $parameters, $ajax, $add_csrf_tokens);
		
		_elgg_services()->set('request', $request);
		_elgg_services()->reset('pageOwner');
		
		return $request;
	}
	
	/**
	 * @dataProvider getGatekeepers
	 */
	public function testPageOwnerMissing($middleware) {
		elgg_register_route('add:object:foo', [
			'path' => '/foo/add/{username}',
			'handler' => '\Elgg\Values::getTrue',
			'middleware' => [
				$middleware,
			],
			'walled' => false,
		]);
		
		$http_request = $this->prepareHttpRequest(elgg_generate_url('add:object:foo', [
			'username' => 'unknown_username',
		]));
		
		$this->expectException(EntityNotFoundException::class);
		_elgg_services()->router->route($http_request);
	}
	
	/**
	 * @dataProvider getGatekeepers
	 */
	public function testPageOwnerExists($middleware, $content_type) {
		
		$entity = $this->createOne($content_type);
		
		elgg_register_route('add:object:foo', [
			'path' => '/foo/add/{guid}',
			'handler' => '\Elgg\Values::getTrue',
			'middleware' => [
				$middleware,
			],
			'walled' => false,
		]);
		
		$http_request = $this->prepareHttpRequest(elgg_generate_url('add:object:foo', [
			'guid' => $entity->guid,
		]));
		
		$response = _elgg_services()->router->route($http_request);
		$this->assertTrue($response);
	}
	
	/**
	 * @dataProvider getInvalidGatekeepers
	 */
	public function testPageOwnerThrowsOnInvalidType($middleware, $content_type) {
		$entity = $this->createOne($content_type);
		
		elgg_register_route('add:object:foo', [
			'path' => '/foo/view/{guid}',
			'handler' => '\Elgg\Values::getTrue',
			'middleware' => [
				$middleware,
			],
			'walled' => false,
		]);
		
		$http_request = $this->prepareHttpRequest(elgg_generate_url('add:object:foo', [
			'guid' => $entity->guid,
		]));
		
		$this->expectException(EntityNotFoundException::class);
		_elgg_services()->router->route($http_request);
	}
	
	public static function getGatekeepers() {
		return [
			[PageOwnerGatekeeper::class, 'object'],
			[UserPageOwnerGatekeeper::class, 'user'],
			[GroupPageOwnerGatekeeper::class, 'group'],
		];
	}
	
	public static function getInvalidGatekeepers() {
		return [
			[UserPageOwnerGatekeeper::class, 'group'],
			[GroupPageOwnerGatekeeper::class, 'user'],
		];
	}
}
