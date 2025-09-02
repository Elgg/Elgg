<?php

namespace Elgg\Friends\Notifications;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * Send a notification when a friend is added
 *
 * @since 6.3
 */
class AddFriendHandler extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationSubject($recipient, $method);
		}
		
		return elgg_echo('friend:newfriend:subject', [
			$friend->getDisplayName(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationSummary($recipient, $method);
		}
		
		return elgg_echo('friend:newfriend:subject', [
			$friend->getDisplayName(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		return elgg_echo('friend:newfriend:body', [
			$friend->getDisplayName(),
			$friend->getURL()
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		$friend = $this->event->getObject();
		if (!$friend instanceof \ElggUser) {
			return parent::getNotificationSubject($recipient, $method);
		}
		
		return $friend->getURL();
	}
}
