<?php

$engine = dirname(dirname(dirname(__FILE__)));
require_once "$engine/lib/entities.php";

class ElggNotificationsServiceTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->hooks = new Elgg_PluginHookService();
		$this->queue = new Elgg_Util_MemoryQueue();
	}

	public function testRegisterEvent() {
		$service = new Elgg_Notifications_Service($this->queue, $this->hooks);

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
		$service = new Elgg_Notifications_Service($this->queue, $this->hooks);

		$service->registerEvent('foo', 'bar');
		$this->assertTrue($service->unregisterEvent('foo', 'bar'));

		$events = array(
			'foo' => array()
		);
		$this->assertEquals($events, $service->getEvents());
		$this->assertFalse($service->unregisterEvent('foo', 'bar'));
	}

	public function testRegisterMethod() {
		$service = new Elgg_Notifications_Service($this->queue, $this->hooks);

		$service->registerMethod('foo');
		$methods = array('foo' => 'foo');
		$this->assertEquals($methods, $service->getMethods());
	}

	public function testUnregisterMethod() {
		$service = new Elgg_Notifications_Service($this->queue, $this->hooks);

		$service->registerMethod('foo');
		$this->assertTrue($service->unregisterMethod('foo'));
		$this->assertEquals(array(), $service->getMethods());
		$this->assertFalse($service->unregisterMethod('foo'));
	}

	public function testEnqueueEvent() {
		$service = new Elgg_Notifications_Service($this->queue, $this->hooks);

		$service->registerEvent('object', 'bar');
		$object = new ElggObject();
		$object->subtype = 'bar';
		$service->enqueueEvent('create', '', $object);
		$event = new Elgg_Notifications_Event($object, 'create');
		$this->assertEquals($event, $this->queue->dequeue());
		$this->assertNull($this->queue->dequeue());

		// unregistered action type
		$service->enqueueEvent('null', '', $object);
		$this->assertNull($this->queue->dequeue());

		// unregistered object type
		$service->enqueueEvent('create', '', new ElggObject());
		$this->assertNull($this->queue->dequeue());
	}
}
