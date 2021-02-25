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
		$this->assertTrue($this->service->addSubscription($user->guid, 'mail', $target->guid));
		
		$expected = [
			$user->guid => ['mail'],
		];
		$this->assertEquals($expected, $this->service->getSubscriptionsForContainer($target->guid, ['mail', 'site']));
		
		// remove subscription
		$this->assertTrue($this->service->removeSubscription($user->guid, 'mail', $target->guid));
		// remove non existing subscription
		$this->assertFalse($this->service->removeSubscription($user->guid, 'site', $target->guid));
		
		// check if all was removed
		$this->assertEmpty($this->service->getSubscriptionsForContainer($target->guid, ['mail', 'site']));
		
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
		$this->assertTrue($this->service->addSubscription($user->guid, 'mail', $target->guid, $object->type, $object->subtype, $event->getAction()));
		
		// check subscriptions
		$expected = [
			$user->guid => ['mail'],
		];
		$this->assertEquals($expected, $this->service->getSubscriptions($event, ['mail', 'site']));
		
		// test with a different notification
		$this->assertEmpty($this->service->getSubscriptions($event2, ['mail', 'site']));
		
		// remove subscription
		$this->assertFalse($this->service->removeSubscription($user->guid, 'mail', $target->guid));
		$this->assertTrue($this->service->removeSubscription($user->guid, 'mail', $target->guid, $object->type, $object->subtype, $event->getAction()));
		
		// remove non existing subscription
		$this->assertFalse($this->service->removeSubscription($user->guid, 'site', $target->guid, $object->type, $object->subtype, $event->getAction()));
		
		// check if all was removed
		$this->assertEmpty($this->service->getSubscriptions($event, ['mail', 'site']));
		
		$user->delete();
		$target->delete();
		$object->delete();
		$object2->delete();
	}
}
