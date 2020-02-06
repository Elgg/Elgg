<?php

namespace Elgg\Plugins;

use Elgg\BaseTestCase;
use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\Http\GatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\GroupGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\GroupToolGatekeeperException;
use Elgg\UnitTestCase;

/**
 * @group Router
 */
abstract class RouteResponseTest extends UnitTestCase {

	use PluginTesting;

	public function up() {
		$this->startPlugin(null, true, false, true);
		_elgg_services()->logger->disable();
	}

	public function down() {
		_elgg_services()->logger->enable();
	}

	/**
	 * Get object subtype
	 * @return mixed
	 */
	abstract function getSubtype();

	public function testAddRouteRespondsWithErrorWithoutAuthenticatedUser() {

		$user = $this->createUser();

		$url = elgg_generate_url("add:object:{$this->getSubtype()}", [
			'guid' => $user->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

		$this->expectException(GatekeeperException::class);
		_elgg_services()->router->route($request);
	}

	public function testAddRouteRespondsWithErrorIfUserIsNotPermittedToWriteToContainer() {

	    $ex = null;

		try {
			$user = $this->createUser();

			$handler = function () {
				return false;
			};

			elgg_register_plugin_hook_handler('container_permissions_check', 'object', $handler);

			_elgg_services()->session->setLoggedInUser($user);

			$url = elgg_generate_url("add:object:{$this->getSubtype()}", [
				'guid' => $user->guid,
			]);

			$request = BaseTestCase::prepareHttpRequest($url);
			_elgg_services()->setValue('request', $request);

			_elgg_services()->router->route($request);

		} catch (\Exception $ex) {

		}

		_elgg_services()->session->removeLoggedInUser();

		elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', $handler);

		$this->assertInstanceOf(EntityPermissionsException::class, $ex);
	}

	public function testAddRouteRespondsOk() {

		$user = $this->createUser();

		$handler = function () {
			return true;
		};

		elgg_register_plugin_hook_handler('container_permissions_check', 'object', $handler);

		_elgg_services()->session->setLoggedInUser($user);

		$url = elgg_generate_url("add:object:{$this->getSubtype()}", [
			'guid' => $user->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		_elgg_services()->session->removeLoggedInUser();

		elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', $handler);
	}

	public function testEditRouteRespondsWithErrorWithoutAuthenticatedUser() {

		$user = $this->createUser();
		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'owner_guid' => $user->guid,
		]);

		$url = elgg_generate_url("edit:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

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

		$handler = function () {
			return false;
		};

		elgg_register_plugin_hook_handler('permissions_check', 'object', $handler);

		_elgg_services()->session->setLoggedInUser($user);

		$url = elgg_generate_url("edit:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

		try {
			_elgg_services()->router->route($request);
		} catch (\Exception $ex) {

		}

		_elgg_services()->session->removeLoggedInUser();

		elgg_unregister_plugin_hook_handler('permissions_check', 'object', $handler);

		$this->assertInstanceOf(EntityPermissionsException::class, $ex);
	}

	public function testEditRouteRespondsOk() {

		$user = $this->createUser();

		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'owner_guid' => $user->guid,
		]);

		_elgg_services()->session->setLoggedInUser($user);

		$handler = function () {
			return true;
		};

		elgg_register_plugin_hook_handler('permissions_check', 'object', $handler);

		$url = elgg_generate_url("edit:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());

		_elgg_services()->session->removeLoggedInUser();

		elgg_unregister_plugin_hook_handler('permissions_check', 'object', $handler);
	}

	public function testViewRouteRespondsWithErrorIfEntityIsNotFound() {

		$user = $this->createUser();
		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'owner_guid' => $user->guid,
			'access_id' => ACCESS_PRIVATE,
		]);

		$object->invalidateCache();

		$url = elgg_generate_url("view:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

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

		$url = elgg_generate_url("view:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

		$this->expectException(EntityNotFoundException::class);
		_elgg_services()->router->route($request);
	}

	public function testViewRouteRespondsWithErrorIfGroupPermissionsAreNotFulfilled() {

		$user = $this->createUser();
		$group = $this->createGroup([
			'access_id' => ACCESS_PUBLIC,
			'membership' => ACCESS_PUBLIC,
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		]);

		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'container_guid' => $group->guid,
			'access_id' => ACCESS_PUBLIC,
		]);

		_elgg_services()->session->setLoggedInUser($user);

		_elgg_services()->hooks->backup();

		$ex = null;

		try {
			$url = elgg_generate_url("view:object:{$this->getSubtype()}", [
				'guid' => $object->guid,
			]);

			$request = BaseTestCase::prepareHttpRequest($url);
			_elgg_services()->setValue('request', $request);

			_elgg_services()->router->route($request);
		} catch (\Exception $ex) {

		}

		_elgg_services()->hooks->restore();
		_elgg_services()->session->removeLoggedInUser();

		$this->assertInstanceOf(GroupGatekeeperException::class, $ex);
	}

	public function testGroupCollectionRouteRespondsWithErrorIfGroupPermissionsAreNotFulfilled() {

		$user = $this->createUser();

		$group = $this->createGroup([
			'access_id' => ACCESS_PUBLIC,
			'membership' => ACCESS_PUBLIC,
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		]);

		_elgg_services()->session->setLoggedInUser($user);

		$ex = null;

		_elgg_services()->hooks->backup();

		try {
			$url = elgg_generate_url("collection:object:{$this->getSubtype()}:group", [
				'guid' => $group->guid,
			]);

			$request = BaseTestCase::prepareHttpRequest($url);
			_elgg_services()->setValue('request', $request);

			_elgg_services()->router->route($request);
		} catch (\Exception $ex) {

		}

		_elgg_services()->hooks->restore();
		_elgg_services()->session->removeLoggedInUser();

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

		$url = elgg_generate_url("view:object:{$this->getSubtype()}", [
			'guid' => $object->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
	}

	/**
	 * @dataProvider collectionRoutes
	 */
	public function testCollectionRoutesRespondOk($route, $params) {
		if ($params instanceof \Closure) {
			$params = $params();
		}

		$url = elgg_generate_url($route, $params);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
	}

	/**
	 * @dataProvider groupRoutesProtectedByToolOption
	 */
	public function testProtectedGroupRoutesThrowException($route_name, $tool_option) {
		$group = $this->createGroup([
			'access_id' => ACCESS_PUBLIC,
			'membership' => ACCESS_PUBLIC,
			'content_access_mode'=> \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
		]);
		
		// make sure tool option is registerd
		elgg()->group_tools->register($tool_option);
		
		$this->assertTrue($group->disableTool($tool_option));
		
		$url = elgg_generate_url($route_name, [
			'guid' => $group->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

		$this->expectException(GroupToolGatekeeperException::class);
		_elgg_services()->router->route($request);
	}

	/**
	 * @dataProvider groupRoutesProtectedByToolOption
	 */
	public function testProtectedGroupRoutesRespondOk($route_name, $tool_option) {
		$group = $this->createGroup([
			'access_id' => ACCESS_PUBLIC,
			'membership' => ACCESS_PUBLIC,
			'content_access_mode'=> \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
		]);
		
		// make sure tool option is registerd
		elgg()->group_tools->register($tool_option);
		
		$this->assertTrue($group->enableTool($tool_option));
		
		$url = elgg_generate_url($route_name, [
			'guid' => $group->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

		ob_start();
		_elgg_services()->router->route($request);
		ob_get_clean();

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
	}

	public function collectionRoutes() {
		self::createApplication();
		$result = [
			[
				'route' => "default:object:{$this->getSubtype()}",
				'params' => [],
			],
			[
				'route' => "collection:object:{$this->getSubtype()}:all",
				'params' => [],
			],
			[
				'route' => "collection:object:{$this->getSubtype()}:owner",
				'params' => function () {
					return [
						'username' => $this->createUser()->username,
					];
				},
			],
			[
				'route' => "collection:object:{$this->getSubtype()}:group",
				'params' => function () {
					return [
						'guid' => $this->createGroup([
							'access_id' => ACCESS_PUBLIC,
							'membership' => ACCESS_PUBLIC,
							'content_access_mode'=> \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
						])->guid,
					];
				},
			],
			[
				'route' => "collection:object:{$this->getSubtype()}:friends",
				'params' => function () {
					return [
						'username' => $this->createUser()->username,
					];
				},
			],
		];
		
		$protected_routes = $this->groupRoutesProtectedByToolOption();
		
		foreach ($result as $key => $route) {
			$route_name = $route['route'];
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
	public function groupRoutesProtectedByToolOption() {
		return [];
	}
}
