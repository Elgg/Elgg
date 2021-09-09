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
		$reflector = new \ReflectionClass($this->service);
		$method = $reflector->getMethod('filterMutedNotifications');
		$method->setAccessible(true);
		
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		// mute actor
		$actor = $event->getActor();
		$this->assertTrue($actor->muteNotifications($user->guid));
		
		$subscriptions = [
			$user->guid => ['apples'],
		];
		
		$this->assertEmpty($method->invoke($this->service, $subscriptions, $event));
	}
	
	public function testFilterMutedNotificationsOwner() {
		$reflector = new \ReflectionClass($this->service);
		$method = $reflector->getMethod('filterMutedNotifications');
		$method->setAccessible(true);
		
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		// mute owner
		$owner = $event->getObject()->getOwnerEntity();
		$this->assertTrue($owner->muteNotifications($user->guid));
		
		$subscriptions = [
			$user->guid => ['apples'],
		];
		
		$this->assertEmpty($method->invoke($this->service, $subscriptions, $event));
	}
	
	public function testFilterMutedNotificationsContainer() {
		$reflector = new \ReflectionClass($this->service);
		$method = $reflector->getMethod('filterMutedNotifications');
		$method->setAccessible(true);
		
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		// mute container
		$container = $event->getObject()->getContainerEntity();
		$this->assertTrue($container->muteNotifications($user->guid));
		
		$subscriptions = [
			$user->guid => ['apples'],
		];
		
		$this->assertEmpty($method->invoke($this->service, $subscriptions, $event));
	}
	
	public function testFilterMutedNotificationsEntity() {
		$reflector = new \ReflectionClass($this->service);
		$method = $reflector->getMethod('filterMutedNotifications');
		$method->setAccessible(true);
		
		$user = $this->createUser();
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		// mute entity
		$object = $event->getObject();
		$this->assertTrue($object->muteNotifications($user->guid));
		
		$subscriptions = [
			$user->guid => ['apples'],
		];
		
		$this->assertEmpty($method->invoke($this->service, $subscriptions, $event));
	}
	
	public function testFilterDelayedEmailSubscribers() {
		$reflector = new \ReflectionClass($this->service);
		$method = $reflector->getMethod('filterDelayedEmailSubscribers');
		$method->setAccessible(true);
		
		$subscriptions = [
			1 => ['apples'],
			2 => ['email'],
			3 => ['delayed_email'],
			4 => ['email', 'delayed_email'],
			5 => ['delayed_email', 'email'],
			6 => ['email', 'apples'],
		];
		
		$expected = [
			1 => ['apples'],
			2 => ['email'],
			3 => ['delayed_email'],
			4 => ['email'],
			5 => ['email'],
			6 => ['email', 'apples'],
		];
		
		$this->assertEquals($expected, $method->invoke($this->service, $subscriptions));
	}
	
	public function testFilterSubscriptionsUniqueMethods() {
		$event = $this->getSubscriptionNotificationEvent();
		
		$subscriptions = [
			1 => ['apples', 'apples', 'bananas'],
			2 => ['apples', 'apples', 'bananas', 'bananas'],
			3 => ['apples', 'bananas', 'apples', 'bananas'],
		];
		
		$expected = [
			1 => ['apples', 'bananas'],
			2 => ['apples', 'bananas'],
			3 => ['apples', 'bananas'],
		];
		
		$this->assertEquals($expected, $this->service->filterSubscriptions($subscriptions, $event));
	}
	
	public function testFilterSubscriptionsEmptyMethods() {
		$event = $this->getSubscriptionNotificationEvent();
		
		$subscriptions = [
			1 => ['apples', ''],
			2 => ['0', 'bananas'],
			3 => [false, 'bananas', 'apples'],
		];
		
		$expected = [
			1 => ['apples'],
			2 => ['bananas'],
			3 => ['bananas', 'apples'],
		];
		
		$this->assertEquals($expected, $this->service->filterSubscriptions($subscriptions, $event));
	}
	
	public function testFilterTimedMutedSubscribersExpired() {
		$this->entities[] = $user1 = $this->createUser();
		$this->entities[] = $user2 = $this->createUser();
		$event = $this->getSubscriptionNotificationEvent();
		
		$user1->setPrivateSetting('timed_muting_start', time() - 20);
		$user1->setPrivateSetting('timed_muting_end', time() - 10);
		
		$subscriptions = [
			$user1->guid => ['apples', 'bananas'],
			$user2->guid => ['bananas'],
		];
		
		$this->assertEquals($subscriptions, $this->service->filterSubscriptions($subscriptions, $event));
	}
	
	public function testFilterTimedMutedSubscribersActive() {
		$this->entities[] = $user1 = $this->createUser();
		$this->entities[] = $user2 = $this->createUser();
		$event = $this->getSubscriptionNotificationEvent();
		
		$user1->setPrivateSetting('timed_muting_start', time() - 20);
		$user1->setPrivateSetting('timed_muting_end', time() + 10);
		
		$subscriptions = [
			$user1->guid => ['apples', 'bananas'],
			$user2->guid => ['bananas'],
		];
		
		$expected = [
			$user2->guid => ['bananas'],
		];
		
		$this->assertEquals($expected, $this->service->filterSubscriptions($subscriptions, $event));
	}
	
	public function testFilterTimedMutedSubscribersScheduled() {
		$this->entities[] = $user1 = $this->createUser();
		$this->entities[] = $user2 = $this->createUser();
		$event = $this->getSubscriptionNotificationEvent();
		
		$user1->setPrivateSetting('timed_muting_start', time() + 20);
		$user1->setPrivateSetting('timed_muting_end', time() + 40);
		
		$subscriptions = [
			$user1->guid => ['apples', 'bananas'],
			$user2->guid => ['bananas'],
		];
		
		$this->assertEquals($subscriptions, $this->service->filterSubscriptions($subscriptions, $event));
	}
	
	public function testGetNotificationEventSubscriptionsWithMutedActorBySubscription() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$this->assertTrue($container->addSubscription($user->guid, 'apples'));
		
		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']);
		$this->assertNotEmpty($subscriptions);
		$this->assertNotEmpty($this->service->filterSubscriptions($subscriptions, $event));
		
		// mute actor
		$actor = $event->getActor();
		$this->assertTrue($actor->muteNotifications($user->guid));
		
		$this->assertEmpty($this->service->filterSubscriptions($subscriptions, $event));
	}
	
	public function testGetNotificationEventSubscriptionsWithMutedOwnerBySubscription() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$this->assertTrue($container->addSubscription($user->guid, 'apples'));
		
		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']);
		$this->assertNotEmpty($subscriptions);
		$this->assertNotEmpty($this->service->filterSubscriptions($subscriptions, $event));
		
		// mute owner
		$owner = $object->getOwnerEntity();
		$this->assertTrue($owner->muteNotifications($user->guid));
		
		$this->assertEmpty($this->service->filterSubscriptions($subscriptions, $event));
	}
	
	public function testGetNotificationEventSubscriptionsWithMutedContainerBySubscription() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$this->assertTrue($container->addSubscription($user->guid, 'apples'));
		
		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']);
		$this->assertNotEmpty($subscriptions);
		$this->assertNotEmpty($this->service->filterSubscriptions($subscriptions, $event));
		
		// mute container
		$container = $object->getContainerEntity();
		$this->assertTrue($container->muteNotifications($user->guid));
		
		// create a subscription which should exist in the system
		$this->assertTrue(add_entity_relationship($user->guid, SubscriptionsService::RELATIONSHIP_PREFIX . ':apples', $container->guid));
		
		$this->assertTrue($container->hasMutedNotifications($user->guid));
		$this->assertTrue($container->hasSubscription($user->guid, ['apples']));
		
		$this->assertEmpty($this->service->filterSubscriptions($subscriptions, $event));
	}
	
	public function testGetNotificationEventSubscriptionsWithMutedEntityBySubscription() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$this->assertTrue($container->addSubscription($user->guid, 'apples'));
		
		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']);
		$this->assertNotEmpty($subscriptions);
		$this->assertNotEmpty($this->service->filterSubscriptions($subscriptions, $event));
		
		// mute entity
		$this->assertTrue($object->muteNotifications($user->guid));
		
		$this->assertEmpty($this->service->filterSubscriptions($subscriptions, $event));
	}
	
	public function testGetNotificationEventSubscriptionsWithExcludedOwnerGUID() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$owner = $object->getOwnerEntity();
		$this->assertTrue($owner->addSubscription($user->guid, 'apples'));
		
		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']);
		$this->assertNotEmpty($subscriptions);
		
		// exclude owner
		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas'], [$owner->guid]);
		$this->assertEmpty($subscriptions);
	}
	
	public function testGetNotificationEventSubscriptionsWithExcludedContainerGUID() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$this->assertTrue($container->addSubscription($user->guid, 'apples'));
		
		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']);
		$this->assertNotEmpty($subscriptions);
		
		// exclude container
		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas'], [$container->guid]);
		$this->assertEmpty($subscriptions);
	}
	
	public function testGetNotificationEventSubscriptionsWithExcludedEntityGUID() {
		$this->entities[] = $user = $this->createUser();
		
		$event = $this->getSubscriptionNotificationEvent();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$this->assertTrue($object->addSubscription($user->guid, 'apples'));
		
		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']);
		$this->assertNotEmpty($subscriptions);
		
		// exclude entity
		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas'], [$object->guid]);
		$this->assertEmpty($subscriptions);
	}
	
	public function testGetNotificationEventSubscriptionsWhereEventActorIsNotPresentInResult() {
		$event = $this->getSubscriptionNotificationEvent();
		
		$this->entities[] = $user = $this->createUser();
		$actor = $event->getActor();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$this->assertTrue($container->addSubscription($actor->guid, 'apples'));
		$this->assertTrue($container->addSubscription($user->guid, 'bananas'));
		
		$expected = [
			$user->guid => [
				'bananas',
			],
		];
		
		$this->assertEquals($expected, $this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
	}
	
	public function testGetNotificationEventSubscriptionsForEntityAndContainer() {
		$event = $this->getSubscriptionNotificationEvent();
		
		$this->entities[] = $user1 = $this->createUser();
		$this->entities[] = $user2 = $this->createUser();
		
		$actor = $event->getActor();
		
		/* @var $object \ElggObject */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		
		$this->assertTrue($object->addSubscription($user1->guid, 'apples'));
		$this->assertTrue($container->addSubscription($user2->guid, 'apples'));
		
		$expected = [
			$user1->guid => [
				'apples',
			],
			$user2->guid => [
				'apples',
			],
		];
		
		$this->assertEquals($expected, $this->service->getNotificationEventSubscriptions($event, ['apples']));
	}
	
	public function testGetNotificationEventSubscriptionsForUserEventObject() {
		$this->entities[] = $actor = $this->createUser();
		$this->entities[] = $object = $this->createUser();
		
		$event = new SubscriptionNotificationEvent($object, 'create', $actor);
		
		$this->entities[] = $user1 = $this->createUser();
				
		$this->assertTrue($object->addSubscription($user1->guid, 'apples'));

		$subscriptions = $this->service->getNotificationEventSubscriptions($event, ['apples']);

		$this->assertArrayNotHasKey($user1->guid, $subscriptions);
	}
}
