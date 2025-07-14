<?php

namespace Elgg\Friends;

/**
 * Handle friends notifications
 *
 * @since 3.2
 * @internal
 */
class Notifications {

	/**
	 * Notify user that someone has friended them
	 *
	 * @param \Elgg\Event $event 'create', 'relationship'
	 *
	 * @return void
	 * @internal
	 */
	public static function sendFriendNotification(\Elgg\Event $event) {
		if ((bool) elgg_get_plugin_setting('friend_request', 'friends')) {
			// no generic notification while friend request is active
			// notifications are sent by actions
			return;
		}
		
		$object = $event->getObject();
		if (!$object instanceof \ElggRelationship || $object->relationship !== 'friend') {
			return;
		}
	
		if ($object->guid_two === elgg_get_logged_in_user_guid()) {
			// don't send notification to yourself
			return;
		}
		
		$user_one = get_user($object->guid_one);
		$user_two = get_user($object->guid_two);
		if (!$user_one instanceof \ElggUser || !$user_two instanceof \ElggUser) {
			return;
		}
	
		$user_two->notify('add_friend', $user_one, [], $user_one);
	}
}
