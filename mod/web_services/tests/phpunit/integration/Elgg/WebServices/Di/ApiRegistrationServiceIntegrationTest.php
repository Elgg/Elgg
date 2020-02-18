<?php

namespace Elgg\WebServices\Di;

use Elgg\IntegrationTestCase;
use Elgg\WebServices\ApiMethod;

class ApiRegistrationServiceIntegrationTest extends IntegrationTestCase {

	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = ApiRegistrationService::instance();
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
	}
	
	public function testRegisterApiMethod() {
		// service function
		$this->service->registerApiMethod('foo', 'callback');
		
		$method = $this->service->getApiMethod('foo');
		$this->assertInstanceOf(ApiMethod::class, $method);
		
		// lib function
		elgg_ws_expose_function('bar', 'callback2');
		
		$method = $this->service->getApiMethod('bar');
		$this->assertInstanceOf(ApiMethod::class, $method);
	}
	
	public function testUnregisterApiMethod() {
		// service function
		$this->service->registerApiMethod('foo', 'callback');
		
		$method = $this->service->getApiMethod('foo');
		$this->assertInstanceOf(ApiMethod::class, $method);
		
		$this->service->unregisterApiMethod('foo');
		$this->assertEmpty($this->service->getApiMethod('foo'));
		
		// lib function
		elgg_ws_expose_function('bar', 'callback2');
		
		$method = $this->service->getApiMethod('bar');
		$this->assertInstanceOf(ApiMethod::class, $method);
		
		elgg_ws_unexpose_function('bar');
		$this->assertEmpty($this->service->getApiMethod('bar'));
	}
	
	public function testGetAllApiMethods() {
		$preregistered = $this->service->getAllApiMethods();
		
		$this->service->registerApiMethod('foo', 'callback');
		$this->service->registerApiMethod('bar', 'callback2');
		
		$methods = $this->service->getAllApiMethods();
		$this->assertCount(count($preregistered) + 2, $methods);
	}
}
