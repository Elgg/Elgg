<?php

/**
 * Notifications plugin tests
 */
class ElggNotificationsPluginUnitTest extends ElggCoreUnitTest {

	/**
	 * @var ElggUser
	 */
	private $user1;

	/**
	 * @var ElggUser
	 */
	private $user2;

	/**
	 * @var ElggUser
	 */
	private $group;

	public function up() {

		elgg_register_notification_method('test');

		$this->user1 = new ElggUser();
		$this->user1->username = 'test1';
		$this->user1->save();

		$this->user2 = new ElggUser();
		$this->user2->username = 'test2';
		$this->user2->save();

		$this->group = new ElggGroup();
		$this->group->save();
	}

	public function down() {

		elgg_unregister_notification_method('test');

		$this->user1->delete();
		$this->user2->delete();
		$this->group->delete();
	}

	public function testFriendSubscriptionRemovedWhenFriendRelationshipDeleted() {

		$this->user1->addFriend($this->user2->guid);
		$this->assertTrue($this->user1->isFriendsWith($this->user2->guid));

		elgg_add_subscription($this->user1->guid, 'test', $this->user2->guid);
		$this->assertIsA(check_entity_relationship($this->user1->guid, 'notifytest', $this->user2->guid), ElggRelationship::class);

		$this->user1->removeFriend($this->user2->guid);
		$this->assertFalse($this->user1->isFriendsWith($this->user2->guid));
		$this->assertFalse(check_entity_relationship($this->user1->guid, 'notifytest', $this->user2->guid));
	}

	public function testGroupSubscriptionRemovedWhenMemberRelationshipRemoved() {

		$this->group->join($this->user1);
		$this->assertTrue($this->group->isMember($this->user1));

		elgg_add_subscription($this->user1->guid, 'test', $this->group->guid);
		$this->assertIsA(check_entity_relationship($this->user1->guid, 'notifytest', $this->group->guid), ElggRelationship::class);

		$this->group->leave($this->user1);
		$this->assertFalse($this->group->isMember($this->user1));
		$this->assertFalse(check_entity_relationship($this->user1->guid, 'notifytest', $this->user2->guid));
	}

}
