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
	 * Send a notification about an accepted friend request
	 *
	 * @param \ElggUser $recipient the original requester
	 * @param \ElggUser $sender    the accepting user
	 *
	 * @return array
	 */
	public static function sendAcceptedFriendRequestNotification(\ElggUser $recipient, \ElggUser $sender) {
		
		$subject = elgg_echo('friends:notification:request:accept:subject', [$sender->getDisplayName()], $recipient->getLanguage());
		$message = elgg_echo('friends:notification:request:accept:message', [
			$sender->getDisplayName(),
		], $recipient->getLanguage());
		
		$params = [
			'action' => 'friendrequest:accept',
			'object' => $recipient,
			'friend' => $sender,
		];
		
		return notify_user($recipient->guid, $sender->guid, $subject, $message, $params);
	}
	
	/**
	 * Send a notification about a new friend
	 *
	 * @param \ElggUser $recipient the new friend
	 * @param \ElggUser $sender    the acting user
	 *
	 * @return array
	 */
	public static function sendAddFriendNotification(\ElggUser $recipient, \ElggUser $sender) {
		// Notification subject
		$subject = elgg_echo('friend:newfriend:subject', [
			$sender->getDisplayName(),
		], $recipient->getLanguage());
		
		// Notification body
		$body = elgg_echo('friend:newfriend:body', [
			$sender->getDisplayName(),
			$sender->getURL()
		], $recipient->getLanguage());
		
		// Notification params
		$params = [
			'action' => 'add_friend',
			'object' => $sender,
			'friend' => $recipient,
			'url' => $recipient->getURL(),
		];
		
		return notify_user($recipient->guid, $sender->guid, $subject, $body, $params);
	}

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
	
		self::sendAddFriendNotification($user_two, $user_one);
	}
}
