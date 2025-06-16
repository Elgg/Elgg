<?php

namespace Elgg\Router;

use Elgg\IntegrationTestCase;
use Elgg\Users\Accounts;
use Elgg\Http\Request;

class RouteRegistrationServiceIntegrationTest extends IntegrationTestCase {

	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	/**
	 * @var RouteRegistrationService
	 */
	protected $route_service;
	
	/**
	 * @var Accounts
	 */
	protected $account_service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'minusername' => 6,
			],
		]);
		
		$this->route_service = _elgg_services()->routes;
		$this->account_service = elgg()->accounts;
	}
	
	/**
	 * @dataProvider validUsernames
	 */
	public function testCanGenerateRouteForUsername($username) {
		// make sure a user could register with the username
		$this->account_service->assertValidUsername($username);
		
		$this->user = $this->createUser([
			'username' => $username,
		]);
		
		$this->assertInstanceOf(\ElggUser::class, $this->user);
		
		// register a route where the username is used
		$this->route_service->register('foo', [
			'path' => '/foo/{username}',
			'handler' => function (Request $request) {
				return elgg_ok_response($request->getParam('username'));
			},
		]);
		
		// since a user could register with the given username, make sure routes generate correctly
		$this->assertNotFalse($this->route_service->generateUrl('foo', [
			'username' => $username,
		]));
	}
	
	public static function validUsernames() {
		return [
			['username'],
			['úsernâmé'],
			['user1234'],
			['123456789'],
			['user-name'],
			['user.name'],
			['user_name'],
			['देवनागरी'], // https://github.com/Elgg/Elgg/issues/12518 and https://github.com/Elgg/Elgg/issues/13067
		];
	}
	
	public function testUseLoggedInRegistration() {
		// no logged-in user present
		$route = $this->route_service->register('foo', [
			'path' => '/foo/{username}',
			'controller' => function($request) {
			},
			'use_logged_in' => true,
		]);
		
		$this->assertEmpty($route->getDefault('username'));
		$this->assertEmpty($route->getDefault('guid'));
		
		// with a logged-in user
		$user = $this->createUser();
		
		$session = _elgg_services()->session_manager;
		$session->setLoggedInUser($user);
		
		$route2 = $this->route_service->register('foo2', [
			'path' => '/foo2/{username}',
			'controller' => function($request) {
			},
			'use_logged_in' => true,
		]);
		
		$this->assertEquals($user->username, $route2->getDefault('username'));
		$this->assertEquals($user->guid, $route2->getDefault('guid'));
		
		// make sure existing defaults aren't overruled
		$route2 = $this->route_service->register('foo2', [
			'path' => '/foo2/{username}',
			'controller' => function($request) {
			},
			'defaults' => [
				'username' => "{$user->username}_foo",
				'guid' => -1000,
			],
			'use_logged_in' => true,
		]);
		
		$this->assertEquals("{$user->username}_foo", $route2->getDefault('username'));
		$this->assertEquals(-1000, $route2->getDefault('guid'));
	}
}
