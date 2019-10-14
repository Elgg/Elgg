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
	public static function createFriendRelationship(\Elgg\Event $event) {
		
		$relationship = $event->getObject();
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'friend') {
			return;
		}
		
		$user_guid = $relationship->guid_one;
		$friend_guid = $relationship->guid_two;
		
		remove_entity_relationship($friend_guid, 'friendrequest', $user_guid);
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
		remove_entity_relationship($relationship->guid_two, 'friend', $relationship->guid_one);
		
		// re-register listener
		elgg_register_event_handler($event->getName(), $event->getType(), __METHOD__);
	}
}
