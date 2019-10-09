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
			$recipient->getDisplayName(),
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
}
