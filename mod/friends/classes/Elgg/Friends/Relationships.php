<?php

namespace Elgg\Friends;

/**
 * Event listener for friend relationships
 *
 * @since 3.2
 */
class Relationships {

	/**
	 * Listen to the create friend relationship to remove pending friendship requests
	 *
	 * @param \Elgg\Event $event 'create', 'relationship'
	 *
	 * @return void
	 * @since 3.2
	 */
	public static function removePendingFriendRequest(\Elgg\Event $event) {
		
		$relationship = $event->getObject();
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'friend') {
			return;
		}
		
		$friend = get_user($relationship->guid_two);
		if (!$friend instanceof \ElggUser) {
			return;
		}
		
		$friend->removeRelationship($relationship->guid_one, 'friendrequest');
	}
	
	/**
	 * Turn on notifications for new friends
	 *
	 * @param \Elgg\Event $event 'create', 'relationship'
	 *
	 * @return void
	 * @since 4.0
	 */
	public static function applyFriendNotificationsSettings(\Elgg\Event $event): void {
		
		$relationship = $event->getObject();
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'friend') {
			return;
		}
		
		$user = get_user($relationship->guid_one);
		$friend = get_user($relationship->guid_two);
		if (empty($user) || empty($friend)) {
			return;
		}
		
		$friend_preferences = $user->getNotificationSettings('friends');
		$enabled_methods = array_keys(array_filter($friend_preferences));
		
		// loop through all notification types
		$methods = elgg_get_notification_methods();
		foreach ($enabled_methods as $method) {
			// only enable supported methods
			if (!in_array($method, $methods)) {
				continue;
			}
			
			$friend->addSubscription($user->guid, $method);
		}
	}
	
	/**
	 * Listen to the delete friend relationship to remove the friend relationship bi-directional
	 *
	 * @param \Elgg\Event $event 'delete', 'relationship'
	 *
	 * @return void
	 * @since 3.2
	 */
	public static function deleteFriendRelationship(\Elgg\Event $event) {
		
		$relationship = $event->getObject();
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'friend') {
			return;
		}
		
		if (!(bool) elgg_get_plugin_setting('friend_request', 'friends')) {
			return;
		}
		
		// prevent deadloops
		elgg_unregister_event_handler($event->getName(), $event->getType(), __METHOD__);
		
		// remove other friend relationship
		$friend = get_user($relationship->guid_two);
		if ($friend instanceof \ElggUser) {
			$friend->removeRelationship($relationship->guid_one, 'friend');
		}
		
		// re-register listener
		elgg_register_event_handler($event->getName(), $event->getType(), __METHOD__);
	}
	
	/**
	 * Remove subscriptions when the friend relationship is removed
	 *
	 * @param \Elgg\Event $event 'delete', 'relationship'
	 *
	 * @return void
	 * @since 4.0
	 */
	public static function deleteFriendNotificationSubscription(\Elgg\Event $event): void {
		$relationship = $event->getObject();
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'friend') {
			return;
		}
		
		$user = get_user($relationship->guid_one);
		$friend = get_user($relationship->guid_two);
		if (empty($user) || empty($friend)) {
			return;
		}
		
		$friend->removeSubscriptions($user->guid);
	}
}
