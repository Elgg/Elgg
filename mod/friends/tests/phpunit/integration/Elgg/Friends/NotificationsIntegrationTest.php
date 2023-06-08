<?php

namespace Elgg\Friends;

use Elgg\Plugins\IntegrationTestCase;

class NotificationsIntegrationTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function up() {
		elgg_register_notification_method('test');
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		elgg_unregister_notification_method('test');
	}
	
	public function testFriendSubscriptionAddedWhenFriendRelationshipCreated() {
		$user = $this->createUser();
		$friend = $this->createUser();
		
		$user->setNotificationSetting('test', true, 'friends');
		
		$user->addFriend($friend->guid);
		$this->assertTrue($user->isFriendsWith($friend->guid));
		
		$this->assertTrue($friend->hasSubscription($user->guid, 'test'));
		
		$user->removeFriend($friend->guid);
		$this->assertFalse($user->isFriendsWith($friend->guid));
		$this->assertFalse($friend->hasSubscription($user->guid, 'test'));
	}
	
	public function testFriendSubscriptionRemovedWhenFriendRelationshipDeleted() {
		$user = $this->createUser();
		$friend = $this->createUser();
		
		$user->addFriend($friend->guid);
		$this->assertTrue($user->isFriendsWith($friend->guid));
		
		$friend->addSubscription($user->guid, 'test');
		$this->assertTrue($friend->hasSubscription($user->guid, 'test'));
		
		$user->removeFriend($friend->guid);
		$this->assertFalse($user->isFriendsWith($friend->guid));
		$this->assertFalse($friend->hasSubscription($user->guid, 'test'));
	}
}
