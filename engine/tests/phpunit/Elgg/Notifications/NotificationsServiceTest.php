<?php

namespace Elgg\Notifications;

use Elgg\Config;
use Elgg\Context;
use Elgg\Database\EntityTable;
use Elgg\I18n\Translator;
use Elgg\Logger;
use Elgg\PluginHooksService;
use Elgg\Queue\MemoryQueue;
use Elgg\TestCase;
use Elgg\Tests\EntityMocks;
use ElggObject;
use ElggSession;

/**
 * @group NotificationsService
 */
class NotificationsServiceTest extends TestCase {

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var MemoryQueue
	 */
	private $queue;

	/**
	 * @var SubscriptionsService
	 */
	private $subscriptions;

	/**
	 * @var Translator
	 */
	private $translator;

	/**
	 * @var ElggSession
	 */
	private $session;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * @var NotificationsService
	 */
	private $notifications;

	/**
	 * @var EntityMocks
	 */
	private $mocks;

	/**
	 * @var EntityTable
	 */
	private $entities;

	public function setUp() {
		$this->hooks = new PluginHooksService();
		$this->queue = new MemoryQueue();
		$dbMock = $this->getMockBuilder(\Elgg\Database::class)
				->disableOriginalConstructor()
				->getMock();
		$this->subscriptions = new SubscriptionsService($dbMock);
		$this->translator = new Translator();
		$this->session = ElggSession::getMock();
		$this->config = $this->config();
		$this->context = new Context();
		$this->logger = new Logger($this->hooks, $this->config, $this->context);
		$this->logger->disable();

		$this->mocks = new EntityMocks($this);
		$this->entities = $this->mocks->getEntityTableMock();
	}

	public function tearDown() {
		$this->logger->enable();
	}

	public function setupServices() {
		_elgg_services()->setValue('hooks', $this->hooks);
		_elgg_services()->setValue('translator', $this->translator);
		_elgg_services()->setValue('session', $this->session);
		_elgg_services()->setValue('config', $this->config);
		_elgg_services()->setValue('context', $this->context);
		_elgg_services()->setValue('logger', $this->logger);
		_elgg_services()->setValue('entityTable', $this->entities);

		$this->notifications = new NotificationsService(
				$this->subscriptions, $this->queue, $this->hooks, $this->session, $this->translator, $this->entities, $this->logger);

		_elgg_services()->setValue('notifications', $this->notifications);
	}

	public function testRegisterEvent() {
		$this->setupServices();

		$this->notifications->registerEvent('foo', 'bar');
		$events = array(
			'foo' => array(
				'bar' => array('create')
			)
		);
		$this->assertEquals($events, $this->notifications->getEvents());

		$this->notifications->registerEvent('foo', 'bar', array('test'));
		$events['foo']['bar'] = array('create', 'test');
		$this->assertEquals($events, $this->notifications->getEvents());

		$this->notifications->registerEvent('foo', 'bar');
		$this->assertEquals($events, $this->notifications->getEvents());
	}

	public function testUnregisterEvent() {
		$this->setupServices();

		$this->notifications->registerEvent('foo', 'bar');
		$this->assertTrue($this->notifications->unregisterEvent('foo', 'bar'));

		$events = array(
			'foo' => array()
		);
		$this->assertEquals($events, $this->notifications->getEvents());
		$this->assertFalse($this->notifications->unregisterEvent('foo', 'bar'));
	}

	public function testRegisterMethod() {
		$this->setupServices();

		$this->notifications->registerMethod('foo');
		$methods = array('foo' => 'foo');
		$this->assertEquals($methods, $this->notifications->getMethods());
	}

	public function testUnregisterMethod() {
		$this->setupServices();

		$this->notifications->registerMethod('foo');
		$this->assertTrue($this->notifications->unregisterMethod('foo'));
		$this->assertEquals(array(), $this->notifications->getMethods());
		$this->assertFalse($this->notifications->unregisterMethod('foo'));
	}

	public function testEnqueueEvent() {
		$this->setupServices();

		$object = $this->mocks->getObject();
		$this->notifications->registerEvent('object', $object->getSubtype());

		$this->notifications->enqueueEvent('create', 'object', $object);

		$event = new SubscriptionNotificationEvent($object, 'create');
		$this->assertEquals($event, $this->queue->dequeue());
		$this->assertNull($this->queue->dequeue());

		// unregistered action type
		$this->notifications->enqueueEvent('null', 'object', $object);
		$this->assertNull($this->queue->dequeue());

		// unregistered object type
		$this->notifications->enqueueEvent('create', 'object', new ElggObject());
		$this->assertNull($this->queue->dequeue());
	}

	public function testEnqueueEventHook() {

		$object = $this->mocks->getObject();

		$params = array('action' => 'create', 'object' => $object);

		$mock = $this->getMock(PluginHooksService::class, ['trigger']);
		$mock->expects($this->once())
				->method('trigger')
				->with('enqueue', 'notification', $params, true);
		$this->hooks = $mock;

		$this->setupServices();
		$this->notifications->registerEvent('object', $object->getSubtype());
		$this->notifications->enqueueEvent('create', 'object', $object);
	}

	public function testStoppingEnqueueEvent() {

		$mock = $this->getMock(PluginHooksService::class, ['trigger']);
		$mock->expects($this->once())
				->method('trigger')
				->will($this->returnValue(false));

		$this->hooks = $mock;

		$this->setupServices();

		$object = $this->mocks->getObject();
		$this->notifications->registerEvent('object', $object->getSubtype());

		$this->notifications->enqueueEvent('create', 'object', $object);
		$this->assertNull($this->queue->dequeue());
	}

	public function testProcessQueueNoEvents() {
		$this->setupServices();
		$this->assertEquals(0, $this->notifications->processQueue(time() + 10));
	}

	public function testProcessQueueThreeEvents() {

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(3))
				->method('getSubscriptions')
				->will($this->returnValue([]));

		$this->subscriptions = $mock;

		$this->setupServices();

		$object = $this->mocks->getObject();
		$this->notifications->registerEvent('object', $object->getSubtype());
		
		$this->notifications->enqueueEvent('create', 'object', $object);
		$this->notifications->enqueueEvent('create', 'object', $object);
		$this->notifications->enqueueEvent('create', 'object', $object);

		$this->assertEquals(3, $this->notifications->processQueue(time() + 10));
	}

	public function testProcessQueueTimesout() {
		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(0))
				->method('getSubscriptions')
				->will($this->returnValue([]));

		$this->subscriptions = $mock;

		$this->setupServices();

		$object = $this->mocks->getObject();
		$this->notifications->registerEvent('object', $object->getSubtype());

		$this->notifications->enqueueEvent('create', 'object', $object);
		$this->notifications->enqueueEvent('create', 'object', $object);
		$this->notifications->enqueueEvent('create', 'object', $object);

		$this->assertEquals(0, $this->notifications->processQueue(time()));
	}

}
