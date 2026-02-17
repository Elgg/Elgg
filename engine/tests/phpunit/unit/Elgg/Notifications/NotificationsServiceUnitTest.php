<?php

namespace Elgg\Notifications;

use Elgg\Helpers\Notifications\TestNotificationHandler;
use Elgg\UnitTestCase;

class NotificationsServiceUnitTest extends UnitTestCase {

	protected NotificationsService $service;
	
	public function up() {
		$this->service = _elgg_services()->notifications;
	}
	
	public function down() {
		_elgg_services()->reset('notifications');
	}
	
	protected function isRegisteredEvent(string $type, string $subtype, string $action): bool {
		$events = $this->service->getEvents();
		
		return isset($events[$type][$subtype][$action]);
	}
	
	public function testRegisterEventDefault() {
		$this->assertFalse($this->isRegisteredEvent('object', 'dummy', 'create'));
		
		$this->service->registerEvent('object', 'dummy');
		
		// create event gets registered by default
		$this->assertTrue($this->isRegisteredEvent('object', 'dummy', 'create'));
		$this->assertFalse($this->isRegisteredEvent('object', 'dummy', 'some'));
		
		// register additional event
		$this->service->registerEvent('object', 'dummy', 'some');
		
		$this->assertTrue($this->isRegisteredEvent('object', 'dummy', 'create'));
		$this->assertTrue($this->isRegisteredEvent('object', 'dummy', 'some'));
		
		// unregister all events
		$this->service->unregisterEvent('object', 'dummy');
	}
	
	public function testRegisterEventSpecific() {
		$this->assertFalse($this->isRegisteredEvent('object', 'dummy', 'create'));
		$this->assertFalse($this->isRegisteredEvent('object', 'dummy', 'some'));
		
		$this->service->registerEvent('object', 'dummy', 'some');
		
		$this->assertFalse($this->isRegisteredEvent('object', 'dummy', 'create'));
		$this->assertTrue($this->isRegisteredEvent('object', 'dummy', 'some'));
		
		// register additional event
		$this->service->registerEvent('object', 'dummy', 'some2');
		
		$this->assertFalse($this->isRegisteredEvent('object', 'dummy', 'create'));
		$this->assertTrue($this->isRegisteredEvent('object', 'dummy', 'some'));
		$this->assertTrue($this->isRegisteredEvent('object', 'dummy', 'some2'));
		
		// unregister all events
		$this->service->unregisterEvent('object', 'dummy');
	}
	
	public function testRegisterMultipleHandlerOnSameEventWithSameHandler() {
		$this->assertFalse($this->isRegisteredEvent('object', 'dummy', 'create'));
		
		$this->service->registerEvent('object', 'dummy');
		$this->service->registerEvent('object', 'dummy');
		
		$this->assertTrue($this->isRegisteredEvent('object', 'dummy', 'create'));
		
		$events = $this->service->getEvents();
		$this->assertCount(1, $events['object']['dummy']['create']);
	}

	public function testRegisterMultipleHandlerOnSameEventWithDifferentHandlers() {
		$this->assertFalse($this->isRegisteredEvent('object', 'dummy', 'create'));
		
		$this->service->registerEvent('object', 'dummy');
		$this->service->registerEvent('object', 'dummy', 'create', TestNotificationHandler::class);
		
		$this->assertTrue($this->isRegisteredEvent('object', 'dummy', 'create'));
		
		$events = $this->service->getEvents();
		$this->assertCount(2, $events['object']['dummy']['create']);
	}
	
	public function testGetSubscriptionHandlers() {
		// not a subscription event handler
		$this->service->registerEvent('object', 'dummy', 'create', TestNotificationHandler::class);
		
		$handlers = $this->invokeInaccessableMethod($this->service, 'getSubscriptionHandlers', 'object', 'dummy', 'create');
		
		$this->assertEmpty($handlers);
		
		// register the subscription event handler
		$this->service->registerEvent('object', 'dummy');
		
		$handlers = $this->invokeInaccessableMethod($this->service, 'getSubscriptionHandlers', 'object', 'dummy', 'create');
		
		$this->assertCount(1, $handlers);
		$this->assertContains(NotificationEventHandler::class, $handlers);
	}
	
	public function testGetInstantHandlers() {
		$this->assertFalse($this->isRegisteredEvent('object', 'dummy', 'create'));
		
		// check the default instant notification handler (no events)
		$handlers = $this->invokeInaccessableMethod($this->service, 'getInstantHandlers', 'object', 'dummy', 'create');
		
		$this->assertCount(1, $handlers);
		$this->assertContains(InstantNotificationEventHandler::class, $handlers);
		
		// check the default instant notification handler (only subscription events)
		$this->service->registerEvent('object', 'dummy');
		$handlers = $this->invokeInaccessableMethod($this->service, 'getInstantHandlers', 'object', 'dummy', 'create');
		
		$this->assertCount(1, $handlers);
		$this->assertContains(InstantNotificationEventHandler::class, $handlers);
		
		// register the custom instant event handler
		$this->service->registerEvent('object', 'dummy', 'create', TestNotificationHandler::class);
		
		$handlers = $this->invokeInaccessableMethod($this->service, 'getInstantHandlers', 'object', 'dummy', 'create');
		
		$this->assertCount(1, $handlers);
		$this->assertContains(TestNotificationHandler::class, $handlers);
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
