<?php

namespace Elgg\Notifications;

use Elgg\UnitTestCase;

class NotificationsServiceUnitTest extends UnitTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->createApplication();
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
	}
	
	public function testRegisterNotificationMethod() {
		$service = _elgg_services()->notifications;
		
		$service->registerMethod('apples');
		$this->assertCount(1, $service->getMethods());
		$this->assertTrue($service->isRegisteredMethod('apples'));
		$this->assertFalse($service->isRegisteredMethod('bananas'));
		
		$this->assertTrue($service->unregisterMethod('apples'));
		$this->assertEmpty($service->getMethods());
		$this->assertFalse($service->isRegisteredMethod('apples'));
		
		$this->assertFalse($service->unregisterMethod('apples'));
		$this->assertFalse($service->unregisterMethod('bananas'));
	}
	
	public function testRegisterNotificationMethods() {
		$service = _elgg_services()->notifications;
		
		$service->registerMethod('apples');
		$this->assertCount(1, $service->getMethods());
		$this->assertTrue($service->isRegisteredMethod('apples'));
		$this->assertFalse($service->isRegisteredMethod('bananas'));
		
		$service->registerMethod('bananas');
		$this->assertCount(2, $service->getMethods());
		$this->assertTrue($service->isRegisteredMethod('apples'));
		$this->assertTrue($service->isRegisteredMethod('bananas'));
		
		$this->assertTrue($service->unregisterMethod('apples'));
		$this->assertCount(1, $service->getMethods());
		$this->assertFalse($service->isRegisteredMethod('apples'));
		$this->assertTrue($service->isRegisteredMethod('bananas'));
	}
}
