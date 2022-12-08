<?php

namespace Elgg\Router;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\UnitTestCase;

class RouteRegistrationServiceUnitTest extends UnitTestCase {
	
	/**
	 * @var RouteRegistrationService
	 */
	protected $service;
	
	public function up() {
		$this->service = _elgg_services()->routes;
	}
	
	public function down() {
		unset($this->service);
	}
	
	public function testRegisterRouterWithResource() {
		
		$route = $this->service->register('view:object:blog', [
			'path' => '/blog/view/{guid}/{title?}',
			'resource' => 'blog/view',
		]);
		
		$this->assertInstanceOf(Route::class, $route);
		$this->assertEquals('/blog/view/{guid}/{title}', $route->getPath());
		$this->assertArrayHasKey('_resource', $route->getDefaults());
		$this->assertEquals('blog/view', $route->getDefault('_resource'));
	}
	
	public function testRegisterRouterWithoutPath() {
		$this->expectException(InvalidArgumentException::class);
		$this->service->register('view:object:blog', [
			'resource' => 'blog/view',
		]);
	}
	
	public function testRegisterRouterWithoutControllerParam() {
		$this->expectException(InvalidArgumentException::class);
		$this->service->register('view:object:blog', [
			'path' => '/blog/view/{guid}/{title?}',
		]);
	}
	
	public function testUnregisterRoute() {
		
		// register
		$route = $this->service->register('view:object:blog', [
			'path' => '/blog/view/{guid}/{title?}',
			'resource' => 'blog/view',
		]);
		
		$this->assertInstanceOf(Route::class, $route);
		
		// unregister
		$this->service->unregister('view:object:blog');
		
		$this->assertNull($this->service->get('view:object:blog'));
	}
	
	public function testGetRoute() {
		
		// register
		$route = $this->service->register('view:object:blog', [
			'path' => '/blog/view/{guid}/{title?}',
			'resource' => 'blog/view',
		]);
		
		$this->assertInstanceOf(Route::class, $route);
		
		$this->assertEquals($route, $this->service->get('view:object:blog'));
	}
	
	public function testGenerateUrl() {
		
		// register
		$route = $this->service->register('view:object:blog', [
			'path' => '/blog/view/{guid}/{title?}',
			'resource' => 'blog/view',
		]);
		
		$this->assertInstanceOf(Route::class, $route);
		
		$params = [
			'guid' => 123,
			'title' => 'dummy-title',
		];
		
		$url = $this->service->generateUrl('view:object:blog', $params);
		$this->assertEquals(elgg_normalize_url('/blog/view/123/dummy-title'), $url);
	}
	
	public function testGenerateUrlMissingRouteName() {
		
		$params = [
			'guid' => 123,
			'title' => 'dummy-title',
		];
		
		$url = $this->service->generateUrl('view:object:blog', $params);
		$this->assertNull($url);
	}
	
	public function testGenerateUrlMissingRequiredParameter() {
		
		// register
		$route = $this->service->register('view:object:blog', [
			'path' => '/blog/view/{guid}/{title?}',
			'resource' => 'blog/view',
		]);
		
		$this->assertInstanceOf(Route::class, $route);
		
		$params = [
			'title' => 'dummy-title',
		];
		
		$url = $this->service->generateUrl('view:object:blog', $params);
		$this->assertNull($url);
	}
	
	public function testGenerateUrlIncorrectRequiredParameterType() {
		
		// register
		$route = $this->service->register('view:object:blog', [
			'path' => '/blog/view/{guid}/{title?}',
			'resource' => 'blog/view',
		]);
		
		$this->assertInstanceOf(Route::class, $route);
		
		$params = [
			'guid' => 'abc',
			'title' => 'dummy-title',
		];
		
		$url = $this->service->generateUrl('view:object:blog', $params);
		$this->assertNull($url);
	}
	
	public function testGenerateUrlForDeprecatedRoute() {
		
		// register
		$route = $this->service->register('view:foo:bar', [
			'path' => '/foo/bar',
			'resource' => 'foo/bar',
			'deprecated' => '3.1',
		]);
		
		$this->assertInstanceOf(Route::class, $route);
		
		_elgg_services()->logger->disable();
		$this->service->generateUrl('view:foo:bar');
		$logged = _elgg_services()->logger->enable();
		
		$this->assertCount(1, $logged);
		
		$message_details = $logged[0];
		
		$this->assertStringContainsString('The route "view:foo:bar" has been deprecated.', $message_details['message']);
	}
	
	/**
	 * @dataProvider validUsernameProvider
	 */
	public function testGenerateUrlWithValidUsername($username) {
		$this->service->register('valid:username', [
			'path' => '/valid/{username}',
			'resource' => 'dummy',
		]);
		
		$url = $this->service->generateUrl('valid:username', [
			'username' => $username,
		]);
		$this->assertIsString($url);
	}
	
	/**
	 * @see \Elgg\Users\AccountsServiceUnitTest::validUsernameProvider()
	 *
	 * @return array
	 */
	public function validUsernameProvider() {
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
	
	/**
	 * @dataProvider invalidUsernameProvider
	 */
	public function testGenerateUrlWithInvalidUsername($username) {
		$this->service->register('invalid:username', [
			'path' => '/invalid/{username}',
			'resource' => 'dummy',
		]);
		
		$url = $this->service->generateUrl('invalid:username', [
			'username' => $username,
		]);
		$this->assertNull($url);
	}
	
	/**
	 * @see \Elgg\Users\AccountsServiceUnitTest::invalidUsernameProvider()
	 *
	 * @return array
	 */
	public function invalidUsernameProvider() {
		return [
			['username#'],
			['username@'],
			['Username™'],
		];
	}
}
