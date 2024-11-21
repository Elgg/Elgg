<?php

namespace Elgg\Discussions\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'discussion' 'create' action
 */
class CreateDiscussionEventHandler extends NotificationEventHandler {
		
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('discussion:topic:notify:subject', [$this->getEventEntity()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('discussion:topic:notify:summary', [$this->getEventEntity()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->getEventEntity();
		
		return elgg_echo('discussion:topic:notify:body', [
			$this->getEventActor()?->getDisplayName(),
			$entity?->getDisplayName(),
			$entity?->description,
			$entity?->getURL(),
		], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected static function isConfigurableForGroup(\ElggGroup $group): bool {
		return $group->isToolEnabled('forum');
	}
}
