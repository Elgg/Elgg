<?php

namespace Elgg;

use Elgg\Exceptions\AuthenticationException;
use Elgg\Exceptions\Exception;

class AuthenticationServiceUnitTest extends UnitTestCase {
	
	/**
	 * @var AuthenticationService
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = new AuthenticationService(_elgg_services()->handlers);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		unset($this->service);
	}
	
	public function testRegisterHandler() {
		$handlers = $this->getInaccessableProperty($this->service, 'handlers');
		
		$this->assertIsArray($handlers);
		$this->assertEmpty($handlers);
		
		$this->assertTrue($this->service->registerHandler('foo'));
		
		$handlers = $this->getInaccessableProperty($this->service, 'handlers');
		
		$this->assertIsArray($handlers);
		$this->assertArrayHasKey('user', $handlers);
		$this->assertCount(1, $handlers['user']);
		$this->assertArrayHasKey('foo', $handlers['user']);
		$this->assertEquals([
			'handler' => 'foo',
			'importance' => 'sufficient',
		], $handlers['user']['foo']);
		
		// reregister the same callback, should overrule
		$this->assertTrue($this->service->registerHandler('foo', 'required'));
		
		$handlers = $this->getInaccessableProperty($this->service, 'handlers');
		
		$this->assertIsArray($handlers);
		$this->assertArrayHasKey('user', $handlers);
		$this->assertCount(1, $handlers['user']);
		$this->assertArrayHasKey('foo', $handlers['user']);
		$this->assertEquals([
			'handler' => 'foo',
			'importance' => 'required',
		], $handlers['user']['foo']);
	}
	
	public function testUnregisterHandler() {
		$handlers = $this->getInaccessableProperty($this->service, 'handlers');
		
		$this->assertIsArray($handlers);
		$this->assertEmpty($handlers);
		
		$this->assertTrue($this->service->registerHandler('foo'));
		
		$handlers = $this->getInaccessableProperty($this->service, 'handlers');
		
		$this->assertArrayHasKey('user', $handlers);
		$this->assertNotEmpty($handlers['user']);
		
		$this->assertNull($this->service->unregisterHandler('foo'));
		
		$handlers = $this->getInaccessableProperty($this->service, 'handlers');
		
		$this->assertArrayHasKey('user', $handlers);
		$this->assertEmpty($handlers['user']);
	}
	
	public function testAuthenticateWithUnknownPolicy() {
		$this->assertFalse($this->service->authenticate('foo'));
	}
	
	public function testAuthenticateWithNonCallable() {
		_elgg_services()->logger->disable();
		
		$this->assertTrue($this->service->registerHandler('foo', 'sufficient', 'bar'));
		
		$this->assertFalse($this->service->authenticate('bar'));
		
		$logs = _elgg_services()->logger->enable();
		$this->assertNotEmpty($logs);
	}
	
	public function testAuthenticateWithParams() {
		$params = [
			'foo' => 'bar',
			'bar' => 'foo',
		];
		
		$this->assertTrue($this->service->registerHandler(function($provided_params) use ($params) {
			$this->assertEquals($params, $provided_params);
			
			return true;
		}, 'sufficient', 'bar'));
		
		$this->assertTrue($this->service->authenticate('bar', $params));
	}
	
	public function testAuthenticateMultipleSufficient() {
		$this->assertTrue($this->service->registerHandler(function() {
			return true;
		}, 'sufficient', 'bar'));
		$this->assertTrue($this->service->registerHandler(function() {
			return false;
		}, 'sufficient', 'bar'));
		
		$this->assertTrue($this->service->authenticate('bar'));
	}
	
	public function testAuthenticateSufficientRequired() {
		$this->assertTrue($this->service->registerHandler(function() {
			return true;
		}, 'sufficient', 'bar'));
		$this->assertTrue($this->service->registerHandler(function() {
			return false;
		}, 'required', 'bar'));
		
		$this->assertFalse($this->service->authenticate('bar'));
	}
	
	public function testAuthenticateMultipleRequired() {
		$this->assertTrue($this->service->registerHandler(function() {
			return true;
		}, 'required', 'bar'));
		$this->assertTrue($this->service->registerHandler(function() {
			return false;
		}, 'required', 'bar'));
		
		$this->assertFalse($this->service->authenticate('bar'));
	}
	
	public function testAuthenticateSufficientWithException() {
		$this->assertTrue($this->service->registerHandler(function() {
			throw new Exception('this was required');
		}, 'sufficient', 'bar'));
		
		$this->expectException(AuthenticationException::class);
		$this->expectExceptionMessage('this was required');
		$this->service->authenticate('bar');
	}
	
	public function testAuthenticateMultipleSufficientWithOneException() {
		$this->assertTrue($this->service->registerHandler(function() {
			throw new Exception('this was required');
		}, 'sufficient', 'bar'));
		$this->assertTrue($this->service->registerHandler(function() {
			return true;
		}, 'sufficient', 'bar'));
		
		$this->assertTrue($this->service->authenticate('bar'));
	}
	
	public function testAuthenticateRequiredWithException() {
		$this->assertTrue($this->service->registerHandler(function() {
			throw new Exception('this was required');
		}, 'required', 'bar'));
		
		$this->expectException(AuthenticationException::class);
		$this->expectExceptionMessage('this was required');
		$this->service->authenticate('bar');
	}
}
