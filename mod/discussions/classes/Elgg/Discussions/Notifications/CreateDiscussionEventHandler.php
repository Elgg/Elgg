<?php

namespace Elgg\Discussions\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'discussion' 'create' action
 */
class CreateDiscussionEventHandler extends NotificationEventHandler {
		
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('discussion:topic:notify:subject', [$this->event->getObject()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('discussion:topic:notify:summary', [$this->event->getObject()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->event->getObject();
		
		return elgg_echo('discussion:topic:notify:body', [
			$this->event->getActor()->getDisplayName(),
			$entity->getDisplayName(),
			$entity->description,
			$entity->getURL(),
		], $recipient->getLanguage());
	}
}
