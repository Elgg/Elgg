<?php
namespace Elgg\Notifications;

class NotificationsServiceTest extends \PHPUnit_Framework_TestCase {

	protected $session;

	public function setUp() {
		$this->hooks = new \Elgg\PluginHooksService();
		$this->queue = new \Elgg\Queue\MemoryQueue();
		$dbMock = $this->getMockBuilder('\Elgg\Database')
			->disableOriginalConstructor()
			->getMock();
		$this->sub = new \Elgg\Notifications\SubscriptionsService($dbMock);
		$this->session = \ElggSession::getMock();

		// Event class has dependency on elgg_get_logged_in_user_guid()
		_elgg_services()->setValue('session', $this->session);
	}

	public function testRegisterEvent() {
		$service = new \Elgg\Notifications\NotificationsService($this->sub, $this->queue, $this->hooks, $this->session);

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
		$service = new \Elgg\Notifications\NotificationsService($this->sub, $this->queue, $this->hooks, $this->session);

		$service->registerEvent('foo', 'bar');
		$this->assertTrue($service->unregisterEvent('foo', 'bar'));

		$events = array(
			'foo' => array()
		);
		$this->assertEquals($events, $service->getEvents());
		$this->assertFalse($service->unregisterEvent('foo', 'bar'));
	}

	public function testRegisterMethod() {
		$service = new \Elgg\Notifications\NotificationsService($this->sub, $this->queue, $this->hooks, $this->session);

		$service->registerMethod('foo');
		$methods = array('foo' => 'foo');
		$this->assertEquals($methods, $service->getMethods());
	}

	public function testUnregisterMethod() {
		$service = new \Elgg\Notifications\NotificationsService($this->sub, $this->queue, $this->hooks, $this->session);

		$service->registerMethod('foo');
		$this->assertTrue($service->unregisterMethod('foo'));
		$this->assertEquals(array(), $service->getMethods());
		$this->assertFalse($service->unregisterMethod('foo'));
	}

	public function testEnqueueEvent() {
		$service = new \Elgg\Notifications\NotificationsService($this->sub, $this->queue, $this->hooks, $this->session);

		$service->registerEvent('object', 'bar');
		$object = new \ElggObject();
		$object->subtype = 'bar';
		$service->enqueueEvent('create', 'object', $object);
		$event = new \Elgg\Notifications\Event($object, 'create');
		$this->assertEquals($event, $this->queue->dequeue());
		$this->assertNull($this->queue->dequeue());

		// unregistered action type
		$service->enqueueEvent('null', 'object', $object);
		$this->assertNull($this->queue->dequeue());

		// unregistered object type
		$service->enqueueEvent('create', 'object', new \ElggObject());
		$this->assertNull($this->queue->dequeue());
	}

	public function testEnqueueEventHook() {
		$object = new \ElggObject();
		$object->subtype = 'bar';
		$params = array('action' => 'create', 'object' => $object);

		$mock = $this->getMock('\Elgg\PluginHooksService', array('trigger'));
		$mock->expects($this->once())
			->method('trigger')
			->with('enqueue', 'notification', $params, true);
		$service = new \Elgg\Notifications\NotificationsService($this->sub, $this->queue, $mock, $this->session);
		$service->registerEvent('object', 'bar');
		$service->enqueueEvent('create', 'object', $object);
	}

	public function testStoppingEnqueueEvent() {
		$mock = $this->getMock('\Elgg\PluginHooksService', array('trigger'));
		$mock->expects($this->once())
			->method('trigger')
			->will($this->returnValue(false));
		$service = new \Elgg\Notifications\NotificationsService($this->sub, $this->queue, $mock, $this->session);

		$service->registerEvent('object', 'bar');
		$object = new \ElggObject();
		$object->subtype = 'bar';
		$service->enqueueEvent('create', 'object', $object);
		$this->assertNull($this->queue->dequeue());
	}

	public function testProcessQueueNoEvents() {
		$service = new \Elgg\Notifications\NotificationsService($this->sub, $this->queue, $this->hooks, $this->session);
		$this->assertEquals(0, $service->processQueue(time() + 10));
	}

	public function testProcessQueueThreeEvents() {
		$mock = $this->getMock(
				'\Elgg\Notifications\SubscriptionsService',
				array('getSubscriptions'),
				array(),
				'',
				false);
		$mock->expects($this->exactly(3))
			->method('getSubscriptions')
			->will($this->returnValue(array()));
		$service = new \Elgg\Notifications\NotificationsService($mock, $this->queue, $this->hooks, $this->session);

		$service->registerEvent('object', 'bar');
		$object = new \ElggObject();
		$object->subtype = 'bar';
		$service->enqueueEvent('create', 'object', $object);
		$service->enqueueEvent('create', 'object', $object);
		$service->enqueueEvent('create', 'object', $object);

		$this->assertEquals(3, $service->processQueue(time() + 10));
	}

	public function testProcessQueueTimesout() {
		$mock = $this->getMock(
				'\Elgg\Notifications\SubscriptionsService',
				array('getSubscriptions'),
				array(),
				'',
				false);
		$mock->expects($this->exactly(0))
			->method('getSubscriptions');
		$service = new \Elgg\Notifications\NotificationsService($mock, $this->queue, $this->hooks, $this->session);

		$service->registerEvent('object', 'bar');
		$object = new \ElggObject();
		$object->subtype = 'bar';
		$service->enqueueEvent('create', 'object', $object);
		$service->enqueueEvent('create', 'object', $object);
		$service->enqueueEvent('create', 'object', $object);

		$this->assertEquals(0, $service->processQueue(time()));
	}
}

