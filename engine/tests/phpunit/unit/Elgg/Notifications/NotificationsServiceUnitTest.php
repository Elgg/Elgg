<?php

namespace Elgg\Notifications;

use Elgg\UnitTestCase;

class NotificationsServiceUnitTest extends UnitTestCase {

	protected NotificationsService $service;
	
	public function up() {
		$this->service = _elgg_services()->notifications;
	}
	
	public function testRegisterEventDefault() {
		$this->assertFalse($this->service->isRegisteredEvent('object', 'dummy', 'create'));
		
		$this->service->registerEvent('object', 'dummy');
		
		// create event gets registered by default
		$this->assertTrue($this->service->isRegisteredEvent('object', 'dummy', 'create'));
		$this->assertFalse($this->service->isRegisteredEvent('object', 'dummy', 'some'));
		
		// register additional event
		$this->service->registerEvent('object', 'dummy', ['some']);
		
		$this->assertTrue($this->service->isRegisteredEvent('object', 'dummy', 'create'));
		$this->assertTrue($this->service->isRegisteredEvent('object', 'dummy', 'some'));
		
		// unregister all events
		$this->service->unregisterEvent('object', 'dummy');
	}
	
	public function testRegisterEventSpecific() {
		$this->assertFalse($this->service->isRegisteredEvent('object', 'dummy', 'create'));
		$this->assertFalse($this->service->isRegisteredEvent('object', 'dummy', 'some'));
		
		$this->service->registerEvent('object', 'dummy', ['some']);
		
		$this->assertFalse($this->service->isRegisteredEvent('object', 'dummy', 'create'));
		$this->assertTrue($this->service->isRegisteredEvent('object', 'dummy', 'some'));
		
		// register additional event
		$this->service->registerEvent('object', 'dummy', ['some2']);
		
		$this->assertFalse($this->service->isRegisteredEvent('object', 'dummy', 'create'));
		$this->assertTrue($this->service->isRegisteredEvent('object', 'dummy', 'some'));
		$this->assertTrue($this->service->isRegisteredEvent('object', 'dummy', 'some2'));
		
		// unregister all events
		$this->service->unregisterEvent('object', 'dummy');
	}

	public function testRegisterMethod() {
		$this->service->registerMethod('apples');
		$this->assertCount(1, $this->service->getMethods());
		$this->assertTrue($this->service->isRegisteredMethod('apples'));
		$this->assertFalse($this->service->isRegisteredMethod('bananas'));
		
		$this->service->unregisterMethod('apples');
		$this->assertEmpty($this->service->getMethods());
		$this->assertFalse($this->service->isRegisteredMethod('apples'));
	}
	
	public function testRegisterMethods() {
		$this->service->registerMethod('apples');
		$this->assertCount(1, $this->service->getMethods());
		$this->assertTrue($this->service->isRegisteredMethod('apples'));
		$this->assertFalse($this->service->isRegisteredMethod('bananas'));
		
		$this->service->registerMethod('bananas');
		$this->assertCount(2, $this->service->getMethods());
		$this->assertTrue($this->service->isRegisteredMethod('apples'));
		$this->assertTrue($this->service->isRegisteredMethod('bananas'));
		
		$this->service->unregisterMethod('apples');
		$this->assertCount(1, $this->service->getMethods());
		$this->assertFalse($this->service->isRegisteredMethod('apples'));
		$this->assertTrue($this->service->isRegisteredMethod('bananas'));
	}
}
