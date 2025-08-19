<?php

namespace Elgg\Notifications;

use Elgg\Helpers\Notifications\TestNotificationHandler;
use Elgg\IntegrationTestCase;

class NotificationEventHandlerIntegrationTest extends IntegrationTestCase {
	
	protected function prepareInstantNotificationEvent(): InstantNotificationEvent {
		$object = $this->createObject();
		$actor = $this->getRandomUser();
		return new InstantNotificationEvent($object, 'create', $actor);
	}
	
	protected function prepareNotificationEventHandler(NotificationEvent $event, array $params = []): NotificationEventHandler {
		return new NotificationEventHandler($event, _elgg_services()->notifications, $params);
	}
	
	public function testFilterMutedSubscribers() {
		$event = $this->prepareInstantNotificationEvent();
		
		$subscribed = $this->createUser();
		$muted = $this->createUser();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$owner = $object->getOwnerEntity();
		
		$object->addSubscription($subscribed->guid);
		$owner->addSubscription($muted->guid);
		$object->muteNotifications($muted->guid);
		
		$filtered = $this->prepareNotificationEventHandler($event);
		
		$subscribers = $this->invokeInaccessableMethod($filtered, 'prepareSubscriptions');
		$this->assertIsArray($subscribers);
		$this->assertArrayHasKey($subscribed->guid, $subscribers);
		$this->assertArrayNotHasKey($muted->guid, $subscribers);
		
		$not_filtered = $this->prepareNotificationEventHandler($event, ['apply_muting' => false]);
		
		$subscribers = $this->invokeInaccessableMethod($not_filtered, 'prepareSubscriptions');
		$this->assertIsArray($subscribers);
		$this->assertArrayHasKey($subscribed->guid, $subscribers);
		$this->assertArrayHasKey($muted->guid, $subscribers);
	}
	
	public function testGetEventActorWithNonUser() {
		$object = $this->createObject();
		$actor = $this->createObject();
		
		$event = new InstantNotificationEvent($object, 'create', $actor);
		
		$handler = $this->prepareNotificationEventHandler($event);
		
		$this->assertNull($this->invokeInaccessableMethod($handler, 'getEventActor'));
	}

	public function testGetEventActorWithUser() {
		$event = $this->prepareInstantNotificationEvent();
		
		$handler = $this->prepareNotificationEventHandler($event);
		
		$this->assertInstanceOf(\ElggUser::class, $this->invokeInaccessableMethod($handler, 'getEventActor'));
	}
	
	public function testGetEventEntityWithoutEntity() {
		$actor = $this->createUser();
		
		$object = new \ElggMetadata();
		$object->name = 'foo';
		$object->value = 'bar';
		$object->entity_guid = $actor->guid;
		
		$event = new InstantNotificationEvent($object, 'create', $actor);
		
		$handler = $this->prepareNotificationEventHandler($event);
		
		$this->assertNull($this->invokeInaccessableMethod($handler, 'getEventEntity'));
	}

	public function testGetEventEntityWithEntity() {
		$event = $this->prepareInstantNotificationEvent();
		
		$handler = $this->prepareNotificationEventHandler($event);
		
		$this->assertInstanceOf(\ElggEntity::class, $this->invokeInaccessableMethod($handler, 'getEventEntity'));
	}
	
	public function testGetNotificationAttachmentsEmpty() {
		$event = $this->prepareInstantNotificationEvent();
		
		$handler = $this->prepareNotificationEventHandler($event);
		
		$attachments = $this->invokeInaccessableMethod($handler, 'getNotificationAttachments', $event->getActor(), 'foo');
		$this->assertIsArray($attachments);
		$this->assertEmpty($attachments);
	}
	
	public function testNotificationCustomHandler() {
		$this->createApplication([
			'isolate' => true,
		]);
		
		$notification_event = $this->prepareInstantNotificationEvent();
		$handler = new TestNotificationHandler($notification_event, _elgg_services()->notifications);
		
		_elgg_services()->notifications->registerMethod('test_method');
		$method_event = $this->registerTestingEvent('send', 'notification:test_method', function(\Elgg\Event $event) use ($notification_event, $handler) {
			$notification = $event->getParam('notification');
			$this->assertInstanceOf(Notification::class, $notification);
			
			$this->assertArrayHasKey('attachments', $notification->params);
			$this->assertEquals($notification->params['attachments'], $this->invokeInaccessableMethod($handler, 'getNotificationAttachments', $notification_event->getActor(), 'attachment_method'));
			
			$this->assertArrayHasKey('subject', $notification->params);
			$this->assertEquals($notification->params['subject'], $this->invokeInaccessableMethod($handler, 'getNotificationSubject', $notification_event->getActor(), 'attachment_method'));
			
			$this->assertArrayHasKey('summary', $notification->params);
			$this->assertEquals($notification->params['summary'], $this->invokeInaccessableMethod($handler, 'getNotificationSummary', $notification_event->getActor(), 'attachment_method'));
			
			$this->assertArrayHasKey('body', $notification->params);
			$this->assertEquals($notification->params['body'], $this->invokeInaccessableMethod($handler, 'getNotificationBody', $notification_event->getActor(), 'attachment_method'));
			
			$this->assertArrayHasKey('url', $notification->params);
			$this->assertEquals($notification->params['url'], $this->invokeInaccessableMethod($handler, 'getNotificationURL', $notification_event->getActor(), 'attachment_method'));
			
			return true;
		});
		
		$result = $handler->send();
		
		$this->assertIsArray($result);
		$this->assertArrayHasKey($notification_event->getActorGUID(), $result);
		
		$method_event->assertNumberOfCalls(1);
		$method_event->assertValueBefore(false);
		$method_event->assertValueAfter(true);
	}
	
	public function testNotificationCustomHandlerWithParams() {
		$this->createApplication([
			'isolate' => true,
		]);
		
		$notification_event = $this->prepareInstantNotificationEvent();
		$params = [
			'subject' => __METHOD__ . ' subject',
			'summary' => __METHOD__ . ' summary',
			'body' => __METHOD__ . ' body',
			'url' => __METHOD__ . ' url',
			'attachments' => [
				[
					'filepath' => __FILE__,
					'filename' => 'NotificationEventHandlerIntegrationTest.php',
					'type' => 'application/x-php',
				],
			],
		];
		$handler = new TestNotificationHandler($notification_event, _elgg_services()->notifications, $params);
		
		_elgg_services()->notifications->registerMethod('test_method');
		$method_event = $this->registerTestingEvent('send', 'notification:test_method', function(\Elgg\Event $event) use ($notification_event, $handler, $params) {
			$notification = $event->getParam('notification');
			$this->assertInstanceOf(Notification::class, $notification);
			
			$this->assertArrayHasKey('attachments', $notification->params);
			$this->assertNotEquals($notification->params['attachments'], $this->invokeInaccessableMethod($handler, 'getNotificationAttachments', $notification_event->getActor(), 'attachment_method'));
			$this->assertEquals($notification->params['attachments'], $params['attachments']);
			
			$this->assertArrayHasKey('subject', $notification->params);
			$this->assertNotEquals($notification->params['subject'], $this->invokeInaccessableMethod($handler, 'getNotificationSubject', $notification_event->getActor(), 'attachment_method'));
			$this->assertEquals($notification->params['subject'], $params['subject']);
			
			$this->assertArrayHasKey('summary', $notification->params);
			$this->assertNotEquals($notification->params['summary'], $this->invokeInaccessableMethod($handler, 'getNotificationSummary', $notification_event->getActor(), 'attachment_method'));
			$this->assertEquals($notification->params['summary'], $params['summary']);
			
			$this->assertArrayHasKey('body', $notification->params);
			$this->assertNotEquals($notification->params['body'], $this->invokeInaccessableMethod($handler, 'getNotificationBody', $notification_event->getActor(), 'attachment_method'));
			$this->assertEquals($notification->params['body'], $params['body']);
			
			$this->assertArrayHasKey('url', $notification->params);
			$this->assertNotEquals($notification->params['url'], $this->invokeInaccessableMethod($handler, 'getNotificationURL', $notification_event->getActor(), 'attachment_method'));
			$this->assertEquals($notification->params['url'], $params['url']);
			
			return true;
		});
		
		$result = $handler->send();
		
		$this->assertIsArray($result);
		$this->assertArrayHasKey($notification_event->getActorGUID(), $result);
		
		$method_event->assertNumberOfCalls(1);
		$method_event->assertValueBefore(false);
		$method_event->assertValueAfter(true);
	}
}
