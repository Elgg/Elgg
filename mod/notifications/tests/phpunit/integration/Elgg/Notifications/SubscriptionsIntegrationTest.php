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
		
		elgg_add_subscription($user1->guid, 'test', $user2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, check_entity_relationship($user1->guid, 'notifytest', $user2->guid));

		$user1->removeFriend($user2->guid);
		$this->assertFalse($user1->isFriendsWith($user2->guid));
		$this->assertFalse(check_entity_relationship($user1->guid, 'notifytest', $user2->guid));
		
		$user1->delete();
		$user2->delete();
	}

	public function testGroupSubscriptionRemovedWhenMemberRelationshipRemoved() {
		
		$user = $this->createUser();
		$group = $this->createGroup();
		
		$group->join($user);
		$this->assertTrue($group->isMember($user));

		elgg_add_subscription($user->guid, 'test', $group->guid);
		$this->assertInstanceOf(\ElggRelationship::class, check_entity_relationship($user->guid, 'notifytest', $group->guid));

		$group->leave($user);
		$this->assertFalse($group->isMember($user));
		
		$group->delete();
		$user->delete();
	}
}
