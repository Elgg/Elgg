<?php

namespace Elgg\Notifications;

use Elgg\IntegrationTestCase;

class SubscriptionServiceIntegrationTest extends IntegrationTestCase {

	/**
	 * @var SubscriptionsService
	 */
	protected $service;
	
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
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
	}
	
	public function testAddRemoveSubscription() {
		$user = $this->createUser();
		$target = $this->createGroup();
		
		// add subscription
		$this->assertTrue($this->service->addSubscription($user->guid, 'apples', $target->guid));
		
		$expected = [
			$user->guid => ['apples'],
		];
		$this->assertEquals($expected, $this->service->getSubscriptionsForContainer($target->guid, ['apples', 'bananas']));
		
		// remove subscription
		$this->assertTrue($this->service->removeSubscription($user->guid, 'apples', $target->guid));
		// remove non existing subscription
		$this->assertFalse($this->service->removeSubscription($user->guid, 'bananas', $target->guid));
		
		// check if all was removed
		$this->assertEmpty($this->service->getSubscriptionsForContainer($target->guid, ['apples', 'bananas']));
		
		$user->delete();
		$target->delete();
	}
	
	public function testAddRemoveDetailedSubscription() {
		$user = $this->createUser();
		$target = $this->createGroup();
		$object = $this->createObject([
			'subtype' => 'foo',
			'access_id' => ACCESS_PUBLIC,
			'container_guid' => $target->guid,
		]);
		$object2 = $this->createObject([
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
		$this->assertFalse($this->service->removeSubscription($user->guid, 'apples', $target->guid));
		$this->assertTrue($this->service->removeSubscription($user->guid, 'apples', $target->guid, $object->type, $object->subtype, $event->getAction()));
		
		// remove non existing subscription
		$this->assertFalse($this->service->removeSubscription($user->guid, 'bananas', $target->guid, $object->type, $object->subtype, $event->getAction()));
		
		// check if all was removed
		$this->assertEmpty($this->service->getNotificationEventSubscriptions($event, ['apples', 'bananas']));
		
		$user->delete();
		$target->delete();
		$object->delete();
		$object2->delete();
	}
}
