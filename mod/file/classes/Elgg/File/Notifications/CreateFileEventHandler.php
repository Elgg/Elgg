<?php

namespace Elgg\File\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'file' 'create' action
 */
class CreateFileEventHandler extends NotificationEventHandler {

	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('file:notify:subject', [$this->getEventEntity()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('file:notify:summary', [$this->getEventEntity()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->getEventEntity();
		
		return elgg_echo('file:notify:body', [
			$this->getEventActor()?->getDisplayName(),
			$entity?->getDisplayName(),
			$entity?->description,
			$entity?->getURL(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected static function isConfigurableForGroup(\ElggGroup $group): bool {
		return $group->isToolEnabled('file');
	}
}
