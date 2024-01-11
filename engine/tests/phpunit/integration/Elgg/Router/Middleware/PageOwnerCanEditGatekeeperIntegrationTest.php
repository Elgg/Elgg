<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\Http\Gatekeeper\LoggedInGatekeeperException;
use Elgg\IntegrationTestCase;

class PageOwnerCanEditGatekeeperIntegrationTest extends IntegrationTestCase {

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
	public function testUserNotLoggedIn($middleware) {
		$user = $this->createUser();
		
		elgg_register_route('add:object:foo', [
			'path' => '/foo/edit/{guid}',
			'handler' => '\Elgg\Values::getTrue',
			'middleware' => [
				$middleware,
			],
			'walled' => false,
		]);
		
		$http_request = $this->prepareHttpRequest(elgg_generate_url('add:object:foo', [
			'guid' => $user->guid,
		]));
		
		$this->expectException(LoggedInGatekeeperException::class);
		_elgg_services()->router->route($http_request);
	}
	
	/**
	 * @dataProvider getGatekeepers
	 */
	public function testPageOwnerCantEdit($middleware, $content_type) {
		$owner = $this->createUser();
		
		$entity = $this->createOne($content_type, ['owner_guid' => $owner->guid]);
		
		$logged_in_user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($logged_in_user);
		
		elgg_register_route('add:object:foo', [
			'path' => '/foo/edit/{guid}',
			'handler' => '\Elgg\Values::getTrue',
			'middleware' => [
				$middleware,
			],
			'walled' => false,
		]);
		
		$http_request = $this->prepareHttpRequest(elgg_generate_url('add:object:foo', [
			'guid' => $entity->guid,
		]));
		
		$this->expectException(EntityPermissionsException::class);
		_elgg_services()->router->route($http_request);
	}

	/**
	 * @dataProvider getGatekeepers
	 */
	public function testPageOwnerCanEdit($middleware, $content_type) {
		
		$owner = $this->createUser();
		$entity = $this->createOne($content_type, ['owner_guid' => $owner->guid]);
		_elgg_services()->session_manager->setLoggedInUser($owner);
		
		elgg_register_route('add:object:foo', [
			'path' => '/foo/edit/{guid}',
			'handler' => '\Elgg\Values::getTrue',
			'middleware' => [
				$middleware,
			],
			'walled' => false,
		]);
		
		$http_request = $this->prepareHttpRequest(elgg_generate_url('add:object:foo', [
			'guid' => $entity->guid,
		]));
		
		_elgg_services()->router->route($http_request);
	}
	
	public static function getGatekeepers() {
		return [
			[PageOwnerCanEditGatekeeper::class, 'object'],
			[UserPageOwnerCanEditGatekeeper::class, 'user'],
			[GroupPageOwnerCanEditGatekeeper::class, 'group'],
		];
	}
}
