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
		$this->route_service = _elgg_services()->routes;
		$this->account_service = elgg()->accounts;
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		if ($this->user instanceof \ElggUser) {
			$this->user->delete;
		}
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
	
	public function validUsernames() {
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
}
