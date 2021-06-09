<?php

namespace Elgg\Traits\Entity;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\IntegrationTestCase;

abstract class ElggEntitySubscriptionsIntegrationTestCase extends IntegrationTestCase {

	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	/**
	 * @var \ElggEntity
	 */
	protected $target;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->setupApplication();
		
		$this->user = $this->createUser();
		$this->user->setNotificationSetting('apples', true);
		$this->user->setNotificationSetting('bananas', false);
		
		$this->target = $this->getEntity();
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$this->target->delete();
			$this->user->delete();
		});
	}
	
	protected function setupApplication(): void {
		$this->createApplication([
			'isolate' => true,
		]);
		
		_elgg_services()->notifications->registerMethod('apples');
		_elgg_services()->notifications->registerMethod('bananas');
	}
	
	abstract protected function getEntity(): \ElggEntity;
	
	public function testSubscription() {
		$this->assertTrue($this->target->addSubscription($this->user->guid));
		
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['apples']));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, 'bananas'));
		$this->assertTrue($this->target->hasSubscriptions($this->user->guid));
		
		$this->assertTrue($this->target->removeSubscription($this->user->guid));
		
		$this->assertFalse($this->target->hasSubscription($this->user->guid));
		$this->assertFalse($this->target->hasSubscriptions($this->user->guid));
	}
	
	public function testSubscriptionWithMethod() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples']));
		$this->assertFalse($this->target->addSubscription($this->user->guid, ['oranges'])); // not a registered method
		
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['apples']));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['bananas']));
		$this->assertTrue($this->target->hasSubscriptions($this->user->guid, ['apples']));
		$this->assertTrue($this->target->hasSubscriptions($this->user->guid, []));
		
		$this->assertTrue($this->target->removeSubscription($this->user->guid, ['bananas'])); // valid method but not subscribed
		$this->assertTrue($this->target->removeSubscription($this->user->guid, ['apples']));
		
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['apples', 'bananas']));
		$this->assertFalse($this->target->hasSubscriptions($this->user->guid, ['apples', 'bananas']));
	}
	
	/**
	 * @dataProvider invalidMethodsProvider
	 */
	public function testAddSubscriptionWithInvalidMethod($methods) {
		$this->expectException(InvalidArgumentException::class);
		$this->target->addSubscription($this->user->guid, $methods);
	}
	
	/**
	 * @dataProvider invalidMethodsProvider
	 */
	public function testHasSubscriptionWithInvalidMethod($methods) {
		$this->expectException(InvalidArgumentException::class);
		$this->target->hasSubscription($this->user->guid, $methods);
	}
	
	/**
	 * @dataProvider invalidMethodsProvider
	 */
	public function testHasSubscriptionsWithInvalidMethod($methods) {
		$this->expectException(InvalidArgumentException::class);
		$this->target->hasSubscriptions($this->user->guid, $methods);
	}
	
	/**
	 * @dataProvider invalidMethodsProvider
	 */
	public function testRemoveSubscriptionWithInvalidMethod($methods) {
		$this->expectException(InvalidArgumentException::class);
		$this->target->removeSubscription($this->user->guid, $methods);
	}
	
	public function invalidMethodsProvider() {
		return [
			[new \stdClass()],
			[1],
			[1.0],
			[true],
			[false],
			[''],
			[['']],
			[[1]],
			[[false]],
		];
	}
	
	public function testSubscriptionWithMethodTypeSubtypeAction() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples'], 'object', 'foo', 'create'));
		
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['apples'], 'object', 'foo', 'create'));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['apples']));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['bananas']));
		$this->assertTrue($this->target->hasSubscriptions($this->user->guid, ['apples']));
		$this->assertTrue($this->target->hasSubscriptions($this->user->guid, []));
		
		$this->assertTrue($this->target->removeSubscription($this->user->guid, ['bananas'], 'object', 'foo', 'create')); // valid method but not subscribed
		$this->assertTrue($this->target->removeSubscription($this->user->guid, ['apples'], 'object', 'foo', 'create'));
		
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['apples', 'bananas'], 'object', 'foo', 'create'));
		$this->assertFalse($this->target->hasSubscriptions($this->user->guid, ['apples', 'bananas']));
	}
	
	public function testRemoveSubscriptions() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples', 'bananas']));
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples'], 'object', 'foo', 'create'));
		
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['apples']));
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['apples'], 'object', 'foo', 'create'));
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['bananas']));
		
		$this->assertTrue($this->target->removeSubscriptions($this->user->guid));
		
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['apples']));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['apples'], 'object', 'foo', 'create'));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['bananas']));
	}
	
	public function testRemoveSubscriptionsWithMethod() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples', 'bananas']));
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples'], 'object', 'foo', 'create'));
		
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['apples']));
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['apples'], 'object', 'foo', 'create'));
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['bananas']));
		
		$this->assertTrue($this->target->removeSubscriptions($this->user->guid, ['apples']));
		
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['apples']));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['apples'], 'object', 'foo', 'create'));
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['bananas']));
		
		$this->assertTrue($this->target->removeSubscriptions($this->user->guid, ['bananas']));
		
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['bananas']));
	}
	
	public function testGetSubscriptions() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples', 'bananas']));
		
		$subscriptions = $this->target->getSubscriptions();
		$this->assertCount(2, $subscriptions);
		
		foreach ($subscriptions as $subscription) {
			$this->assertInstanceOf(\ElggRelationship::class, $subscription);
		}
	}
	
	public function testGetSubscriptionsMultipleUsers() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples', 'bananas']));
		
		$user2 = $this->createUser();
		$this->assertTrue($this->target->addSubscription($user2->guid, ['apples']));
		
		$subscriptions = $this->target->getSubscriptions();
		$this->assertCount(3, $subscriptions);
		
		foreach ($subscriptions as $subscription) {
			$this->assertInstanceOf(\ElggRelationship::class, $subscription);
		}
		
		$subscriptions = $this->target->getSubscriptions(0, ['bananas']);
		$this->assertCount(1, $subscriptions);
		
		foreach ($subscriptions as $subscription) {
			$this->assertInstanceOf(\ElggRelationship::class, $subscription);
		}
		
		$user2->delete();
	}
	
	public function testGetSubscriptionsWithTypeSubtypeAction() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples', 'bananas'], 'object', 'foo', 'create'));
		
		$user2 = $this->createUser();
		$this->assertTrue($this->target->addSubscription($user2->guid, ['apples']));
		
		$subscriptions = $this->target->getSubscriptions();
		$this->assertCount(3, $subscriptions);
		
		foreach ($subscriptions as $subscription) {
			$this->assertInstanceOf(\ElggRelationship::class, $subscription);
		}
		
		$subscriptions = $this->target->getSubscriptions(0, [], 'object', 'foo', 'create');
		$this->assertCount(2, $subscriptions);
		
		foreach ($subscriptions as $subscription) {
			$this->assertInstanceOf(\ElggRelationship::class, $subscription);
		}
		
		$user2->delete();
	}
	
	public function testGetSubscribers() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples', 'bananas']));
		
		$subscribers = $this->target->getSubscribers();
		$this->assertCount(1, $subscribers);
		$this->assertEquals($this->user->guid, $subscribers[0]->guid);
	}
	
	public function testGetSubscribersWithMethods() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples', 'bananas']));
		
		$user2 = $this->createUser();
		$this->assertTrue($this->target->addSubscription($user2->guid, ['apples']));
		
		$subscribers = $this->target->getSubscribers();
		$this->assertCount(2, $subscribers);
		
		$subscribers = $this->target->getSubscribers(['apples']);
		$this->assertCount(2, $subscribers);
		
		$subscribers = $this->target->getSubscribers(['bananas']);
		$this->assertCount(1, $subscribers);
	}
	
	public function testGetSubscribersWithTypeSubtypeAction() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples', 'bananas']));
		
		$user2 = $this->createUser();
		$this->assertTrue($this->target->addSubscription($user2->guid, ['apples'], 'object', 'foo', 'create'));
		
		$subscribers = $this->target->getSubscribers();
		$this->assertCount(2, $subscribers);
		
		$subscribers = $this->target->getSubscribers(['apples']);
		$this->assertCount(2, $subscribers);
		
		$subscribers = $this->target->getSubscribers(['bananas']);
		$this->assertCount(1, $subscribers);
	}
	
	public function testMuteUnmuteNotifications() {
		$this->assertTrue($this->target->addSubscription($this->user->guid, ['apples', 'bananas']));
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['apples']));
		$this->assertTrue($this->target->hasSubscription($this->user->guid, ['bananas']));
		$this->assertNotEmpty($this->target->getSubscribers(['apples', 'bananas']));
		
		$this->assertTrue($this->target->muteNotifications($this->user->guid));
		
		$this->assertTrue($this->target->hasMutedNotifications($this->user->guid));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['apples']));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['bananas']));
		$this->assertEmpty($this->target->getSubscribers(['apples', 'bananas']));
		
		$this->assertTrue($this->target->unmuteNotifications($this->user->guid));
		
		$this->assertFalse($this->target->hasMutedNotifications($this->user->guid));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['apples']));
		$this->assertFalse($this->target->hasSubscription($this->user->guid, ['bananas']));
		$this->assertEmpty($this->target->getSubscribers(['apples', 'bananas']));
	}
}
