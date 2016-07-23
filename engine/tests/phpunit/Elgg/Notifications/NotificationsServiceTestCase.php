<?php

namespace Elgg\Notifications;

use Elgg\Config;
use Elgg\Context;
use Elgg\Database;
use Elgg\Database\AccessCollections;
use Elgg\Database\Annotations;
use Elgg\Database\EntityTable;
use Elgg\Database\MetadataTable;
use Elgg\Database\RelationshipsTable;
use Elgg\Debug\Inspector;
use Elgg\EventsService;
use Elgg\I18n\Translator;
use Elgg\Logger;
use Elgg\PluginHooksService;
use Elgg\Queue\MemoryQueue;
use Elgg\TestCase;
use Elgg\Tests\EntityMocks;
use ElggEntity;
use ElggObject;
use ElggSession;
use Exception;

abstract class NotificationsServiceTestCase extends TestCase {

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 *
	 * @var Inspector
	 */
	protected $inspector;

	/**
	 * @var MemoryQueue
	 */
	protected $queue;

	/**
	 * @var SubscriptionsService
	 */
	protected $subscriptions;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * @var ElggSession
	 */
	protected $session;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Context
	 */
	protected $context;

	/**
	 * @var Logger
	 */
	protected $logger;

	/**
	 * @var NotificationsService
	 */
	protected $notifications;

	/**
	 * @var EntityMocks
	 */
	protected $mocks;

	/**
	 * @var EntityTable
	 */
	protected $entities;

	/**
	 * @var MetadataTable
	 */
	protected $metadata;

	/**
	 * @var Annotations
	 */
	protected $annotations;

	/**
	 * @var RelationshipsTable
	 */
	protected $relationships;

	/**
	 * @var AccessCollections
	 */
	protected $accessCollections;

	/**
	 * @var string
	 */
	protected $test_object_class;

	public function setUp() {

		if (!isset($this->test_object_class)) {
			throw new Exception(get_class($this) . ' must set \$object_test_class before calling ' . __METHOD__);
		}

		$this->mocks = new EntityMocks($this);
		$this->entities = $this->mocks->getEntityTableMock();
		$this->metadata = $this->mocks->getMetadataTableMock();
		$this->annotations = $this->mocks->getAnnotationsTableMock();
		$this->relationships = $this->mocks->getRelationshipsTableMock();

		$this->inspector = new Inspector();
		$this->events = new EventsService($this->inspector);
		$this->events->backup();

		$this->hooks = new PluginHooksService();
		$this->hooks->backup();

		$this->queue = new DatabaseQueueMock();
		$dbMock = $this->getMockBuilder(Database::class)
				->disableOriginalConstructor()
				->getMock();

		$this->subscriptions = new SubscriptionsService($dbMock);
		$this->translator = new Translator();
		$this->translator->addTranslation('en', ['__test__' => 'Test']);

		$this->session = ElggSession::getMock();
		$this->session->start();
		$this->session->setLoggedInUser($this->mocks->getUser([
			'language' => 'en',
		]));

		$this->config = $this->config();
		$this->context = new Context();
		$this->logger = new Logger($this->hooks, $this->config, $this->context);
		$this->logger->disable();

		$this->accessCollections = $this->getMockBuilder(AccessCollections::class)
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
		$this->events->restore();
		$this->hooks->restore();
	}

	public function setupServices() {
		_elgg_services()->setValue('hooks', $this->hooks);
		_elgg_services()->setValue('events', $this->events);
		_elgg_services()->setValue('translator', $this->translator);
		_elgg_services()->setValue('session', $this->session);
		_elgg_services()->setValue('config', $this->config);
		_elgg_services()->setValue('context', $this->context);
		_elgg_services()->setValue('logger', $this->logger);
		_elgg_services()->setValue('entityTable', $this->entities);
		_elgg_services()->setValue('metadataTable', $this->metadata);
		_elgg_services()->setValue('annotations', $this->annotations);
		_elgg_services()->setValue('relationshipsTable', $this->relationships);
		_elgg_services()->setValue('accessCollections', $this->accessCollections);

		$this->notifications = new NotificationsService(
				$this->subscriptions, $this->queue, $this->hooks, $this->session, $this->translator, $this->entities, $this->logger);

		_elgg_services()->setValue('notifications', $this->notifications);
	}

	public function getTestObject() {
		$objects = $this->prepareTestObjects();
		foreach ($objects as $object) {
			if ($object instanceof $this->test_object_class) {
				return $object;
			}
		}
		throw new Exception("Test object not found for $this->test_object_class class");
	}

	public function prepareTestObjects() {

		$this->setupServices();

		$object = $this->mocks->getObject([
			'access_id' => ACCESS_LOGGED_IN,
			'subtype' => 'test_subtype',
		]);

		$group = $this->mocks->getGroup([
			'access_id' => ACCESS_LOGGED_IN,
		]);

		$user = $this->mocks->getUser();

		$metadata_id = create_metadata($object->guid, 'test_metadata_name', 'test_metadata_value');
		$metadata = elgg_get_metadata_from_id($metadata_id);

		$annotation_id = $object->annotate('test_annotation_name', 'test_annotation_value');
		$annotation = elgg_get_annotation_from_id($annotation_id);

		add_entity_relationship($object->guid, 'test_relationship', $user->guid);
		$relationship = check_entity_relationship($object->guid, 'test_relationship', $user->guid);

		return [
			$object,
			$group,
			$user,
			$metadata,
			$annotation,
			$relationship,
		];
	}

	public function testRegisterEvent() {
		$this->setupServices();

		$object = $this->getTestObject();

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());
		$events = array(
			$object->getType() => array(
				$object->getSubtype() => array('create')
			)
		);
		$this->assertEquals($events, $this->notifications->getEvents());

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), array('test_event'));
		$events[$object->getType()][$object->getSubtype()] = array('create', 'test_event');
		$this->assertEquals($events, $this->notifications->getEvents());

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());
		$this->assertEquals($events, $this->notifications->getEvents());
	}

	public function testUnregisterEvent() {
		$this->setupServices();

		$object = $this->getTestObject();

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());
		$this->assertTrue($this->notifications->unregisterEvent($object->getType(), $object->getSubtype()));

		$events = array(
			$object->getType() => array()
		);
		$this->assertEquals($events, $this->notifications->getEvents());
		$this->assertFalse($this->notifications->unregisterEvent($object->getType(), $object->getSubtype()));
	}

	public function testRegisterMethod() {
		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$methods = array('test_method' => 'test_method');
		$this->assertEquals($methods, $this->notifications->getMethods());
	}

	public function testUnregisterMethod() {
		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->assertTrue($this->notifications->unregisterMethod('test_method'));
		$this->assertEquals(array(), $this->notifications->getMethods());
		$this->assertFalse($this->notifications->unregisterMethod('test_method'));
	}

	public function testEnqueueEvent() {
		$this->setupServices();

		$object = $this->getTestObject();

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());

		$this->notifications->enqueueEvent('create', $object->getType(), $object);

		$event = new SubscriptionNotificationEvent($object, 'create');
		$this->assertEquals($event, $this->queue->dequeue());
		$this->assertNull($this->queue->dequeue());

		// unregistered action type
		$this->notifications->enqueueEvent('null', $object->getType(), $object);
		$this->assertNull($this->queue->dequeue());

		// unregistered object type
		$this->notifications->enqueueEvent('create', $object->getType(), new ElggObject());
		$this->assertNull($this->queue->dequeue());
	}

	public function testStoppingEnqueueEvent() {

		$mock = $this->getMock(PluginHooksService::class, ['trigger']);
		$mock->expects($this->once())
				->method('trigger')
				->will($this->returnValue(false));

		$this->hooks = $mock;

		$this->setupServices();

		$object = $this->getTestObject();

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());

		$this->notifications->enqueueEvent('create', $object->getType(), $object);
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

		$object = $this->getTestObject();
		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['event1', 'event2', 'event3']);

		$this->notifications->enqueueEvent('event1', $object->getType(), $object);
		$this->notifications->enqueueEvent('event2', $object->getType(), $object);
		$this->notifications->enqueueEvent('event3', $object->getType(), $object);

		$this->assertEquals(3, $this->notifications->processQueue(time() + 10));
	}

	public function testProcessQueueTimesout() {

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(0))
				->method('getSubscriptions')
				->will($this->returnValue([]));

		$this->subscriptions = $mock;

		$this->setupServices();

		$object = $this->getTestObject();
		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['event1', 'event2', 'event3']);

		$this->notifications->enqueueEvent('event1', $object->getType(), $object);
		$this->notifications->enqueueEvent('event2', $object->getType(), $object);
		$this->notifications->enqueueEvent('event3', $object->getType(), $object);

		$this->assertEquals(0, $this->notifications->processQueue(time()));
	}

	public function testCanUseEnqueueHookToPreventSubscriptionNotificationEventFromQueueing() {

		$object = $this->getTestObject();

		$call_count = 0;

		$recipient = $this->mocks->getUser([
			'language' => 'en',
		]);

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(0))
				->method('getSubscriptions')
				->will($this->returnValue([
							$recipient->guid => ['test_method', 'bad_method'],
		]));

		$this->subscriptions = $mock;

		$this->hooks->registerHandler('enqueue', 'notification', function($hook, $type, $return, $params) use (&$call_count, $object) {
			$call_count++;
			$this->assertSame($object, $params['object']);
			$this->assertEquals('test_event', $params['action']);
			return false;
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');


		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);

		$this->assertEquals(0, $this->queue->size());
		$this->notifications->enqueueEvent('test_event', $object->getType(), $object);
		$this->assertEquals(1, $call_count);
		$this->assertEquals(0, $this->queue->size());

		$this->assertEquals(0, $this->notifications->processQueue(time() + 10));
	}

	public function testCanUseHooksBeforeAndAfterSubscriptionNotificationsQueue() {

		$object = $this->getTestObject();

		$before_call_count = 0;
		$after_call_count = 0;

		$recipient = $this->mocks->getUser();
		$subscribers = [
			$recipient->guid => ['test_method', 'bad_method'],
		];
		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
				->method('getSubscriptions')
				->will($this->returnValue($subscribers));

		$this->subscriptions = $mock;

		$event = new SubscriptionNotificationEvent($object, 'test_event');

		$this->hooks->registerHandler('send:before', 'notifications', function($hook, $type, $return, $params) use (&$before_call_count, $event, $subscribers, $object) {
			$before_call_count++;
			$this->assertEquals($event, $params['event']);
			$this->assertEquals($subscribers, $params['subscriptions']);
			return false;
		});

		$this->hooks->registerHandler('send:after', 'notifications', function($hook, $type, $return, $params) use (&$after_call_count, $event, $subscribers, $object) {
			$after_call_count++;
			$this->assertEquals($event, $params['event']);
			$this->assertEquals($subscribers, $params['subscriptions']);
			$this->assertEmpty($params['deliveries']);
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);

		$this->assertEquals(0, $this->queue->size());
		$this->notifications->enqueueEvent('test_event', $object->getType(), $object);
		$this->assertEquals(1, $this->queue->size());

		$this->assertEquals(1, $this->notifications->processQueue(time() + 10));

		$this->assertEquals(1, $before_call_count);
		$this->assertEquals(1, $after_call_count);
	}

	public function testCanProcessSubscriptionNotificationsQueue() {

		$object = $this->getTestObject();

		$call_count = 0;

		$recipient = $this->mocks->getUser();

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
				->method('getSubscriptions')
				->will($this->returnValue([
							$recipient->guid => ['test_method', 'bad_method'],
		]));

		$this->subscriptions = $mock;

		$event = new SubscriptionNotificationEvent($object, 'test_event');

		$this->translator->addTranslation('en', [
			'notification:body' => 'Link: %s',
			'notification:subject' => 'From: %s',
		]);

		$this->hooks->registerHandler('send', 'notification:test_method', function($hook, $type, $return, $params) use (&$call_count, $event, $recipient) {
			$call_count++;
			$this->assertInstanceOf(Notification::class, $params['notification']);
			$this->assertEquals($this->translator->translate('notification:subject', [$event->getActor()->name], $recipient->language), $params['notification']->subject);
			$this->assertEquals($this->translator->translate('notification:body', [$event->getObject()->getURL()], $recipient->language), $params['notification']->body);
			$this->assertEquals($event, $params['event']);
			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);

		$this->assertEquals(0, $this->queue->size());
		$this->notifications->enqueueEvent('test_event', $object->getType(), $object);

		$event = $this->queue->dequeue();
		$this->assertInstanceOf(SubscriptionNotificationEvent::class, $event);
		$this->assertSame(elgg_get_logged_in_user_entity(), $event->getActor());
		$this->assertSame($object, $event->getObject());
		$this->assertEquals("test_event:{$object->getType()}:{$object->getSubtype()}", $event->getDescription());

		$this->notifications->enqueueEvent('test_event', $object->getType(), $object);
		$this->assertEquals(1, $this->queue->size());

		$deliveries = [
			"test_event:{$object->getType()}:{$object->getSubtype()}" => [
				$recipient->guid => [
					'test_method' => true,
					'bad_method' => false,
				]
			]
		];

		$result = $this->notifications->processQueue(time() + 10, true);
		$this->assertEquals(1, $call_count);
		$this->assertEquals($deliveries, $result);
	}

	public function testCanAlterSubscriptionNotificationTranslations() {

		$object = $this->getTestObject();

		$call_count = 0;

		$recipient = $this->mocks->getUser([
			'language' => 'en',
		]);

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
				->method('getSubscriptions')
				->will($this->returnValue([
							$recipient->guid => ['test_method', 'bad_method'],
		]));

		$this->subscriptions = $mock;

		$event = new SubscriptionNotificationEvent($object, 'test_event');

		$this->translator->addTranslation('en', [
			"notification:{$event->getDescription()}:body" => '%s %s %s %s %s %s',
			"notification:{$event->getDescription()}:subject" => '%s %s',
		]);

		$this->hooks->registerHandler('send', 'notification:test_method', function($hook, $type, $return, $params) use (&$call_count, $event, $object, $recipient) {
			$call_count++;

			$object = $event->getObject();
			if ($object instanceof ElggEntity) {
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
			$this->assertEquals($event, $params['event']);
			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);
		$this->notifications->enqueueEvent('test_event', $object->getType(), $object);

		$this->notifications->processQueue(time() + 10);

		$this->assertEquals(1, $call_count);
	}

	public function testCanPrepareSubscriptionNotification() {

		$object = $this->getTestObject();

		$recipient = $this->mocks->getUser([
			'language' => 'en',
		]);

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
				->method('getSubscriptions')
				->will($this->returnValue([
							$recipient->guid => ['test_method', 'bad_method'],
								]
		));

		$this->subscriptions = $mock;

		$event = new SubscriptionNotificationEvent($object, 'test_event');

		$this->hooks->registerHandler('prepare', 'notification', function($hook, $type, $notification) {
			$notification->prepare_hook = true;
			return $notification;
		});

		$this->hooks->registerHandler('prepare', "notification:{$event->getDescription()}", function($hook, $type, $notification) {
			$notification->granular_prepare_hook = true;
			return $notification;
		});

		$this->hooks->registerHandler('format', 'notification:test_method', function($hook, $type, $notification) {
			$notification->format_hook = true;
			return $notification;
		});

		$this->hooks->registerHandler('send', 'notification:test_method', function($hook, $type, $return, $params) {
			$notification = $params['notification'];
			$this->assertTrue($notification->prepare_hook);
			$this->assertTrue($notification->granular_prepare_hook);
			$this->assertTrue($notification->format_hook);
			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);
		$this->notifications->enqueueEvent('test_event', $object->getType(), $object);

		$this->assertEquals(1, $this->notifications->processQueue(time() + 10));
	}

	public function testValidatesObjectExistenceForDequeuedSubscriptionNotificationEvent() {

		$object = $this->getTestObject();

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(0))
				->method('getSubscriptions')
				->will($this->returnValue([]));

		$this->subscriptions = $mock;

		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);
		$this->notifications->enqueueEvent('test_event', $object->getType(), $object);

		$object->delete();

		$this->assertEquals(0, $this->notifications->processQueue(time() + 10));
	}

	public function testValidatesActorExistenceForDequeuedSubscriptionNotificationEvent() {

		$object = $this->getTestObject();

		$mock = $this->getMock(SubscriptionsService::class, ['getSubscriptions'], [], '', false);
		$mock->expects($this->exactly(0))
				->method('getSubscriptions')
				->will($this->returnValue([]));

		$this->subscriptions = $mock;

		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);
		$this->notifications->enqueueEvent('test_event', $object->getType(), $object);

		$this->session->getLoggedInUser()->delete();

		$this->assertEquals(0, $this->notifications->processQueue(time() + 10));
	}
}
