<?php

namespace Elgg\Notifications;

use Elgg\IntegrationTestCase;
use Elgg\TestableHook;

class SubscriptionServiceIntegrationTest extends IntegrationTestCase {

	/**
	 * @var SubscriptionsService
	 */
	protected $service;
	
	/**
	 * @var \ElggEntity[]
	 */
	protected $entities;
	
	/**
	 * @var TestableHook
	 */
	protected $testing_hook;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
		_elgg_services()->notifications->registerMethod('apples');
		_elgg_services()->notifications->registerMethod('bananas');
		
		$this->service = _elgg_services()->subscriptions;
		$this->entities = [];
		$this->testing_hook = null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		foreach ($this->entities as $entity) {
			$entity->delete();
		}
		
		if ($this->testing_hook instanceof TestableHook) {
			$this->testing_hook->unregister();
		}
	}
	
	protected function getSubscriptionNotificationEvent(): SubscriptionNotificationEvent {
		$this->entities[] = $actor = $this->createUser();
		$this->entities[] = $owner = $this->createUser();
		$this->entities[] = $container = $this->createGroup();
		$this->entities[] = $object = $this->createObject([
			'owner_guid' => $owner->guid,
			'container_guid' => $container->guid,
		]);
		
		return new SubscriptionNotificationEvent($object, 'create', $actor);
	}
	
	public function testAddRemoveSubscription() {
		$this->entities[] = $user = $this->createUser();
		$this->entities[] = $target = $this->createGroup();
		
		// add subscription
		$this->assertTrue($this->service->addSubscription($user->guid, 'apples', $target->guid));
		
		$expected = [
			$user->guid => ['apples'],
		];
		$this->assertEquals($expected, $this->service->getSubscriptionsForContainer($target->guid, ['apples', 'bananas']));
		
		// remove subscription
		$this->assertTrue($this->service->removeSubscription($user->guid, 'apples', $target->guid));
		// remove non existing subscription
		$this->assertTrue($this->service->removeSubscription($user->guid, 'bananas', $target->guid));
		
		// check if all was removed
		$this->assertEmpty($this->service->getSubscriptionsForContainer($target->guid, ['apples', 'bananas']));
	}
	
	public function testAddRemoveDetailedSubscription() {
		$this->entities[] = $user = $this->createUser();
		$this->entities[] = $target = $this->createGroup();
		$this->entities[] = $object = $this->createObject([
			'subtype' => 'foo',
			'access_id' => ACCESS_PUBLIC,
			'container_guid' => $target->guid,
		]);
		$this->entities[] = $object2 = $this->createObject([
			'subtype' => 'bar',
			'access_id' => ACCESS_PUBLIC,
			'container_guid' => $target->guid,
		]);
		
		$event = new SubscriptionNotificationEvent($object, 'create', $object->getOwnerEntity());
		$event2 = new SubscriptionNotificationEvent($object2, 'create', $object->getOwnerEntity());
		
		// add subscription
		$this->assertTrue($this->service->addSubscription($user->guid, 'apples', $target->guid, $object->type, $object->subtype, $event->getAction()));
		
		// check subscriptions
		$expected = [
			$user->guid => ['apples'],
		];
		$this->assertEquals($expected, $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
		
		// test with a different notification
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event2, ['apples', 'bananas']));
		
		// remove subscription
		$this->assertTrue($this->service->removeSubscription($user->guid, 'apples', $target->guid, $object->type, $object->subtype, $event->getAction()));
		
		// remove non existing subscription
		$this->assertTrue($this->service->removeSubscription($user->guid, 'bananas', $target->guid, $object->type, $object->subtype, $event->getAction()));
		
		// check if all was removed
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
	}
	
	public function testMuteUnmuteNotifications() {
		$this->entities[] = $user = $this->createUser();
		$this->entities[] = $target = $this->createGroup();
		
		// add subscription
		$this->assertTrue($this->service->addSubscription($user->guid, 'apples', $target->guid));
		$this->assertTrue($this->service->addSubscription($user->guid, 'apples', $target->guid, 'object', 'foo', 'create'));
		
		// mute notifications
		$this->assertTrue($this->service->muteNotifications($user->guid, $target->guid));
		// fails on duplicate
		$this->assertFalse($this->service->muteNotifications($user->guid, $target->guid));
		
		// no subscription should be left
		$this->assertFalse($this->service->hasSubscription($user->guid, 'apples', $target->guid));
		$this->assertFalse($this->service->hasSubscription($user->guid, 'apples', $target->guid, 'object', 'foo', 'create'));
		
		// check muted
		$this->assertTrue($this->service->hasMutedNotifications($user->guid, $target->guid));
		
		// remove muted notifications
		$this->assertTrue($this->service->unmuteNotifications($user->guid, $target->guid));
		// fails if not exists
		$this->assertFalse($this->service->unmuteNotifications($user->guid, $target->guid));
		
		// check muted
		$this->assertFalse($this->service->hasMutedNotifications($user->guid, $target->guid));
		
		// no subscription should be present
		$this->assertFalse($this->service->hasSubscription($user->guid, 'apples', $target->guid));
		$this->assertFalse($this->service->hasSubscription($user->guid, 'apples', $target->guid, 'object', 'foo', 'create'));
	}
	
	public function testFilterMutedNotificationsActor() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		// mute actor
		$actor = $event->getActor();
		$this->assertTrue($actor->muteNotifictions($user->guid));
		
		$subscriptions = [
			$user->guid => ['apples'],
		];
		
		$this->assertEmpty($this->service->filterMutedNotifications($subscriptions, $event));
	}
	
	public function testFilterMutedNotificationsOwner() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		// mute owner
		$owner = $event->getObject()->getOwnerEntity();
		$this->assertTrue($owner->muteNotifictions($user->guid));
		
		$subscriptions = [
			$user->guid => ['apples'],
		];
		
		$this->assertEmpty($this->service->filterMutedNotifications($subscriptions, $event));
	}
	
	public function testFilterMutedNotificationsContainer() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		// mute container
		$container = $event->getObject()->getContainerEntity();
		$this->assertTrue($container->muteNotifictions($user->guid));
		
		$subscriptions = [
			$user->guid => ['apples'],
		];
		
		$this->assertEmpty($this->service->filterMutedNotifications($subscriptions, $event));
	}
	
	public function testFilterMutedNotificationsEntity() {
		$user = $this->createUser();
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		// mute entity
		$object = $event->getObject();
		$this->assertTrue($object->muteNotifictions($user->guid));
		
		$subscriptions = [
			$user->guid => ['apples'],
		];
		
		$this->assertEmpty($this->service->filterMutedNotifications($subscriptions, $event));
	}
	
	function testGetNotificationEventSubscriptionsWithMutedActorBySubscription() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$this->assertTrue($container->addSubscription($user->guid, 'apples'));
		
		$this->assertNotEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
		
		// mute actor
		$actor = $event->getActor();
		$this->assertTrue($actor->muteNotifictions($user->guid));
		
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
	}
	
	function testGetNotificationEventSubscriptionsWithMutedOwnerBySubscription() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$this->assertTrue($container->addSubscription($user->guid, 'apples'));
		
		$this->assertNotEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
		
		// mute owner
		$owner = $object->getOwnerEntity();
		$this->assertTrue($owner->muteNotifictions($user->guid));
		
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
	}
	
	function testGetNotificationEventSubscriptionsWithMutedContainerBySubscription() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$this->assertTrue($container->addSubscription($user->guid, 'apples'));
		
		$this->assertNotEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
		
		// mute container
		$container = $object->getContainerEntity();
		$this->assertTrue($container->muteNotifictions($user->guid));
		
		// create a subscription which should exist in the system
		$this->assertTrue(add_entity_relationship($user->guid, SubscriptionsService::RELATIONSHIP_PREFIX . ':apples', $container->guid));
		
		$this->assertTrue($container->hasMutedNotifications($user->guid));
		$this->assertTrue($container->hasSubscription($user->guid, ['apples']));
		
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
	}
	
	function testGetNotificationEventSubscriptionsWithMutedEntityBySubscription() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$this->assertTrue($container->addSubscription($user->guid, 'apples'));
		
		$this->assertNotEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
		
		// mute entity
		$this->assertTrue($object->muteNotifictions($user->guid));
		
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
	}
	
	function testGetNotificationEventSubscriptionsWithMutedActorByHook() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		$this->testing_hook = $this->registerTestingHook('get', 'subscriptions', function(\Elgg\Hook $hook) use ($user) {
			$subs = $hook->getValue();
			
			$subs[$user->guid] = ['apples'];
			
			return $subs;
		});
		
		$this->assertNotEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
		
		// mute actor
		$actor = $event->getActor();
		$this->assertTrue($actor->muteNotifictions($user->guid));
		
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
	}
	
	function testGetNotificationEventSubscriptionsWithMutedOwnerByHook() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		$this->testing_hook = $this->registerTestingHook('get', 'subscriptions', function(\Elgg\Hook $hook) use ($user) {
			$subs = $hook->getValue();
			
			$subs[$user->guid] = ['apples'];
			
			return $subs;
		});
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		
		$this->assertNotEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
		
		// mute owner
		$owner = $object->getOwnerEntity();
		$this->assertTrue($owner->muteNotifictions($user->guid));
		
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
	}
	
	function testGetNotificationEventSubscriptionsWithMutedContainerByHook() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		$this->testing_hook = $this->registerTestingHook('get', 'subscriptions', function(\Elgg\Hook $hook) use ($user) {
			$subs = $hook->getValue();
			
			$subs[$user->guid] = ['apples'];
			
			return $subs;
		});
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		
		$this->assertNotEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
		
		// mute container
		$container = $object->getContainerEntity();
		$this->assertTrue($container->muteNotifictions($user->guid));
		
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
	}
	
	function testGetNotificationEventSubscriptionsWithMutedEntityByHook() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		$this->testing_hook = $this->registerTestingHook('get', 'subscriptions', function(\Elgg\Hook $hook) use ($user) {
			$subs = $hook->getValue();
			
			$subs[$user->guid] = ['apples'];
			
			return $subs;
		});
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		
		$this->assertNotEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
		
		// mute entity
		$this->assertTrue($object->muteNotifictions($user->guid));
		
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
	}
}
