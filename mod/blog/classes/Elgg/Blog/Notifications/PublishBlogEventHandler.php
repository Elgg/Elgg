<?php

namespace Elgg\Blog\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'blog' 'publish' action
 */
class PublishBlogEventHandler extends NotificationEventHandler {

	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('blog:notify:subject', [$this->event->getObject()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('blog:notify:summary', [$this->event->getObject()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->event->getObject();
		
		return elgg_echo('blog:notify:body', [
			$this->event->getActor()->getDisplayName(),
			$entity->getDisplayName(),
			$entity->getExcerpt(),
			$entity->getURL(),
		], $recipient->getLanguage());
	}
}
