<?php

namespace Elgg\Friends\Notifications;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * Send a notification when a friend request is made
 *
 * @since 6.3
 */
class FriendRequestHandler extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationSubject($recipient, $method);
		}
		
		return elgg_echo('friends:notification:request:subject', [$friend->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationSummary($recipient, $method);
		}
		
		return elgg_echo('friends:notification:request:subject', [$friend->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		return elgg_echo('friends:notification:request:message', [
			$friend->getDisplayName(),
			elgg_get_site_entity()->getDisplayName(),
			elgg_generate_url('collection:relationship:friendrequest:pending', [
				'username' => $recipient->username,
			]),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationURL($recipient, $method);
		}
		
		return $friend->getURL();
	}
}
