<?php

namespace Elgg\Controllers;

use Elgg\Helpers\Controllers\GenericContentListingTesting;

class GenericContentListingUnitTest extends GenericContentUnitTestCase {
	
	protected function getControllerClass(): string {
		return GenericContentListingTesting::class;
	}
	
	public static function invalidRouteProvider(): array {
		return [
			['add:object:foo'],
			['edit:object:foo'],
			['view:object:foo'],
		];
	}
	
	public static function validRouteProvider(): array {
		return [
			['collection:object:foo:all'],
			['collection:object:foo:group'],
			['collection:object:foo:owner'],
			['collection:object:foo:friends'],
			['default:object:foo'],
		];
	}
	
	public static function pageRouteProvider(): array {
		return [
			['collection:object:foo:all', 'all'],
			['collection:object:foo:group', 'group'],
			['collection:object:foo:owner', 'owner'],
			['collection:object:foo:friends', 'friends'],
			['default:object:foo', 'all'],
		];
	}
	
	public function testGetGroupToolOptionEmpty() {
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register('collection:object:bar:all', [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$this->assertEmpty($this->invokeInaccessableMethod($controller, 'getGroupToolOption'));
	}
	
	public function testGetGroupToolOption() {
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register('collection:object:bar:all', [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
			'options' => [
				'group_tool' => 'bar',
			],
		]);
		$controller = $this->prepareController($http_request);
		
		$this->assertEquals('bar', $this->invokeInaccessableMethod($controller, 'getGroupToolOption'));
	}
	
	public function testGetPageOptionsGroupFilterID() {
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register('collection:object:bar:group', [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		// default
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'group', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_id', $options);
		$this->assertEquals('bar/group', $options['filter_id']);
		
		// not the group page
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'all', []);
		$this->assertIsArray($options);
		$this->assertArrayNotHasKey('filter_id', $options);
		
		// provided
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'group', [
			'filter_id' => 'provided',
		]);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_id', $options);
		$this->assertEquals('provided', $options['filter_id']);
	}
	
	public function testGetPageOptionsFilterValue() {
		$owner = $this->createUser();
		$viewer = $this->createUser();
		
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register('collection:object:bar:group', [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		/** @var GenericContentListingTesting $controller */
		$controller = $this->prepareController($http_request);
		
		// default
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'all', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_value', $options);
		$this->assertEquals('all', $options['filter_value']);
		
		// owner viewing own page
		_elgg_services()->session_manager->setLoggedInUser($viewer);
		$controller->setPageOwner($viewer);
		
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'owner', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_value', $options);
		$this->assertEquals('mine', $options['filter_value']);
		
		// owner viewing other page
		$controller->setPageOwner($owner);
		
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'owner', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_value', $options);
		$this->assertEquals('none', $options['filter_value']);
		
		// friends viewing own page
		$controller->setPageOwner($viewer);
		
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'friends', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_value', $options);
		$this->assertEquals('friends', $options['filter_value']);
		
		// owner viewing other page
		$controller->setPageOwner($owner);
		
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'friends', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_value', $options);
		$this->assertEquals('none', $options['filter_value']);
		
		// provided
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'owner', [
			'filter_value' => 'provided',
		]);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('filter_value', $options);
		$this->assertEquals('provided', $options['filter_value']);
	}
	
	public function testGetPageOptionsTitle() {
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register('collection:object:bar:group', [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		/** @var GenericContentListingTesting $controller */
		$controller = $this->prepareController($http_request);
		
		// default title
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'all', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('title', $options);
		$this->assertEquals(elgg_echo('collection:object:bar:all'), $options['title']);
		
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'group', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('title', $options);
		$this->assertEquals(elgg_echo('collection:object:bar:group'), $options['title']);
		
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'friends', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('title', $options);
		$this->assertEquals(elgg_echo('collection:object:bar:friends'), $options['title']);
		
		// owner
		$owner = $this->createUser();
		$controller->setPageOwner($owner);
		
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'owner', []);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('title', $options);
		$this->assertEquals(elgg_echo('collection:object:bar:owner', [$owner->getDisplayName()]), $options['title']);
		
		// provided
		$options = $this->invokeInaccessableMethod($controller, 'getPageOptions', 'owner', [
			'title' => 'provided',
		]);
		$this->assertIsArray($options);
		$this->assertArrayHasKey('title', $options);
		$this->assertEquals('provided', $options['title']);
	}
}
