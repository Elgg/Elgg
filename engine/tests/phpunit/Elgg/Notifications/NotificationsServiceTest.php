<?php

// the Event class has a dependency on elgg_instance_of() and get_entity()
$engine = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once "$engine/lib/entities.php";

class Elgg_Notifications_NotificationsServiceTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->hooks = new Elgg_PluginHooksService();
		$this->queue = new Elgg_Queue_MemoryQueue();
		$dbMock = $this->getMockBuilder('Elgg_Database')
			->disableOriginalConstructor()
			->getMock();
		$this->sub = new Elgg_Notifications_SubscriptionsService($dbMock);
		$this->access = new Elgg_Access();

		// Event class has dependency on elgg_get_logged_in_user_guid()
		_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
	}

	public function testRegisterEvent() {
		$service = new Elgg_Notifications_NotificationsService($this->sub, $this->queue, $this->hooks, $this->access);

		$service->registerEvent('foo', 'bar');
		$events = array(
			'foo' => array(
				'bar' => array('create')
			)
		);
		$this->assertEquals($events, $service->getEvents());

		$service->registerEvent('foo', 'bar', array('test'));
		$events['foo']['bar'] = array('create', 'test');
		$this->assertEquals($events, $service->getEvents());

		$service->registerEvent('foo', 'bar');
		$this->assertEquals($events, $service->getEvents());
	}

	public function testUnregisterEvent() {
		$service = new Elgg_Notifications_NotificationsService($this->sub, $this->queue, $this->hooks, $this->access);

		$service->registerEvent('foo', 'bar');
		$this->assertTrue($service->unregisterEvent('foo', 'bar'));

		$events = array(
			'foo' => array()
		);
		$this->assertEquals($events, $service->getEvents());
		$this->assertFalse($service->unregisterEvent('foo', 'bar'));
	}

	public function testRegisterMethod() {
		$service = new Elgg_Notifications_NotificationsService($this->sub, $this->queue, $this->hooks, $this->access);

		$service->registerMethod('foo');
		$methods = array('foo' => 'foo');
		$this->assertEquals($methods, $service->getMethods());
	}

	public function testUnregisterMethod() {
		$service = new Elgg_Notifications_NotificationsService($this->sub, $this->queue, $this->hooks, $this->access);

		$service->registerMethod('foo');
		$this->assertTrue($service->unregisterMethod('foo'));
		$this->assertEquals(array(), $service->getMethods());
		$this->assertFalse($service->unregisterMethod('foo'));
	}

	public function testEnqueueEvent() {
		$service = new Elgg_Notifications_NotificationsService($this->sub, $this->queue, $this->hooks, $this->access);

		$service->registerEvent('object', 'bar');
		$object = new ElggObject();
		$object->subtype = 'bar';
		$service->enqueueEvent('create', 'object', $object);
		$event = new Elgg_Notifications_Event($object, 'create');
		$this->assertEquals($event, $this->queue->dequeue());
		$this->assertNull($this->queue->dequeue());

		// unregistered action type
		$service->enqueueEvent('null', 'object', $object);
		$this->assertNull($this->queue->dequeue());

		// unregistered object type
		$service->enqueueEvent('create', 'object', new ElggObject());
		$this->assertNull($this->queue->dequeue());
	}

	public function testEnqueueEventHook() {
		$object = new ElggObject();
		$object->subtype = 'bar';
		$params = array('action' => 'create', 'object' => $object);

		$mock = $this->getMock('Elgg_PluginHooksService', array('trigger'));
		$mock->expects($this->once())
			->method('trigger')
			->with('enqueue', 'notification', $params, true);
		$service = new Elgg_Notifications_NotificationsService($this->sub, $this->queue, $mock, $this->access);
		$service->registerEvent('object', 'bar');
		$service->enqueueEvent('create', 'object', $object);
	}

	public function testStoppingEnqueueEvent() {
		$mock = $this->getMock('Elgg_PluginHooksService', array('trigger'));
		$mock->expects($this->once())
			->method('trigger')
			->will($this->returnValue(false));
		$service = new Elgg_Notifications_NotificationsService($this->sub, $this->queue, $mock, $this->access);

		$service->registerEvent('object', 'bar');
		$object = new ElggObject();
		$object->subtype = 'bar';
		$service->enqueueEvent('create', 'object', $object);
		$this->assertNull($this->queue->dequeue());
	}

	public function testProcessQueueNoEvents() {
		$service = new Elgg_Notifications_NotificationsService($this->sub, $this->queue, $this->hooks, $this->access);
		$this->assertEquals(0, $service->processQueue(time() + 10));
	}

	public function testProcessQueueThreeEvents() {
		$mock = $this->getMock(
				'Elgg_Notifications_SubscriptionsService',
				array('getSubscriptions'),
				array(),
				'',
				false);
		$mock->expects($this->exactly(3))
			->method('getSubscriptions')
			->will($this->returnValue(array()));
		$service = new Elgg_Notifications_NotificationsService($mock, $this->queue, $this->hooks, $this->access);

		$service->registerEvent('object', 'bar');
		$object = new ElggObject();
		$object->subtype = 'bar';
		$service->enqueueEvent('create', 'object', $object);
		$service->enqueueEvent('create', 'object', $object);
		$service->enqueueEvent('create', 'object', $object);

		$this->assertEquals(3, $service->processQueue(time() + 10));
	}

	public function testProcessQueueTimesout() {
		$mock = $this->getMock(
				'Elgg_Notifications_SubscriptionsService',
				array('getSubscriptions'),
				array(),
				'',
				false);
		$mock->expects($this->exactly(0))
			->method('getSubscriptions');
		$service = new Elgg_Notifications_NotificationsService($mock, $this->queue, $this->hooks, $this->access);

		$service->registerEvent('object', 'bar');
		$object = new ElggObject();
		$object->subtype = 'bar';
		$service->enqueueEvent('create', 'object', $object);
		$service->enqueueEvent('create', 'object', $object);
		$service->enqueueEvent('create', 'object', $object);

		$this->assertEquals(0, $service->processQueue(time()));
	}
}
