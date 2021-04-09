<?php

namespace Elgg\Notifications;

use Elgg\IntegrationTestCase;

class SubscriptionsIntegrationTest extends IntegrationTestCase {

	public function up() {
		if (!elgg_is_active_plugin('notifications')) {
			$this->markTestSkipped();
		}
		
		elgg_register_notification_method('test');
	}

	public function down() {
		elgg_unregister_notification_method('test');
	}

	public function testFriendSubscriptionRemovedWhenFriendRelationshipDeleted() {

		$user1 = $this->createUser();
		$user2 = $this->createUser();
		
		$user1->addFriend($user2->guid);
		$this->assertTrue($user1->isFriendsWith($user2->guid));
		
		$user2->addSubscription($user1->guid, 'test');
		$this->assertTrue($user2->hasSubscription($user1->guid, 'test'));
		
		$user1->removeFriend($user2->guid);
		$this->assertFalse($user1->isFriendsWith($user2->guid));
		$this->assertFalse($user2->hasSubscription($user1->guid, 'test'));
		
		$user1->delete();
		$user2->delete();
	}

	public function testGroupSubscriptionRemovedWhenMemberRelationshipRemoved() {
		
		$user = $this->createUser();
		$group = $this->createGroup();
		
		$group->join($user);
		$this->assertTrue($group->isMember($user));

		$group->addSubscription($user->guid, 'test');
		$this->assertTrue($group->hasSubscription($user->guid, 'test'));

		$group->leave($user);
		$this->assertFalse($group->isMember($user));
		$this->assertFalse($group->hasSubscription($user->guid, 'test'));
		
		$group->delete();
		$user->delete();
	}
}
