<?php

namespace Elgg\Plugins;

use Elgg\BaseTestCase;
use Elgg\IntegrationTestCase;

/**
 * @group Router
 */
abstract class RouteResponseTest extends IntegrationTestCase {

	use PluginTesting;

	public function up() {
		self::createApplication(true);
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

	/**
	 * @expectedException \Elgg\GatekeeperException
	 */
	public function testAddRouteRespondsWithErrorWithoutAuthenticatedUser() {

		$user = $this->createUser();

		$url = elgg_generate_url("add:object:{$this->getSubtype()}", [
			'guid' => $user->guid,
		]);

		$request = BaseTestCase::prepareHttpRequest($url);
		_elgg_services()->setValue('request', $request);

		_elgg_services()->router->route($request);
	}

	/**
	 * @expectedException \Elgg\EntityPermissionsException
	 */
	public function testAddRouteRespondsWithErrorIfUserIsNotPermittedToWriteToContainer() {

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
			_elgg_services()->session->removeLoggedInUser();

			elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', $handler);
			
			throw $ex;
		}

		_elgg_services()->session->removeLoggedInUser();

		elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', $handler);
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

	/**
	 * @expectedException \Elgg\GatekeeperException
	 */
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

		_elgg_services()->router->route($request);
	}

	/**
	 * @expectedException \Elgg\EntityPermissionsException
	 */
	public function testEditRouteRespondsWithErrorIfUserIsNotPermittedToWriteToContainer() {

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
			_elgg_services()->session->removeLoggedInUser();

			elgg_unregister_plugin_hook_handler('permissions_check', 'object', $handler);
			
			throw $ex;
		}

		_elgg_services()->session->removeLoggedInUser();

		elgg_unregister_plugin_hook_handler('permissions_check', 'object', $handler);
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

	/**
	 * @expectedException \Elgg\GatekeeperException
	 */
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

			_elgg_services()->router->route($request);
		}

	/**
	 * @expectedException \Elgg\BadRequestException
	 */
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

		_elgg_services()->router->route($request);
	}

	/**
	 * @expectedException \Elgg\EntityPermissionsException
	 * @group Current
	 */
	public function testViewRouteRespondsWithErrorIfGroupPermissionsAreNotFulfilled() {

		$user = $this->createUser();
		$group = $this->createGroup([], [
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		]);

		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'container_guid' => $group->guid,
			'access_id' => ACCESS_PUBLIC,
		]);

		_elgg_services()->session->setLoggedInUser($user);

		try {
			$url = elgg_generate_url("view:object:{$this->getSubtype()}", [
				'guid' => $object->guid,
			]);

			$request = BaseTestCase::prepareHttpRequest($url);
			_elgg_services()->setValue('request', $request);

			_elgg_services()->router->route($request);
		} catch (\Exception $ex) {
			_elgg_services()->session->removeLoggedInUser();
			
			throw $ex;
		}

		_elgg_services()->session->removeLoggedInUser();
	}

	/**
	 * @expectedException \Elgg\EntityPermissionsException
	 * @group Current
	 */
	public function testGroupCollectionRouteRespondsWithErrorIfGroupPermissionsAreNotFulfilled() {

		$user = $this->createUser();
		$group = $this->createGroup([], [
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		]);

		_elgg_services()->session->setLoggedInUser($user);

		try {
			$url = elgg_generate_url("collection:object:{$this->getSubtype()}:group", [
				'guid' => $group->guid,
			]);

			$request = BaseTestCase::prepareHttpRequest($url);
			_elgg_services()->setValue('request', $request);

			_elgg_services()->router->route($request);
		} catch (\Exception $ex) {
			_elgg_services()->session->removeLoggedInUser();
			
			throw $ex;
		}

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testViewRouteRespondsOk() {

		$user = $this->createUser();

		$object = $this->createObject([
			'subtype' => $this->getSubtype(),
			'owner_guid' => $user->guid,
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

	public function collectionRoutes() {
		self::createApplication();
		return [
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
						'guid' => $this->createGroup()->guid,
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
	}
}
