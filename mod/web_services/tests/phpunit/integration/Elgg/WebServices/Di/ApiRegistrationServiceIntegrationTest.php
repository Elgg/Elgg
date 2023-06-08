<?php

namespace Elgg\WebServices\Di;

use Elgg\Plugins\IntegrationTestCase;
use Elgg\WebServices\ApiMethod;

class ApiRegistrationServiceIntegrationTest extends IntegrationTestCase {

	protected ApiRegistrationService $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = ApiRegistrationService::instance();
	}
	
	public function down() {
		ApiRegistrationCollection::instance()->fill([]);
	}
	
	public function testRegisterApiMethod() {
		// service function
		$this->service->registerApiMethod(new ApiMethod('foo', 'callback'));
		
		$method = $this->service->getApiMethod('foo');
		$this->assertInstanceOf(ApiMethod::class, $method);
		
	}
	
	public function testRegisterApiMethodWithDifferentCallMethod() {
		$this->service->registerApiMethod(ApiMethod::factory([
			'method' => 'foo',
			'callback' => 'callback',
			'call_method' => 'GET',
		]));
		$this->service->registerApiMethod(ApiMethod::factory([
			'method' => 'foo',
			'callback' => 'callback',
			'call_method' => 'POST',
		]));
		
		$method = $this->service->getApiMethod('foo');
		$this->assertInstanceOf(ApiMethod::class, $method);
		$this->assertEquals('GET', $method->call_method);
		
		$method = $this->service->getApiMethod('foo', 'POST');
		$this->assertInstanceOf(ApiMethod::class, $method);
		$this->assertEquals('POST', $method->call_method);
	}
	
	public function testUnregisterApiMethod() {
		// service function
		$this->service->registerApiMethod(ApiMethod::factory([
			'method' => 'foo',
			'callback' => 'callback',
			'call_method' => 'POST',
		]));
		
		$method = $this->service->getApiMethod('foo', 'POST');
		$this->assertInstanceOf(ApiMethod::class, $method);
		$this->assertEmpty($this->service->getApiMethod('foo', 'GET'));
		
		$this->service->unregisterApiMethod('foo'); // wrong http request method
		$this->assertNotEmpty($this->service->getApiMethod('foo', 'POST'));
		
		$this->service->unregisterApiMethod('foo', 'POST'); // correct http request method
		$this->assertEmpty($this->service->getApiMethod('foo', 'POST'));
		
		// lib function
		$this->service->registerApiMethod(ApiMethod::factory([
			'method' => 'bar',
			'callback' => 'callback',
			'call_method' => 'POST',
		]));
		
		$method = $this->service->getApiMethod('bar', 'POST');
		$this->assertInstanceOf(ApiMethod::class, $method);
		$this->assertEmpty($this->service->getApiMethod('bar', 'GET'));
		
		elgg_ws_unexpose_function('bar'); // wrong http request method
		$this->assertNotEmpty($this->service->getApiMethod('bar', 'POST'));
		elgg_ws_unexpose_function('bar', 'POST'); // correct http request method
		$this->assertEmpty($this->service->getApiMethod('bar', 'POST'));
	}
	
	public function testGetAllApiMethods() {
		$preregistered = $this->service->getAllApiMethods();
		
		$this->service->registerApiMethod(new ApiMethod('foo', 'callback'));
		$this->service->registerApiMethod(new ApiMethod('bar', 'callback2'));
		
		$methods = $this->service->getAllApiMethods();
		$this->assertCount(count($preregistered) + 2, $methods);
	}
}
