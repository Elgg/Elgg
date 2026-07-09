<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\ValidationException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Router\Route;
use Elgg\UnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

abstract class GenericContentUnitTestCase extends UnitTestCase {
	
	protected function prepareController(\Elgg\Http\Request $http_request): GenericContent {
		try {
			$route_info = _elgg_services()->urlMatcher->matchRequest($http_request);
			$route = _elgg_services()->routes->get($route_info['_route']);
			$route->setMatchedParameters($route_info);
			
			if ($route instanceof Route) {
				$http_request->setRoute($route);
			}
		} catch(ResourceNotFoundException $e) {
			// do nothing
		}
		
		$request = new \Elgg\Request(elgg(), $http_request);
		
		$class = $this->getControllerClass();
		if (!is_a($class, GenericContent::class, true)) {
			throw new InvalidArgumentException("{$class} needs to extend \Elgg\Helpers\Controllers\GenericContentListingTesting or \Elgg\Helpers\Controllers\GenericEntityTesting");
		}
		
		return new $class($request);
	}
	
	abstract protected function getControllerClass(): string;
	
	public function testGetRoutePartsNoRoute() {
		$http_request = $this->prepareHttpRequest('/foo/bar');
		$controller = $this->prepareController($http_request);
		
		$this->expectException(ValidationException::class);
		$this->expectExceptionMessage('Missing route name');
		$this->invokeInaccessableMethod($controller, 'getRouteParts');
	}
	
	public function testGetRoutePartsShortRouteName() {
		$http_request = $this->prepareHttpRequest('/foo/bar');
		_elgg_services()->routes->register('foo:bar', [
			'path' => '/foo/bar',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$this->expectException(ValidationException::class);
		$this->expectExceptionMessage('Unsupported route name configuration');
		$this->invokeInaccessableMethod($controller, 'getRouteParts');
	}
	
	public function testGetRouteParts() {
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register('view:object:bar', [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$parts = $this->invokeInaccessableMethod($controller, 'getRouteParts');
		$this->assertIsArray($parts);
		$this->assertEquals(['view', 'object', 'bar'], $parts);
	}

	#[DataProvider('invalidRouteProvider')]
	public function testAssertInvalidRoute(string $invalid_route_name) {
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register($invalid_route_name, [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$this->expectException(ValidationException::class);
		$this->invokeInaccessableMethod($controller, 'assertValidRoute');
	}
	
	abstract public static function invalidRouteProvider(): array;
	
	#[DataProvider('validRouteProvider')]
	public function testAssertValidRoute(string $valid_route_name) {
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register($valid_route_name, [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$this->assertNull($this->invokeInaccessableMethod($controller, 'assertValidRoute'));
	}
	
	abstract public static function validRouteProvider(): array;
	
	#[DataProvider('validRouteProvider')]
	public function testGetEntityType(string $valid_route_name) {
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register($valid_route_name, [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$parsed_route = explode(':', $valid_route_name);
		
		$this->assertEquals($parsed_route[1], $this->invokeInaccessableMethod($controller, 'getEntityType'));
	}
	
	#[DataProvider('validRouteProvider')]
	public function testGetEntitySubtype(string $valid_route_name) {
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register($valid_route_name, [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$parsed_route = explode(':', $valid_route_name);
		
		$this->assertEquals($parsed_route[2], $this->invokeInaccessableMethod($controller, 'getEntitySubtype'));
	}
	
	#[DataProvider('pageRouteProvider')]
	public function testGetPage(string $route_name, string $expected_page) {
		$http_request = $this->prepareHttpRequest('/foo/bar/1234');
		_elgg_services()->routes->register($route_name, [
			'path' => '/foo/bar/{guid}',
			'controller' => $this->getControllerClass(),
		]);
		$controller = $this->prepareController($http_request);
		
		$this->assertEquals($expected_page, $this->invokeInaccessableMethod($controller, 'getPage'));
	}
	
	abstract public static function pageRouteProvider(): array;
}
