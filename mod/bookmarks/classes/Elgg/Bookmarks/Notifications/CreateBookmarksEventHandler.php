<?php

namespace Elgg\Bookmarks\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'bookmarks' 'create' action
 */
class CreateBookmarksEventHandler extends NotificationEventHandler {

	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('bookmarks:notify:subject', [$this->getEventEntity()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('bookmarks:notify:summary', [$this->getEventEntity()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->getEventEntity();
		
		return elgg_echo('bookmarks:notify:body', [
			$this->getEventActor()?->getDisplayName(),
			$entity?->getDisplayName(),
			$entity?->address,
			$entity?->description,
			$entity?->getURL(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected static function isConfigurableForGroup(\ElggGroup $group): bool {
		return $group->isToolEnabled('bookmarks');
	}
}
