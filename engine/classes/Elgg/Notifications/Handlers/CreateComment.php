<?php

namespace Elgg\Notifications\Handlers;

use Elgg\Notifications\NonConfigurableNotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'comment' 'create' action
 *
 * @since 4.0
 */
class CreateComment extends NonConfigurableNotificationEventHandler {
	
	/**
	 * Tells if the recipient is the owner of the entity commented on
	 *
	 * @param \ElggUser $recipient the recipient to check
	 *
	 * @return bool
	 */
	protected function recipientIsCommentContainerOwner(\ElggUser $recipient): bool {
		return $this->getEventEntity()?->getContainerEntity()?->owner_guid === $recipient->guid;
	}
		
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('generic_comment:notification:subject', [$this->getEventEntity()?->getContainerEntity()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		if ($this->recipientIsCommentContainerOwner($recipient)) {
			return elgg_echo('generic_comment:notification:owner:summary', [$this->getEventEntity()?->getContainerEntity()?->getDisplayName()]);
		} else {
			return elgg_echo('generic_comment:notification:user:summary', [$this->getEventEntity()?->getContainerEntity()?->getDisplayName()]);
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->getEventEntity();
		
		$key = $this->recipientIsCommentContainerOwner($recipient) ? 'generic_comment:notification:owner:body' : 'generic_comment:notification:user:body';
		
		return elgg_echo($key, [
			elgg_get_excerpt((string) $entity?->description, 1000),
			$entity?->getURL(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function excludeOwnerSubscribers(): bool {
		return true;
	}
}
