<?php

namespace Elgg\Groups;

use Elgg\IntegrationTestCase;
use Elgg\Plugins\PluginTesting;

class NotificationsIntegrationTest extends IntegrationTestCase {

	use PluginTesting;
	
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
	
	public function testGroupSubscriptionAddedWhenGroupCreated() {
		$user = $this->createUser();
		
		$user->setNotificationSetting('test', true, 'content_create');
		
		$group = $this->createGroup([
			'owner_guid' => $user->guid,
		]);
		
		$this->assertTrue($group->hasSubscription($user->guid, 'test'));
		
		$group->delete();
		$user->delete();
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
