<?php

namespace Elgg\Friends\Notifications;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * Send a notification when a friend request is declined
 *
 * @since 6.3
 */
class DeclineFriendRequestHandler extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationSubject($recipient, $method);
		}
		
		return elgg_echo('friends:notification:request:decline:subject', [$friend->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationSummary($recipient, $method);
		}
		
		return elgg_echo('friends:notification:request:decline:subject', [$friend->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		return elgg_echo('friends:notification:request:decline:message', [$friend->getDisplayName()]);
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
