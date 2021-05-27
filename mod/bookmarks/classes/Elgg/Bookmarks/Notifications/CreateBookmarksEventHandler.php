<?php

namespace Elgg\Bookmarks\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'bookmarks' 'create' action
 */
class CreateBookmarksEventHandler extends NotificationEventHandler {

	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('bookmarks:notify:subject', [$this->event->getObject()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('bookmarks:notify:summary', [$this->event->getObject()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->event->getObject();
		
		return elgg_echo('bookmarks:notify:body', [
			$this->event->getActor()->getDisplayName(),
			$entity->getDisplayName(),
			$entity->address,
			$entity->description,
			$entity->getURL(),
		], $recipient->getLanguage());
	}
}
