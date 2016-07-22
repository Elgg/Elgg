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

	/**
	 * @var \Elgg\Database\AccessCollections
	 */
	private $accessCollections;

	public function setUp() {
		$this->mocks = new EntityMocks($this);
		$this->entities = $this->mocks->getEntityTableMock();

		$this->hooks = new PluginHooksService();
		$this->queue = new MemoryQueue();
		$dbMock = $this->getMockBuilder(\Elgg\Database::class)
				->disableOriginalConstructor()
				->getMock();
		$this->subscriptions = new SubscriptionsService($dbMock);
		$this->translator = new Translator();
		$this->translator->addTranslation('en', ['__test__' => 'Test']);

		$this->session = ElggSession::getMock();
		$this->session->start();

		$this->config = $this->config();
		$this->context = new Context();
		$this->logger = new Logger($this->hooks, $this->config, $this->context);
		$this->logger->disable();

		$this->accessCollections = $this->getMockBuilder(\Elgg\Database\AccessCollections::class)
				->disableOriginalConstructor()
				->setMethods(['hasAccessToEntity'])
				->getMock();

		$this->accessCollections->expects($this->any())
				->method('hasAccessToEntity')
				->will($this->returnCallback(function($entity, $user) {
							if ($user->isAdmin()) {
								return true;
							}
							if ($entity->owner_guid == $user->guid) {
								return true;
							}
							if ($entity->access_id == ACCESS_PUBLIC) {
								return true;
							}
							if ($entity->access_id == ACCESS_LOGGED_IN && elgg_is_logged_in()) {
								return true;
							}
							if ($entity->access_id == ACCESS_PRIVATE && $entity->owner_guid == $user->guid) {
								return true;
							}
							return false;
						}));
	}

	public function tearDown() {
		$this->logger->enable();
		$this->session->invalidate();
	}

	public function setupServices() {
		_elgg_services()->setValue('hooks', $this->hooks);
		_elgg_services()->setValue('translator', $this->translator);
		_elgg_services()->setValue('session', $this->session);
		_elgg_services()->setValue('config', $this->config);
		_elgg_services()->setValue('context', $this->context);
		_elgg_services()->setValue('logger', $this->logger);
		_elgg_services()->setValue('entityTable', $this->entities);
		_elgg_services()->setValue('accessCollections', $this->accessCollections);

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

	/**
	 * Can't use dataProvider because dataProvider methods are static,
	 * and we need a concrete instance of the test to boostrap mocks
	 */
	public function testNotificationFlow() {

		$objects = [
			$this->mocks->getObject([
				'access_id' => ACCESS_LOGGED_IN,
			]),
			$this->mocks->getGroup([
				'access_id' => ACCESS_LOGGED_IN,
			]),
			$this->mocks->getUser([
				'access_id' => ACCESS_LOGGED_IN,
			]),
			new \ElggMetadata((object) [
						'id' => 1,
						'entity_guid' => $this->mocks->getObject()->guid,
						'owner_guid' => $this->mocks->getUser()->guid,
						'name' => 'foo',
						'value' => 'bar',
						'time_created' => time(),
					]),
			new \ElggAnnotation((object) [
						'id' => 2,
						'entity_guid' => $this->mocks->getObject()->guid,
						'owner_guid' => $this->mocks->getUser()->guid,
						'name' => 'foo',
						'value' => 'bar',
						'time_created' => time(),
					]),
			new \ElggRelationship((object) [
						'id' => 3,
						'guid_one' => $this->mocks->getUser()->guid,
						'guid_two' => $this->mocks->getUser()->guid,
						'relationship' => 'friend',
						'time_created' => time(),
					]),
		];

		foreach ($objects as $object) {
			$this->canUseEnqueueHookToPreventQueuing($object);
			$this->canUseHooksBeforeAndAfterQueueProcessing($object);
			$this->canProcessQueue($object);
			$this->canAlterTranslations($object);
			$this->canPrepareNotification($object);
		}
	}

	public function canUseEnqueueHookToPreventQueuing($object) {

		$call_count = 0;

		$recipient = $this->mocks->getUser([
			'language' => 'en',
		]);

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(0))
				->method('getSubscriptions')
				->will($this->returnValue([
							$recipient->guid => ['foo', 'bar'],
		]));

		$this->subscriptions = $mock;

		$this->hooks->backup();

		$this->hooks->registerHandler('enqueue', 'notification', function($hook, $type, $return, $params) use (&$call_count, $object) {
			$call_count++;
			$this->assertSame($object, $params['object'], 'Failed with ' . get_class($object));
			$this->assertEquals('create', $params['action'], 'Failed with ' . get_class($object));
			return false;
		});

		$this->setupServices();

		$this->notifications->registerMethod('foo');


		$this->notifications->registerEvent($object->getType(), $object->getSubtype());

		$this->assertEquals(0, $this->queue->size(), 'Failed with ' . get_class($object));
		$this->notifications->enqueueEvent('create', $object->getType(), $object);
		$this->assertEquals(1, $call_count, 'Failed with ' . get_class($object));
		$this->assertEquals(0, $this->queue->size(), 'Failed with ' . get_class($object));

		$this->assertEquals(0, $this->notifications->processQueue(time() + 10), 'Failed with ' . get_class($object));

		$this->hooks->restore();
	}

	public function canUseHooksBeforeAndAfterQueueProcessing($object) {

		$before_call_count = 0;
		$after_call_count = 0;

		$recipient = $this->mocks->getUser();
		$subscribers = [
			$recipient->guid => ['foo', 'bar'],
		];
		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
				->method('getSubscriptions')
				->will($this->returnValue($subscribers));

		$this->subscriptions = $mock;

		$this->hooks->backup();

		$event = new SubscriptionNotificationEvent($object, 'create');

		$this->hooks->registerHandler('send:before', 'notifications', function($hook, $type, $return, $params) use (&$before_call_count, $event, $subscribers, $object) {
			$before_call_count++;
			$this->assertEquals($event, $params['event'], 'Failed with ' . get_class($object));
			$this->assertEquals($subscribers, $params['subscriptions'], 'Failed with ' . get_class($object));
			return false;
		});

		$this->hooks->registerHandler('send:after', 'notifications', function($hook, $type, $return, $params) use (&$after_call_count, $event, $subscribers, $object) {
			$after_call_count++;
			$this->assertEquals($event, $params['event'], 'Failed with ' . get_class($object));
			$this->assertEquals($subscribers, $params['subscriptions'], 'Failed with ' . get_class($object));
			$this->assertEmpty($params['deliveries']);
		});

		$this->setupServices();

		$this->notifications->registerMethod('foo');
		$this->notifications->registerEvent($object->getType(), $object->getSubtype());

		$this->assertEquals(0, $this->queue->size(), 'Failed with ' . get_class($object));
		$this->notifications->enqueueEvent('create', $object->getType(), $object);
		$this->assertEquals(1, $this->queue->size(), 'Failed with ' . get_class($object));

		$this->assertEquals(1, $this->notifications->processQueue(time() + 10), 'Failed with ' . get_class($object));

		$this->assertEquals(1, $before_call_count, 'Failed with ' . get_class($object));
		$this->assertEquals(1, $after_call_count, 'Failed with ' . get_class($object));

		$this->hooks->restore();
	}

	public function canProcessQueue($object) {

		$logged_in = $this->mocks->getUser([
			'language' => 'en',
		]);
		$this->session->setLoggedInUser($logged_in);

		$call_count = 0;

		$recipient = $this->mocks->getUser();

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
				->method('getSubscriptions')
				->will($this->returnValue([
							$recipient->guid => ['foo', 'bar'],
		]));

		$this->subscriptions = $mock;

		$event = new SubscriptionNotificationEvent($object, 'create');

		$this->translator->addTranslation('en', [
			'notification:body' => 'Link: %s',
			'notification:subject' => 'From: %s',
		]);

		$this->hooks->backup();

		$this->hooks->registerHandler('send', 'notification:foo', function($hook, $type, $return, $params) use (&$call_count, $event, $object, $recipient) {
			$call_count++;
			$this->assertInstanceOf(Notification::class, $params['notification']);
			$this->assertEquals($this->translator->translate('notification:subject', [$event->getActor()->name], $recipient->language), $params['notification']->subject);
			$this->assertEquals($this->translator->translate('notification:body', [$event->getObject()->getURL()], $recipient->language), $params['notification']->body);
			$this->assertEquals($event, $params['event'], 'Failed with ' . get_class($object));
			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('foo');
		$this->notifications->registerEvent($object->getType(), $object->getSubtype());

		$this->assertEquals(0, $this->queue->size(), 'Failed with ' . get_class($object));
		$this->notifications->enqueueEvent('create', $object->getType(), $object);

		$event = $this->queue->dequeue();
		$this->assertInstanceOf(SubscriptionNotificationEvent::class, $event);
		$this->assertSame($logged_in, $event->getActor());
		$this->assertSame($object, $event->getObject());
		$this->assertEquals("create:{$object->getType()}:{$object->getSubtype()}", $event->getDescription());

		$this->notifications->enqueueEvent('create', $object->getType(), $object);
		$this->assertEquals(1, $this->queue->size(), 'Failed with ' . get_class($object));

		$deliveries = [
			"create:{$object->getType()}:{$object->getSubtype()}" => [
				$recipient->guid => [
					'foo' => true,
					'bar' => false,
				]
			]
		];

		$this->assertEquals($deliveries, $this->notifications->processQueue(time() + 10, true), 'Failed with ' . get_class($object));

		$this->assertEquals(1, $call_count, 'Failed with ' . get_class($object));

		$this->hooks->restore();

		$this->session->removeLoggedInUser();
	}

	public function canAlterTranslations($object) {

		$logged_in = $this->mocks->getUser([
			'language' => 'en',
		]);
		$this->session->setLoggedInUser($logged_in);

		$call_count = 0;

		$recipient = $this->mocks->getUser([
			'language' => 'en',
		]);

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
				->method('getSubscriptions')
				->will($this->returnValue([
							$recipient->guid => ['foo', 'bar'],
		]));

		$this->subscriptions = $mock;

		$event = new SubscriptionNotificationEvent($object, 'create');
		
		$this->translator->addTranslation('en', [
			"notification:{$event->getDescription()}:body" => '%s %s %s %s %s %s',
			"notification:{$event->getDescription()}:subject" => '%s %s',
		]);

		$this->hooks->backup();

		$this->hooks->registerHandler('send', 'notification:foo', function($hook, $type, $return, $params) use (&$call_count, $event, $object, $recipient) {
			$call_count++;

			$object = $event->getObject();
			if ($object instanceof \ElggEntity) {
				$display_name = $object->getDisplayName();
				$container_name = '';
				$container = $object->getContainerEntity();
				if ($container) {
					$container_name = $container->getDisplayName();
				}
			} else {
				$display_name = '';
				$container_name = '';
			}
			$this->assertInstanceOf(Notification::class, $params['notification']);
			$this->assertEquals($this->translator->translate("notification:{$event->getDescription()}:subject", [
						$event->getActor()->name,
						$display_name,
							], $recipient->language), $params['notification']->subject);
			$this->assertEquals($this->translator->translate("notification:{$event->getDescription()}:body", [
						$recipient->name,
						$event->getActor()->name,
						$display_name,
						$container_name,
						$object->description,
						$object->getURL(),
					], $recipient->language), $params['notification']->body);
			$this->assertEquals($event, $params['event'], 'Failed with ' . get_class($object));
			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('foo');
		$this->notifications->registerEvent($object->getType(), $object->getSubtype());
		$this->notifications->enqueueEvent('create', $object->getType(), $object);

		$this->notifications->processQueue(time() + 10);

		$this->assertEquals(1, $call_count, 'Failed with ' . get_class($object));

		$this->hooks->restore();

		$this->session->removeLoggedInUser();
	}

	public function canPrepareNotification($object) {

		$logged_in = $this->mocks->getUser([
			'language' => 'en',
		]);
		$this->session->setLoggedInUser($logged_in);

		$recipient = $this->mocks->getUser([
			'language' => 'en',
		]);

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
				->method('getSubscriptions')
				->will($this->returnValue([
							$recipient->guid => ['foo', 'bar'],
		]
						));

		$this->subscriptions = $mock;

		$event = new SubscriptionNotificationEvent($object, 'create');

		$this->hooks->backup();

		$this->hooks->registerHandler('prepare', 'notification', function($hook, $type, $notification) {
			$notification->prepare_hook = true;
			return $notification;
		});

		$this->hooks->registerHandler('prepare', "notification:{$event->getDescription()}", function($hook, $type, $notification) {
			$notification->granular_prepare_hook = true;
			return $notification;
		});

		$this->hooks->registerHandler('format', 'notification:foo', function($hook, $type, $notification) {
			$notification->format_hook = true;
			return $notification;
		});

		$this->hooks->registerHandler('send', 'notification:foo', function($hook, $type, $return, $params) {
			$notification = $params['notification'];
			$this->assertTrue($notification->prepare_hook);
			$this->assertTrue($notification->granular_prepare_hook);
			$this->assertTrue($notification->format_hook);
			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('foo');
		$this->notifications->registerEvent($object->getType(), $object->getSubtype());
		$this->notifications->enqueueEvent('create', $object->getType(), $object);

		$this->assertEquals(1, $this->notifications->processQueue(time() + 10));

		$this->hooks->restore();

		$this->session->removeLoggedInUser();
	}
}
