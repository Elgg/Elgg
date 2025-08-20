<?php

namespace Elgg\Groups;

/**
 * Group related actions based on relationships
 *
 * @since 4.0
 * @internal
 */
class Relationships {
	
	/**
	 * Remove notification subscriptions when a user leaves a group
	 *
	 * @param \Elgg\Event $event 'delete', 'relationship'
	 *
	 * @return void
	 */
	public static function removeGroupNotificationSubscriptions(\Elgg\Event $event): void {
		$relationship = $event->getObject();
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'member') {
			return;
		}
		
		$user = get_user($relationship->guid_one);
		$group = get_entity($relationship->guid_two);
		if (empty($user) || !$group instanceof \ElggGroup) {
			return;
		}
		
		$group->removeSubscriptions($user->guid);
	}
	
	/**
	 * Apply the default notification settings when joining a group
	 *
	 * @param \Elgg\Event $event 'create', 'relationship'
	 *
	 * @return void
	 */
	public static function applyGroupNotificationSettings(\Elgg\Event $event): void {
		$relationship = $event->getObject();
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'member') {
			return;
		}
		
		$user = get_user($relationship->guid_one);
		$group = get_entity($relationship->guid_two);
		if (empty($user) || !$group instanceof \ElggGroup) {
			return;
		}
		
		$enabled_methods = $user->getNotificationSettings('group_join', true);
		if (empty($enabled_methods)) {
			return;
		}
		
		$group->addSubscription($user->guid, $enabled_methods);
	}
}
