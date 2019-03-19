<?php

namespace Elgg\Plugins;

use Elgg\BaseTestCase;
use Elgg\IntegrationTestCase;
use Elgg\UnitTestCase;

/**
 * @group Router
 */
abstract class RouteResponseTest extends UnitTestCase {

	use PluginTesting;

	public function up() {
		$this->startPlugin();
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

		if ($ex) {
			throw $ex;
		}
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

		if ($ex) {
			throw $ex;
		}
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
	 * @expectedException \Elgg\EntityPermissionsException
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
	 * @expectedException \Elgg\EntityNotFoundException
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
	 * @expectedException \Elgg\GroupGatekeeperException
	 */
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

		if ($ex) {
			throw $ex;
		}
	}

	/**
	 * @expectedException \Elgg\GroupGatekeeperException
	 */
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

		if ($ex) {
			throw $ex;
		}
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
	}
}
