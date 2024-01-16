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
		
		$class = new \ReflectionClass($filtered);
		
		$subscribers = $class->getMethod('prepareSubscriptions')->invoke($filtered);
		$this->assertIsArray($subscribers);
		$this->assertArrayHasKey($subscribed->guid, $subscribers);
		$this->assertArrayNotHasKey($muted->guid, $subscribers);
		
		$not_filtered = $this->prepareNotificationEventHandler($event, ['apply_muting' => false]);
		
		$class = new \ReflectionClass($not_filtered);
		
		$subscribers = $class->getMethod('prepareSubscriptions')->invoke($not_filtered);
		$this->assertIsArray($subscribers);
		$this->assertArrayHasKey($subscribed->guid, $subscribers);
		$this->assertArrayHasKey($muted->guid, $subscribers);
	}
}
