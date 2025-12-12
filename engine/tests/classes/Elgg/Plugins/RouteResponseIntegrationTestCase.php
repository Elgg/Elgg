<?php

namespace Elgg\Plugins;

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\Http\Gatekeeper\GroupGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\GroupToolGatekeeperException;
use Elgg\Exceptions\Http\GatekeeperException;
use Elgg\Router\Route;
use PHPUnit\Framework\Attributes\DataProvider;

abstract class RouteResponseIntegrationTestCase extends IntegrationTestCase {

	public function up() {
		parent::up();
		
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'walled_garden' => false,
			],
		]);
		$this->startPlugin(null, true, false, true);
		_elgg_services()->logger->disable();
	}

	/**
	 * Get object subtype
	 * @return mixed
	 */
	abstract protected static function getSubtype();
	
	/**
	 * {@inheritDoc}
	 */
	public static function prepareHttpRequest($uri = '', $method = 'GET', $parameters = [], $ajax = 0, $add_csrf_tokens = false) {
		$request = parent::prepareHttpRequest($uri, $method, $parameters, $ajax, $add_csrf_tokens);
		
		_elgg_services()->set('request', $request);
		_elgg_services()->reset('pageOwner');
		
		return $request;
	}

	public function testAddRouteRespondsWithErrorWithoutAuthenticatedUser() {

		$user = $this->createUser();

		$request = $this->prepareHttpRequest(elgg_generate_url("add:object:{$this->getSubtype()}", [
			'guid' => $user->guid,
		]));
		
		$this->expectException(GatekeeperException::class);
		_elgg_services()->router->route($request);
	}

	public function testAddRouteRespondsWithErrorIfUserIsNotPermittedToWriteToContainer() {

	    $ex = null;

		try {
			$user = $this->createUser();

			elgg_register_event_handler('container_permissions_check', 'object', '\Elgg\Values::getFalse');

			_elgg_services()->session_manager->setLoggedInUser($user);

			$request = $this->prepareHttpRequest(elgg_generate_url("add:object:{$this->getSubtype()}", [
				'guid' => $user->guid,
			]));

			_elgg_services()->router->route($request);

		} catch (\Exception $ex) {

		}

		_elgg_services()->session_manager->removeLoggedInUser();

		elgg_unregister_event_handler('container_permissions_check', 'object', '\Elgg\Values::getFalse');

		$this->assertInstanceOf(EntityPermissionsException::class, $ex);
	}

	public function testAddRouteRespondsOk() {

		$user = $this->createUser();

		elgg_register_event_handler('container_permissions_check', 'object', '\Elgg\Values::getTrue');

		_elgg_services()->session_manager->setLoggedInUser($user);

		$request = $this->prepareHttpRequest(elgg_generate_url("add:object:{$this->getSubtype()}", [
			'guid' => $user->guid,
		]));

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		_elgg_services()->session_manager->removeLoggedInUser();

		elgg_unregister_event_handler('container_permissions_check', 'object', '\Elgg\Values::getTrue');
	}

	public function testEditRouteRespondsWithErrorWithoutAuthenticatedUser() {

		$user = $this->createUser();
		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'owner_guid' => $user->guid,
		]);

		$request = $this->prepareHttpRequest(elgg_generate_url("edit:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]));

		$this->expectException(GatekeeperException::class);
		_elgg_services()->router->route($request);
	}

	public function testEditRouteRespondsWithErrorIfUserIsNotPermittedToWriteToContainer() {

	    $ex = null;

		$user = $this->createUser();
		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'owner_guid' => $user->guid,
		]);

		elgg_register_event_handler('permissions_check', 'object', '\Elgg\Values::getFalse');

		_elgg_services()->session_manager->setLoggedInUser($user);

		$request = $this->prepareHttpRequest(elgg_generate_url("edit:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]));

		try {
			_elgg_services()->router->route($request);
		} catch (\Exception $ex) {

		}

		_elgg_services()->session_manager->removeLoggedInUser();

		elgg_unregister_event_handler('permissions_check', 'object', '\Elgg\Values::getFalse');

		$this->assertInstanceOf(EntityPermissionsException::class, $ex);
	}

	public function testEditRouteRespondsOk() {

		$user = $this->createUser();

		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'owner_guid' => $user->guid,
		]);

		_elgg_services()->session_manager->setLoggedInUser($user);

		elgg_register_event_handler('permissions_check', 'object', '\Elgg\Values::getTrue');

		$request = $this->prepareHttpRequest(elgg_generate_url("edit:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]));

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		_elgg_services()->session_manager->removeLoggedInUser();

		elgg_unregister_event_handler('permissions_check', 'object', '\Elgg\Values::getTrue');
	}

	public function testViewRouteRespondsWithErrorIfEntityIsNotFound() {

		$user = $this->createUser();
		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'owner_guid' => $user->guid,
			'access_id' => ACCESS_PRIVATE,
		]);

		$object->invalidateCache();

		$request = $this->prepareHttpRequest(elgg_generate_url("view:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]));

		$this->expectException(EntityPermissionsException::class);
		_elgg_services()->router->route($request);
	}

	public function testViewRouteRespondsWithErrorIfEntityIsOfIncorrectSubtype() {

		$user = $this->createUser();
		$object = $this->createObject([
			'subtype' => 'foo',
			'owner_guid' => $user->guid,
			'access_id' => ACCESS_PUBLIC,
		]);

		$request = $this->prepareHttpRequest(elgg_generate_url("view:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]));

		$this->expectException(EntityNotFoundException::class);
		_elgg_services()->router->route($request);
	}

	public function testViewRouteRespondsWithErrorIfGroupPermissionsAreNotFulfilled() {

		$user = $this->createUser();
		
		// make sure the owner of the group is not the testing user
		$owner = $this->getRandomUser([$user->guid]);
		$group = $this->createGroup([
			'owner_guid' => $owner->guid,
			'access_id' => ACCESS_PUBLIC,
			'membership' => ACCESS_PUBLIC,
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		]);

		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'container_guid' => $group->guid,
			'access_id' => ACCESS_PUBLIC,
		]);

		_elgg_services()->session_manager->setLoggedInUser($user);

		_elgg_services()->events->backup();

		$ex = null;

		try {
			$request = $this->prepareHttpRequest(elgg_generate_url("view:object:{$this->getSubtype()}", [
				'guid' => $object->guid,
			]));

			_elgg_services()->router->route($request);
		} catch (\Exception $ex) {

		}

		_elgg_services()->events->restore();
		_elgg_services()->session_manager->removeLoggedInUser();

		$this->assertInstanceOf(GroupGatekeeperException::class, $ex);
	}

	public function testGroupCollectionRouteRespondsWithErrorIfGroupPermissionsAreNotFulfilled() {

		$user = $this->createUser();

		// make sure the owner of the group is not the testing user
		$owner = $this->getRandomUser([$user->guid]);
		$group = $this->createGroup([
			'owner_guid' => $owner->guid,
			'access_id' => ACCESS_PUBLIC,
			'membership' => ACCESS_PUBLIC,
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		]);

		_elgg_services()->session_manager->setLoggedInUser($user);

		$ex = null;

		_elgg_services()->events->backup();

		try {
			$request = $this->prepareHttpRequest(elgg_generate_url("collection:object:{$this->getSubtype()}:group", [
				'guid' => $group->guid,
			]));

			_elgg_services()->router->route($request);
		} catch (\Exception $ex) {

		}

		_elgg_services()->events->restore();
		_elgg_services()->session_manager->removeLoggedInUser();

		$this->assertInstanceOf(GroupGatekeeperException::class, $ex);
	}

	public function testViewRouteRespondsOk() {

		$user = $this->createUser();

		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'owner_guid' => $user->guid,
			'container_guid' => $user->guid,
			'access_id' => ACCESS_PUBLIC,
		]);

		$request = $this->prepareHttpRequest(elgg_generate_url("view:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]));

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
	}

	#[DataProvider('collectionRoutes')]
	public function testCollectionRoutesRespondOk($route, $params) {
		if ($params instanceof \Closure) {
			$params = $params($this);
		}

		$request = $this->prepareHttpRequest(elgg_generate_url($route, $params));

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
	}

	#[DataProvider('groupRoutesProtectedByToolOption')]
	public function testProtectedGroupRoutesThrowException($route, $tool) {
		$group = $this->createGroup([
			'access_id' => ACCESS_PUBLIC,
			'membership' => ACCESS_PUBLIC,
			'content_access_mode'=> \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
		]);
		
		// make sure tool option is registered
		elgg()->group_tools->register($tool);
		
		$this->assertTrue($group->disableTool($tool));
		
		$request = $this->prepareHttpRequest(elgg_generate_url($route, [
			'guid' => $group->guid,
		]));

		$this->expectException(GroupToolGatekeeperException::class);
		_elgg_services()->router->route($request);
	}

	#[DataProvider('groupRoutesProtectedByToolOption')]
	public function testProtectedGroupRoutesRespondOk($route, $tool) {
		$group = $this->createGroup([
			'access_id' => ACCESS_PUBLIC,
			'membership' => ACCESS_PUBLIC,
			'content_access_mode'=> \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
		]);
		
		// make sure tool option is registered
		elgg()->group_tools->register($tool);
		
		$this->assertTrue($group->enableTool($tool));
		
		$request = $this->prepareHttpRequest(elgg_generate_url($route, [
			'guid' => $group->guid,
		]));

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
	}

	public static function collectionRoutes() {
		self::createApplication();
		$subtype = static::getSubtype();
		
		$result = [
			[
				'route' => "default:object:{$subtype}",
				'params' => [],
			],
			[
				'route' => "collection:object:{$subtype}:all",
				'params' => [],
			],
			[
				'route' => "collection:object:{$subtype}:owner",
				'params' => function (RouteResponseIntegrationTestCase $testcase) {
					return [
						'username' => $testcase->createUser()->username,
					];
				},
			],
			[
				'route' => "collection:object:{$subtype}:group",
				'params' => function (RouteResponseIntegrationTestCase $testcase) {
					return [
						'guid' => $testcase->createGroup([
							'access_id' => ACCESS_PUBLIC,
							'membership' => ACCESS_PUBLIC,
							'content_access_mode'=> \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
						])->guid,
					];
				},
			],
			[
				'route' => "collection:object:{$subtype}:friends",
				'params' => function (RouteResponseIntegrationTestCase $testcase) {
					return [
						'username' => $testcase->createUser()->username,
					];
				},
			],
		];
		
		$router = _elgg_services()->routes;
		$protected_routes = static::groupRoutesProtectedByToolOption();
		
		foreach ($result as $key => $route) {
			$route_name = $route['route'];
			
			$route = $router->get($route_name);
			if (!$route instanceof Route) {
				// don't assume all plugins provide all collection routes
				unset($result[$key]);
				continue;
			}
			
			foreach ($protected_routes as $protected_route) {
				if ($protected_route['route'] === $route_name) {
					unset($result[$key]);
				}
			}
		}
		
		return $result;
	}
	
	
	/**
	 * This function can be used by plugins to provide an array [['route' => 'routename', 'tool' => 'tooloption']] of group routes that are protected by a group tool option
	 *
	 * @return array
	 */
	public static function groupRoutesProtectedByToolOption() {
		return [];
	}
}
