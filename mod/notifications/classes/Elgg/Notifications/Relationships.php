<?php

namespace Elgg\Notifications;

/**
 * Hook callbacks for notifications relationships
 *
 * @since 4.0
 * @internal
 */
class Relationships {

	/**
	 * Update notifications for changes in access collection membership.
	 *
	 * This function assumes that only friends can belong to access collections.
	 *
	 * @param \Elgg\Hook $hook 'access:collections:add_user', 'collection'
	 *
	 * @return void
	 */
	public static function updateUserNotificationsPreferencesOnACLChange(\Elgg\Hook $hook) {
		// only update notifications for user owned collections
		$collection_id = $hook->getParam('collection_id');
		$collection = get_access_collection($collection_id);
		if (!$collection instanceof \ElggAccessCollection) {
			return;
		}
		$user = get_entity($collection->owner_guid);
		if (!$user instanceof \ElggUser) {
			return;
		}
	
		$member_guid = (int) $hook->getParam('user_guid');
		if (empty($member_guid)) {
			return;
		}
	
		// loop through all notification types
		$methods = elgg_get_notification_methods();
		foreach ($methods as $method) {
			$metaname = 'collections_notifications_preferences_' . $method;
			$collections_preferences = $user->$metaname;
			if (!$collections_preferences) {
				continue;
			}
			if (!is_array($collections_preferences)) {
				$collections_preferences = [$collections_preferences];
			}
			if (in_array(-1, $collections_preferences)) {
				// if "all friends" notify is on, we don't change any notifications
				// since must be a friend to be in an access collection
				continue;
			}
			if (in_array($collection_id, $collections_preferences)) {
				// notifications are on for this collection so we add/remove
				if ($hook->getName() == 'access:collections:add_user') {
					add_entity_relationship($user->guid, "notify$method", $member_guid);
				} elseif ($hook->getName() == 'access:collections:remove_user') {
					// removing someone from an access collection is not a guarantee
					// that they should be removed from notifications
					//remove_entity_relationship($user->guid, "notify$method", $member_guid);
				}
			}
		}
	}
	
	/**
	 * Update notifications when a relationship is deleted
	 *
	 * @param \Elgg\Event $event 'delete', 'relationship'
	 *
	 * @return void
	 */
	public static function deleteFriendNotificationsSubscription(\Elgg\Event $event) {
		$relationship = $event->getObject();
		if (!$relationship instanceof \ElggRelationship) {
			return;
		}
		
		if (!in_array($relationship->relationship, ['member', 'friend'])) {
			return;
		}
		
		$methods = elgg_get_notification_methods();
		foreach ($methods as $method) {
			elgg_remove_subscription($relationship->guid_one, $method, $relationship->guid_two);
		}
	}
	
	/**
	 * Turn on notifications for new friends if all friend notifications is on
	 *
	 * @param \Elgg\Event $event 'create', 'relationship'
	 *
	 * @return void
	 */
	public static function createFriendNotificationsRelationship(\Elgg\Event $event) {
		$relationship = $event->getObject();
		if (!$relationship instanceof \ElggRelationship) {
			return;
		}
		
		// The handler gets triggered regardless of which relationship was
		// created, so proceed only if dealing with a 'friend' relationship.
		if ($relationship->relationship != 'friend') {
			return;
		}
	
		$user_guid = $relationship->guid_one;
		$friend_guid = $relationship->guid_two;
	
		$user = get_entity($user_guid);
	
		// loop through all notification types
		$methods = elgg_get_notification_methods();
		foreach ($methods as $method) {
			$metaname = 'collections_notifications_preferences_' . $method;
			$collections_preferences = $user->$metaname;
			if ($collections_preferences) {
				if (!empty($collections_preferences) && !is_array($collections_preferences)) {
					$collections_preferences = [$collections_preferences];
				}
				if (is_array($collections_preferences)) {
					// -1 means all friends is on - should be a define
					if (in_array(-1, $collections_preferences)) {
						add_entity_relationship($user_guid, 'notify' . $method, $friend_guid);
					}
				}
			}
		}
	}
}
