<?php

namespace Elgg\Notifications;

use Elgg\EventsService;
use Elgg\Exceptions\Exception;
use Elgg\IntegrationTestCase;
use Elgg\Mocks\Queue\DatabaseQueue;
use Elgg\Values;

abstract class NotificationsServiceIntegrationTestCase extends IntegrationTestCase {

	protected EventsService $events;
	protected DatabaseQueue $queue;
	protected \ElggSession $session;
	protected \Elgg\SessionManagerService $session_manager;
	protected \Elgg\I18n\Translator $translator;
	protected NotificationsService $notifications;
	protected string $test_object_class;
	protected \ElggUser $actor;
	protected int $time;

	public function up() {
		parent::up();
		
		$this->createApplication([
			'isolate' => true,
		]);

		if (!isset($this->test_object_class)) {
			throw new Exception(get_class($this) . ' must set \$object_test_class before calling ' . __METHOD__);
		}

		$this->events = _elgg_services()->events;
		$this->events->backup();

		$this->queue = new DatabaseQueue();

		$this->time = _elgg_services()->entityTable->getCurrentTime()->getTimestamp();

		$this->session = _elgg_services()->session;
		$this->session_manager = _elgg_services()->session_manager;

		$this->translator = _elgg_services()->translator;

		$this->setupServices();

		$this->actor = $this->createUser();
	}

	public function down() {
		$this->session->invalidate();
		$this->events->restore();
		
		parent::down();
	}

	protected function setupServices() {
		$this->notifications = new NotificationsService(
			$this->queue,
			$this->session,
			$this->events
		);
		_elgg_services()->set('notifications', $this->notifications);
	}

	protected function getTestObject() {
		$this->setupServices();

		switch ($this->test_object_class) {
			case \ElggObject::class :
				return $this->createObject([
					'owner_guid' => $this->actor->guid,
					'container_guid' => $this->actor->guid,
					'access_id' => ACCESS_LOGGED_IN,
					'subtype' => 'test_subtype',
				]);

			case \ElggGroup::class :
				return $this->createGroup([
					'owner_guid' => $this->actor->guid,
					'container_guid' => $this->actor->guid,
					'access_id' => ACCESS_LOGGED_IN,
				]);

			case \ElggUser::class :
				return $this->actor;

			case \ElggMetadata::class :
				$object = $this->createObject();
				$metadata = new \ElggMetadata();
				$metadata->entity_guid = $object->guid;
				$metadata->name = 'test_metadata_name';
				$metadata->value = 'test_metadata_value';
				$id = _elgg_services()->metadataTable->create($metadata);

				// return fully loaded object
				return elgg_get_metadata_from_id($id);

			case \ElggAnnotation::class :
				$object = $this->createObject();

				$annotation = new \ElggAnnotation();
				$annotation->entity_guid = $object->guid;
				$annotation->name = 'test_annotation_name';
				$annotation->value = 'test_annotation_value';
				$annotation->owner_guid = $this->actor->guid;
				$annotation->access_id = ACCESS_PUBLIC;
				$annotation->save();

				return $annotation;

			case \ElggRelationship::class :
				$object = $this->createObject();
				$user = $this->actor;
				$rel_id = _elgg_services()->relationshipsTable->add($object->guid, 'test_relationship', $user->guid, true);
				if (empty($rel_id)) {
					break;
				}

				return elgg_get_relationship($rel_id);
		}

		throw new Exception("Test object not found for {$this->test_object_class} class");
	}

	public function testRegisterEvent() {
		$this->setupServices();

		$object = $this->getTestObject();

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());
		$events = [
			$object->getType() => [
				$object->getSubtype() => [
					'create' => NotificationEventHandler::class,
				],
			]
		];
		$this->assertEquals($events, $this->notifications->getEvents());

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);
		$events[$object->getType()][$object->getSubtype()] = [
			'create' => NotificationEventHandler::class,
			'test_event' => NotificationEventHandler::class,
		];
		$this->assertEquals($events, $this->notifications->getEvents());

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());
		$this->assertEquals($events, $this->notifications->getEvents());
	}

	public function testUnregisterEvent() {
		$this->setupServices();

		$object = $this->getTestObject();

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());
		$this->notifications->unregisterEvent($object->getType(), $object->getSubtype());

		$this->assertEquals([], $this->notifications->getEvents());
		$this->notifications->unregisterEvent($object->getType(), $object->getSubtype());
	}
	
	public function testUnregisterEventSpecificAction() {
		$this->setupServices();

		$object = $this->getTestObject();

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['create', 'delete']);
		
		// unregister one action
		$this->notifications->unregisterEvent($object->getType(), $object->getSubtype(), ['create']);

		$events = [
			$object->getType() => [
				$object->getSubtype() => ['delete' => NotificationEventHandler::class],
			],
		];
		$this->assertEquals($events, $this->notifications->getEvents());
		
		// unregister last remaining action
		$this->notifications->unregisterEvent($object->getType(), $object->getSubtype(), ['delete']);
		
		$this->assertEquals([], $this->notifications->getEvents());
	}

	public function testRegisterMethod() {
		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$methods = ['test_method' => 'test_method'];
		$this->assertEquals($methods, $this->notifications->getMethods());
	}

	public function testUnregisterMethod() {
		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->notifications->unregisterMethod('test_method');
		$this->assertEquals([], $this->notifications->getMethods());
	}

	public function testEnqueueEventLoggedInUser() {
		$this->setupServices();

		$object = $this->getTestObject();

		$this->session_manager->setLoggedInUser($this->actor);

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());

		$this->notifications->enqueueEvent('create', $object);

		$event = new SubscriptionNotificationEvent($object, 'create');
		$this->assertEquals(unserialize(serialize($event)), $this->queue->dequeue());
		$this->assertNull($this->queue->dequeue());

		// unregistered action type
		$this->notifications->enqueueEvent('null', $object);
		$this->assertNull($this->queue->dequeue());

		// unregistered object type
		$this->notifications->enqueueEvent('create', new \ElggObject());
		$this->assertNull($this->queue->dequeue());
	}
	
	public function testEnqueueEventProvidedActor() {
		$this->setupServices();

		$object = $this->getTestObject();

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());

		$this->notifications->enqueueEvent('create', $object, $this->actor);

		$event = new SubscriptionNotificationEvent($object, 'create', $this->actor);
		$this->assertEquals(unserialize(serialize($event)), $this->queue->dequeue());
		$this->assertNull($this->queue->dequeue());

		// unregistered action type
		$this->notifications->enqueueEvent('null', $object);
		$this->assertNull($this->queue->dequeue());

		// unregistered object type
		$this->notifications->enqueueEvent('create', new \ElggObject());
		$this->assertNull($this->queue->dequeue());
	}
	
	public function testEnqueueEventFallbackToOwner() {
		$this->setupServices();

		$object = $this->getTestObject();

		$actor = null;
		if ($object instanceof \ElggEntity || $object instanceof \ElggExtender) {
			$actor = $object->getOwnerEntity() ?: null;
		}
		
		$this->notifications->registerEvent($object->getType(), $object->getSubtype());

		$this->notifications->enqueueEvent('create', $object, $actor);

		$event = new SubscriptionNotificationEvent($object, 'create', $actor);
		$this->assertEquals(unserialize(serialize($event)), $this->queue->dequeue());
		$this->assertNull($this->queue->dequeue());

		// unregistered action type
		$this->notifications->enqueueEvent('null', $object);
		$this->assertNull($this->queue->dequeue());

		// unregistered object type
		$this->notifications->enqueueEvent('create', new \ElggObject());
		$this->assertNull($this->queue->dequeue());
	}

	public function testStoppingEnqueueEvent() {

		$mock = $this->createMock(EventsService::class, ['trigger']);
		$mock->expects($this->once())
			->method('triggerResults')
			->will($this->returnValue(false));

		$this->events = $mock;

		$this->setupServices();

		$object = $this->getTestObject();

		$this->session_manager->setLoggedInUser($this->actor);

		$this->notifications->registerEvent($object->getType(), $object->getSubtype());

		$this->notifications->enqueueEvent('create', $object);
		$this->assertNull($this->queue->dequeue());
	}

	public function testProcessQueueNoEvents() {
		$this->setupServices();
		$this->assertEquals(0, $this->notifications->processQueue($this->time + 10));
	}

	public function testProcessQueueThreeEvents() {

		$mock = $this->createMock(SubscriptionsService::class, ['getNotificationEventSubscriptions'], [], '', false);
		$mock->expects($this->exactly(3))
			->method('getNotificationEventSubscriptions')
			->will($this->returnValue([]));

		_elgg_services()->subscriptions = $mock;

		$this->setupServices();

		$object = $this->getTestObject();

		$this->session_manager->setLoggedInUser($this->actor);

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), [
			'event1',
			'event2',
			'event3'
		]);

		$this->notifications->enqueueEvent('event1', $object);
		$this->notifications->enqueueEvent('event2', $object);
		$this->notifications->enqueueEvent('event3', $object);

		$this->session_manager->removeLoggedInUser();

		$this->assertEquals(3, $this->notifications->processQueue($this->time + 10));
		_elgg_services()->reset('subscriptions');
	}

	public function testProcessQueueTimesout() {

		$mock = $this->createMock(SubscriptionsService::class, ['getNotificationEventSubscriptions'], [], '', false);
		$mock->expects($this->exactly(0))
			->method('getNotificationEventSubscriptions')
			->will($this->returnValue([]));

		_elgg_services()->subscriptions = $mock;

		$this->setupServices();

		$object = $this->getTestObject();

		$this->session_manager->setLoggedInUser($this->actor);

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), [
			'event1',
			'event2',
			'event3'
		]);

		$this->notifications->enqueueEvent('event1', $object);
		$this->notifications->enqueueEvent('event2', $object);
		$this->notifications->enqueueEvent('event3', $object);

		$this->session_manager->removeLoggedInUser();

		$this->assertEquals(0, $this->notifications->processQueue($this->time));
		
		_elgg_services()->reset('subscriptions');
	}

	public function testCanUseEnqueueEventToPreventSubscriptionNotificationEventFromQueueing() {

		$object = $this->getTestObject();

		$call_count = 0;

		$recipient = $this->createUser([
			'language' => 'en',
		]);

		$mock = $this->createMock(SubscriptionsService::class, ['getNotificationEventSubscriptions'], [], '', false);
		$mock->expects($this->exactly(0))
			->method('getNotificationEventSubscriptions')
			->will($this->returnValue([
				$recipient->guid => [
					'test_method',
					'bad_method'
				],
			]));

		_elgg_services()->subscriptions = $mock;

		$this->events->registerHandler('enqueue', 'notification', function (\Elgg\Event $event) use (&$call_count, $object) {
			$call_count++;
			$this->assertEquals($object, $event->getParam('object'));
			$this->assertEquals('test_event', $event->getParam('action'));

			return false;
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');

		$this->session_manager->setLoggedInUser($this->actor);

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);

		$this->assertEquals(0, $this->queue->size());
		$this->notifications->enqueueEvent('test_event', $object);
		$this->assertEquals(1, $call_count);
		$this->assertEquals(0, $this->queue->size());

		$this->session_manager->removeLoggedInUser();

		$this->assertEquals(0, $this->notifications->processQueue($this->time + 10));
		
		_elgg_services()->reset('subscriptions');
	}

	public function testCanUseEventsBeforeAndAfterSubscriptionNotificationsQueue() {

		$object = $this->getTestObject();

		$before_call_count = 0;
		$after_call_count = 0;

		$recipient = $this->createUser();
		$subscribers = [
			$recipient->guid => [
				'test_method',
				'bad_method'
			],
		];
		$mock = $this->createMock(SubscriptionsService::class, ['getNotificationEventSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
			->method('getNotificationEventSubscriptions')
			->will($this->returnValue($subscribers));
		$mock->expects($this->exactly(1))
			->method('filterSubscriptions')
			->will($this->returnValue($subscribers));

		_elgg_services()->subscriptions = $mock;

		$this->session_manager->setLoggedInUser($this->actor);

		$event = new SubscriptionNotificationEvent($object, 'test_event');

		$this->events->registerHandler('send:before', 'notifications', function (\Elgg\Event $elgg_event) use (&$before_call_count, $event, $subscribers, $object) {
			$before_call_count++;
			$this->assertEquals(unserialize(serialize($event)), $elgg_event->getParam('event'));
			$this->assertEquals($subscribers, $elgg_event->getParam('subscriptions'));

			return false;
		});

		$this->events->registerHandler('send:after', 'notifications', function (\Elgg\Event $elgg_event) use (&$after_call_count, $event, $subscribers, $object) {
			$after_call_count++;
			$this->assertEquals(unserialize(serialize($event)), $elgg_event->getParam('event'));
			$this->assertEquals($subscribers, $elgg_event->getParam('subscriptions'));
			$this->assertEmpty($elgg_event->getParam('deliveries'));
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);

		$this->assertEquals(0, $this->queue->size());
		$this->notifications->enqueueEvent('test_event', $object);
		$this->assertEquals(1, $this->queue->size());

		$this->session_manager->removeLoggedInUser();

		$this->assertEquals(1, $this->notifications->processQueue($this->time + 10));

		$this->assertEquals(1, $before_call_count);
		$this->assertEquals(1, $after_call_count);
		
		_elgg_services()->reset('subscriptions');
	}

	public function testCanProcessSubscriptionNotificationsQueue() {

		$object = $this->getTestObject();

		$call_count = 0;

		$recipient = $this->createUser();

		$mock = $this->createMock(SubscriptionsService::class, ['getNotificationEventSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
			->method('getNotificationEventSubscriptions')
			->will($this->returnValue([
				$recipient->guid => [
					'test_method',
					'bad_method'
				],
			]));
		$mock->expects($this->exactly(1))
			->method('filterSubscriptions')
			->will($this->returnValue([
				$recipient->guid => [
					'test_method',
					'bad_method'
				],
			]));

		_elgg_services()->subscriptions = $mock;

		$this->session_manager->setLoggedInUser($this->actor);

		$event = new SubscriptionNotificationEvent($object, 'test_event');

		$this->translator->addTranslation('en', [
			'notification:body' => 'Link: %s',
			'notification:subject' => 'From: %s',
		]);

		$this->events->registerHandler('send', 'notification:test_method', function (\Elgg\Event $elgg_event) use (&$call_count, $event, $recipient) {
			$call_count++;
			$this->assertInstanceOf(Notification::class, $elgg_event->getParam('notification'));
			$this->assertEquals($this->translator->translate('notification:subject', [$event->getActor()->name], $recipient->language), $elgg_event->getParam('notification')->subject);
			$this->assertStringContainsString($this->translator->translate('notification:body', [$event->getObject()->getURL()], $recipient->language), $elgg_event->getParam('notification')->body);
			$this->assertEquals($event->toObject(), $elgg_event->getParam('event')->toObject());

			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);

		$this->assertEquals(0, $this->queue->size());
		$this->notifications->enqueueEvent('test_event', $object);

		$event = $this->queue->dequeue();
		$this->assertInstanceOf(SubscriptionNotificationEvent::class, $event);
		$this->assertEquals(elgg_get_logged_in_user_entity(), $event->getActor());
		$this->assertEquals($object, $event->getObject());
		$this->assertEquals("test_event:{$object->getType()}:{$object->getSubtype()}", $event->getDescription());

		$this->notifications->enqueueEvent('test_event', $object);
		$this->assertEquals(1, $this->queue->size());

		$deliveries = [
			"test_event:{$object->getType()}:{$object->getSubtype()}" => [
				$recipient->guid => [
					'test_method' => true,
					'bad_method' => false,
				]
			]
		];

		$this->session_manager->removeLoggedInUser();

		$result = $this->notifications->processQueue($this->time + 10, true);
		$this->assertEquals(1, $call_count);
		$this->assertEquals($deliveries, $result);
		
		_elgg_services()->reset('subscriptions');
	}

	public function testCanAlterSubscriptionNotificationTranslations() {

		$object = $this->getTestObject();

		$call_count = 0;

		$recipient = $this->createUser([
			'language' => 'en',
		]);

		$mock = $this->createMock(SubscriptionsService::class, ['getNotificationEventSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
			->method('getNotificationEventSubscriptions')
			->will($this->returnValue([
				$recipient->guid => [
					'test_method',
					'bad_method'
				],
			]));
		$mock->expects($this->exactly(1))
			->method('filterSubscriptions')
			->will($this->returnValue([
				$recipient->guid => [
					'test_method',
					'bad_method'
				],
			]));
			
		_elgg_services()->subscriptions = $mock;

		$this->session_manager->setLoggedInUser($this->actor);

		$event = new SubscriptionNotificationEvent($object, 'test_event');

		$this->translator->addTranslation('en', [
			"notification:{$event->getDescription()}:body" => '%s %s %s %s %s',
			"notification:{$event->getDescription()}:subject" => '%s %s',
		]);

		$this->events->registerHandler('send', 'notification:test_method', function (\Elgg\Event $elgg_event) use (&$call_count, $event, $object, $recipient) {
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
			$this->assertInstanceOf(Notification::class, $elgg_event->getParam('notification'));
			$this->assertEquals($this->translator->translate("notification:{$event->getDescription()}:subject", [
				$event->getActor()->name,
				$display_name,
			], $recipient->language), $elgg_event->getParam('notification')->subject);
			$this->assertStringContainsString($this->translator->translate("notification:{$event->getDescription()}:body", [
				$event->getActor()->name,
				$display_name,
				$container_name,
				$object->description,
				$object->getURL(),
			], $recipient->language), $elgg_event->getParam('notification')->body);
			$this->assertEquals($event, $elgg_event->getParam('event'));

			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);
		$this->notifications->enqueueEvent('test_event', $object);

		$this->session_manager->removeLoggedInUser();

		$this->notifications->processQueue($this->time + 10);

		$this->assertEquals(1, $call_count);
		
		_elgg_services()->reset('subscriptions');
	}

	public function testCanPrepareSubscriptionNotification() {

		$object = $this->getTestObject();

		$recipient = $this->createUser([
			'language' => 'en',
		]);

		$mock = $this->createMock(SubscriptionsService::class, ['getNotificationEventSubscriptions'], [], '', false);
		$mock->expects($this->exactly(1))
			->method('getNotificationEventSubscriptions')
			->will($this->returnValue([
					$recipient->guid => [
						'test_method',
						'bad_method'
					],
				]
			));

		_elgg_services()->subscriptions = $mock;

		$this->session_manager->setLoggedInUser($this->actor);

		$event = new SubscriptionNotificationEvent($object, 'test_event');

		$this->events->registerHandler('prepare', 'notification', function (\Elgg\Event $event) {
			$notification = $event->getValue();
			$notification->prepare_hook = true;

			return $notification;
		});

		$this->events->registerHandler('prepare', "notification:{$event->getDescription()}", function (\Elgg\Event $event) {
			$notification = $event->getValue();
			$notification->granular_prepare_hook = true;

			return $notification;
		});

		$this->events->registerHandler('format', 'notification:test_method', function (\Elgg\Event $event) {
			$notification = $event->getValue();
			$notification->format_hook = true;

			return $notification;
		});

		$this->events->registerHandler('send', 'notification:test_method', function (\Elgg\Event $event) {
			$notification = $event->getParam('notification');
			$this->assertTrue($notification->prepare_hook);
			$this->assertTrue($notification->granular_prepare_hook);
			$this->assertTrue($notification->format_hook);

			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);
		$this->notifications->enqueueEvent('test_event', $object);

		$this->session_manager->removeLoggedInUser();

		$this->assertEquals(1, $this->notifications->processQueue($this->time + 10));
		
		_elgg_services()->reset('subscriptions');
	}

	public function testValidatesObjectExistenceForDequeuedSubscriptionNotificationEvent() {

		// This test can be enabled once objects table operations such as delete are mocked
		$this->markTestSkipped();

		$object = $this->getTestObject();

		$mock = $this->createMock(SubscriptionsService::class, ['getNotificationEventSubscriptions'], [], '', false);
		$mock->expects($this->exactly(0))
			->method('getNotificationEventSubscriptions')
			->will($this->returnValue([]));

		_elgg_services()->subscriptions = $mock;

		$this->setupServices();

		$this->notifications->registerMethod('test_method');

		$this->session_manager->setLoggedInUser($this->actor);

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);
		$this->notifications->enqueueEvent('test_event', $object);

		$object->delete();

		$this->session_manager->removeLoggedInUser();

		$this->assertEquals(0, $this->notifications->processQueue($this->time + 10));
		
		_elgg_services()->reset('subscriptions');
	}

	public function testValidatesActorExistenceForDequeuedSubscriptionNotificationEvent() {

		// This test can be enabled once users table operations such as delete/ban are mocked
		$this->markTestSkipped();

		$object = $this->getTestObject();

		$mock = $this->createMock(SubscriptionsService::class, ['getNotificationEventSubscriptions'], [], '', false);
		$mock->expects($this->exactly(0))
			->method('getNotificationEventSubscriptions')
			->will($this->returnValue([]));

		_elgg_services()->subscriptions = $mock;

		$this->setupServices();

		$this->notifications->registerMethod('test_method');

		$this->session_manager->setLoggedInUser($this->actor);

		$this->notifications->registerEvent($object->getType(), $object->getSubtype(), ['test_event']);
		$this->notifications->enqueueEvent('test_event', $object);

		$this->session_manager->removeLoggedInUser();

		$this->assertEquals(0, $this->notifications->processQueue($this->time + 10));
		
		_elgg_services()->reset('subscriptions');
	}

	/**
	 * @group InstantNotificationsService
	 */
	public function testCanNotifyUser() {

		$object = $this->getTestObject();

		$from = $this->createUser();
		$to1 = $this->createUser();

		$to2 = $this->createUser();
		$to2->setNotificationSetting('test_method', true);

		$to3 = $this->createUser();

		$subject = 'Test message';
		$body = 'Lorem ipsum';

		$event = new InstantNotificationEvent($object, 'notify_user', $from);

		$this->events->registerHandler('get', 'subscriptions', function (\Elgg\Event $event) use ($to3) {
			$return = $event->getValue();
			$return[$to3->guid] = [
				'test_method'
			];

			return $return;
		});

		$this->events->registerHandler('prepare', 'notification', function (\Elgg\Event $event) {
			$notification = $event->getValue();
			$notification->prepare_hook = true;

			return $notification;
		});

		$this->events->registerHandler('prepare', "notification:{$event->getDescription()}", function (\Elgg\Event $event) {
			$notification = $event->getValue();
			$notification->granular_prepare_hook = true;

			return $notification;
		});

		$this->events->registerHandler('format', 'notification:test_method', function (\Elgg\Event $event) {
			$notification = $event->getValue();
			$notification->format_hook = true;

			return $notification;
		});

		$sent = 0;
		$this->events->registerHandler('send', 'notification:test_method', function (\Elgg\Event $elgg_event) use (&$sent, $subject, $body, $event) {
			$sent++;
			$notification = $elgg_event->getParam('notification');

			$this->assertInstanceOf(Notification::class, $notification);
			$this->assertEquals($notification->subject, $subject);
			$this->assertStringContainsString($body, $notification->body);
			$this->assertEquals($notification->summary, $subject);
			$this->assertEquals($event->toObject(), $elgg_event->getParam('event')->toObject());

			$this->assertTrue($notification->prepare_hook);
			$this->assertTrue($notification->granular_prepare_hook);
			$this->assertTrue($notification->format_hook);

			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->notifications->registerMethod('test_method2');

		$expected = [
			$to2->guid => [
				'test_method' => true,
			],
			$to3->guid => [
				'test_method' => true,
			]
		];

		$this->assertEquals($expected, notify_user([
			$to1->guid,
			$to2->guid,
			0
		], $from->guid, $subject, $body, [
			'object' => $object,
			'summary' => $subject,
		]));

		$this->assertEquals(2, $sent);
	}

	/**
	 * @group InstantNotificationsService
	 */
	public function testCanNotifyUserWithoutAnObject() {

		$from = $this->createUser();
		$to1 = $this->createUser();

		$to2 = $this->createUser();
		$to2->setNotificationSetting('test_method', true);

		$to3 = $this->createUser();

		$subject = 'Test message';
		$body = 'Lorem ipsum';

		$event = new InstantNotificationEvent(null, null, $from);

		$this->events->registerHandler('get', 'subscriptions', function (\Elgg\Event $event) use ($to3) {
			$return = $event->getValue();
			$return[$to3->guid] = ['test_method'];

			return $return;
		});

		$this->events->registerHandler('prepare', 'notification', function (\Elgg\Event $event) {
			$notification = $event->getValue();
			$notification->prepare_hook = true;

			return $notification;
		});

		$this->events->registerHandler('prepare', "notification:{$event->getDescription()}", function (\Elgg\Event $event) {
			$notification = $event->getValue();
			$notification->granular_prepare_hook = true;

			return $notification;
		});

		$this->events->registerHandler('format', 'notification:test_method', function (\Elgg\Event $event) {
			$notification = $event->getValue();
			$notification->format_hook = true;

			return $notification;
		});

		$sent = 0;
		$this->events->registerHandler('send', 'notification:test_method', function (\Elgg\Event $elgg_event) use (&$sent, $subject, $body, $event) {
			$sent++;
			$notification = $elgg_event->getParam('notification');

			$this->assertInstanceOf(Notification::class, $notification);
			$this->assertEquals($subject, $notification->subject);
			$this->assertStringContainsString($body, $notification->body);
			$this->assertEquals($event->toObject(), $elgg_event->getParam('event')->toObject());

			$this->assertTrue($notification->prepare_hook);
			$this->assertTrue($notification->granular_prepare_hook);
			$this->assertTrue($notification->format_hook);

			return true;
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->notifications->registerMethod('test_method2');

		$expected = [
			$to2->guid => [
				'test_method' => true,
			],
			$to3->guid => [
				'test_method' => true,
			]
		];

		$this->assertEquals($expected, notify_user([
			$to1->guid,
			$to2->guid,
			0
		], $from->guid, $subject, $body));

		$this->assertEquals(2, $sent);
	}

	/**
	 * @group InstantNotificationsService
	 */
	public function testCanUseEventsBeforeAndAfterInstantNotificationsQueue() {

		$object = $this->getTestObject();

		$from = $this->createUser();
		$to1 = $this->createUser();

		$to2 = $this->createUser();
		$to2->setNotificationSetting('test_method', true);

		$subject = 'Test message';
		$body = 'Lorem ipsum';

		$subscribers = [
			$to1->guid => [],
			$to2->guid => ['test_method'],
		];

		$event = new InstantNotificationEvent($object, 'test_event', $from);

		$before_call_count = 0;
		$after_call_count = 0;

		$this->events->registerHandler('send:before', 'notifications', function (\Elgg\Event $elgg_event) use (&$before_call_count, $event, $subscribers) {
			$before_call_count++;
			$this->assertEquals($event->toObject(), $elgg_event->getParam('event')->toObject());
			$this->assertEquals($subscribers, $elgg_event->getParam('subscriptions'));

			return false;
		});

		$this->events->registerHandler('send:after', 'notifications', function (\Elgg\Event $elgg_event) use (&$after_call_count, $event, $subscribers) {
			$after_call_count++;
			$this->assertEquals($event, $elgg_event->getParam('event'));
			$this->assertEquals($subscribers, $elgg_event->getParam('subscriptions'));
			$this->assertEmpty($elgg_event->getParam('deliveries'));
		});

		$this->setupServices();

		$this->notifications->registerMethod('test_method');

		$this->assertEquals([], notify_user([
			$to1->guid,
			$to2->guid,
			0
		], $from->guid, $subject, $body, [
			'object' => $object,
			'summary' => $subject,
			'action' => 'test_event',
		]));

		$this->assertEquals(1, $before_call_count);
		$this->assertEquals(1, $after_call_count);
	}

	/**
	 * @group InstantNotificationsService
	 */
	public function testCanNotifyUserViaCustomMethods() {
		
		$from = $this->createUser();
		$to1 = $this->createUser();
		$to1->setNotificationSetting('test_method', true);

		$to2 = $this->createUser();
		$to2->setNotificationSetting('test_method', true);

		$subject = 'Test message';
		$body = 'Lorem ipsum';
		$this->events->registerHandler('send', 'notification:test_method', [
			Values::class,
			'getFalse'
		]);
		$this->events->registerHandler('send', 'notification:test_method2', [
			Values::class,
			'getTrue'
		]);

		$this->setupServices();

		$this->notifications->registerMethod('test_method');
		$this->notifications->registerMethod('test_method2');

		$expected = [
			$to1->guid => [
				'test_method2' => true,
			],
			$to2->guid => [
				'test_method2' => true,
			]
		];

		$this->assertEquals($expected, notify_user([
			$to1->guid,
			$to2->guid,
			0
		], $from->guid, $subject, $body, [], 'test_method2'));
	}
}
