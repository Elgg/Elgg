<?php

namespace Elgg\Notifications;

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
}
