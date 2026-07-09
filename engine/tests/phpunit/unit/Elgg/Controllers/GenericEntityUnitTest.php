<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\HttpException;
use Elgg\Helpers\Controllers\GenericEntityTesting;

class GenericEntityUnitTest extends GenericContentUnitTestCase {
	
	protected function getControllerClass(): string {
		return GenericEntityTesting::class;
	}
	
	public static function invalidRouteProvider(): array {
		return [
			['collection:object:foo:all'],
			['collection:object:foo:group'],
			['collection:object:foo:owner'],
			['collection:object:foo:friends'],
			['default:object:foo'],
		];
	}
	
	public static function validRouteProvider(): array {
		return [
			['add:object:foo'],
			['edit:object:foo'],
			['view:object:foo'],
		];
	}
	
	public static function pageRouteProvider(): array {
		return [
			['add:object:foo', 'add'],
			['edit:object:foo', 'edit'],
			['view:object:foo', 'view'],
		];
	}
	
	public function testGetEntityWithInvalidType() {
		$owner = $this->createUser();
		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $owner->guid,
		]);
		
		$http_request = $this->prepareHttpRequest("/foo/bar/{$object->guid}");
		_elgg_services()->routes->register("view:{$object->getType()}1:{$object->getSubtype()}", [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$this->expectException(EntityNotFoundException::class);
		$this->invokeInaccessableMethod($controller, 'getEntity');
	}
	
	public function testGetEntityWithInvalidSubtype() {
		$owner = $this->createUser();
		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $owner->guid,
		]);
		
		$http_request = $this->prepareHttpRequest("/foo/bar/{$object->guid}");
		_elgg_services()->routes->register("view:{$object->getType()}:{$object->getSubtype()}1", [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$this->expectException(EntityNotFoundException::class);
		$this->invokeInaccessableMethod($controller, 'getEntity');
	}
	
	public function testGetEntityWithProtectedEntity() {
		$owner = $this->createUser();
		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'owner_guid' => $owner->guid,
		]);
		
		$http_request = $this->prepareHttpRequest("/foo/bar/{$object->guid}");
		_elgg_services()->routes->register("view:{$object->getType()}:{$object->getSubtype()}", [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$this->expectException(EntityPermissionsException::class);
		$this->invokeInaccessableMethod($controller, 'getEntity');
	}
	
	public function testGetEntityViewer() {
		$owner = $this->createUser();
		$viewer = $this->createUser();
		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'owner_guid' => $owner->guid,
		]);
		
		$http_request = $this->prepareHttpRequest("/foo/bar/{$object->guid}");
		_elgg_services()->routes->register("view:{$object->getType()}:{$object->getSubtype()}", [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		_elgg_services()->session_manager->setLoggedInUser($viewer);
		
		$entity = $this->invokeInaccessableMethod($controller, 'getEntity');
		$this->assertEquals($object, $entity);
	}
	
	public function testGetEntityViewerCantEdit() {
		$owner = $this->createUser();
		$viewer = $this->createUser();
		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'owner_guid' => $owner->guid,
		]);
		
		$http_request = $this->prepareHttpRequest("/foo/bar/{$object->guid}");
		_elgg_services()->routes->register("view:{$object->getType()}:{$object->getSubtype()}", [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		_elgg_services()->session_manager->setLoggedInUser($viewer);
		
		$this->expectException(EntityPermissionsException::class);
		$this->invokeInaccessableMethod($controller, 'getEntity', true);
	}
	
	public function testGetEntityOwnerCanEdit() {
		$owner = $this->createUser();
		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'owner_guid' => $owner->guid,
		]);
		
		$http_request = $this->prepareHttpRequest("/foo/bar/{$object->guid}");
		_elgg_services()->routes->register("view:{$object->getType()}:{$object->getSubtype()}", [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		_elgg_services()->session_manager->setLoggedInUser($owner);
		
		$entity = $this->invokeInaccessableMethod($controller, 'getEntity');
		$this->assertEquals($object, $entity);
	}
	
	public function testGetPageOptionsFilterID() {
		$owner = $this->createUser();
		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $owner->guid,
		]);
		
		$http_request = $this->prepareHttpRequest("/foo/bar/{$object->guid}");
		_elgg_services()->routes->register("view:{$object->getType()}:{$object->getSubtype()}", [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		// default
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'add', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_id', $options);
		$this->assertEquals("{$object->getSubtype()}/edit", $options['filter_id']);
		
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'edit', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_id', $options);
		$this->assertEquals("{$object->getSubtype()}/edit", $options['filter_id']);
		
		// view
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'view', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_id', $options);
		$this->assertEquals("{$object->getSubtype()}/view", $options['filter_id']);
		
		// provided
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'view', [
			'filter_id' => 'provided',
		]);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_id', $options);
		$this->assertEquals('provided', $options['filter_id']);
	}
	
	public function testGetPageOptionsTitle() {
		$owner = $this->createUser();
		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $owner->guid,
		]);
		
		$http_request = $this->prepareHttpRequest("/foo/bar/{$object->guid}");
		_elgg_services()->routes->register("view:{$object->getType()}:{$object->getSubtype()}", [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		// default
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'add', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('title', $options);
		$this->assertEquals(elgg_echo("add:{$object->getType()}:{$object->getSubtype()}"), $options['title']);
		
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'edit', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('title', $options);
		$this->assertEquals(elgg_echo("edit:{$object->getType()}:{$object->getSubtype()}"), $options['title']);
		
		// view
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'view', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('title', $options);
		$this->assertEquals($object->getDisplayName(), $options['title']);
		
		// provided
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'view', [
			'title' => 'provided',
		]);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('title', $options);
		$this->assertEquals('provided', $options['title']);
	}
}
