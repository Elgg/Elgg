<?php

namespace Elgg\Blog\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'blog' 'publish' action
 */
class PublishBlogEventHandler extends NotificationEventHandler {

	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('blog:notify:subject', [$this->getEventEntity()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('blog:notify:summary', [$this->getEventEntity()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->getEventEntity();
		if (!$entity instanceof \ElggBlog) {
			$entity = null;
		}
		
		return elgg_echo('blog:notify:body', [
			$this->getEventActor()?->getDisplayName(),
			$entity?->getDisplayName(),
			$entity?->getExcerpt(),
			$entity?->getURL(),
		]);
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected static function isConfigurableForGroup(\ElggGroup $group): bool {
		return $group->isToolEnabled('blog');
	}
}
