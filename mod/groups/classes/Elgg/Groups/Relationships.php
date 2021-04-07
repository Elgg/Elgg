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
		
		$group_preferences = $user->getNotificationSettings('group_join');
		$enabled_methods = array_keys(array_filter($group_preferences));
		
		// loop through all notification types
		$methods = elgg_get_notification_methods();
		foreach ($enabled_methods as $method) {
			// only enable supported methods
			if (!in_array($method, $methods)) {
				continue;
			}
			
			$group->addSubscription($user->guid, $method);
		}
	}
}
