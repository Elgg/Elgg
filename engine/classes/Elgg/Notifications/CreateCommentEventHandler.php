<?php

namespace Elgg\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'comment' 'create' action
 *
 * @since 4.0
 */
class CreateCommentEventHandler extends NotificationEventHandler {

	/**
	 * Tells if the recipient is the owner of the entity commented on
	 *
	 * @return bool
	 */
	protected function recipientIsCommentContainerOwner(\ElggUser $recipient): bool {
		return $this->event->getObject()->getContainerEntity()->owner_guid === $recipient->guid;
	}
		
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		if ($this->recipientIsCommentContainerOwner($recipient)) {
			return elgg_echo('generic_comment:notification:owner:subject', [], $recipient->getLanguage());
		} else {
			return elgg_echo('generic_comment:notification:user:subject', [$this->event->getObject()->getDisplayName()], $recipient->getLanguage());
		}
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		if ($this->recipientIsCommentContainerOwner($recipient)) {
			return elgg_echo('generic_comment:notification:owner:summary', [], $recipient->getLanguage());
		} else {
			return elgg_echo('generic_comment:notification:user:summary', [$this->event->getObject()->getDisplayName()], $recipient->getLanguage());
		}
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->event->getObject();
		
		$key = $this->recipientIsCommentContainerOwner($recipient) ? 'generic_comment:notification:owner:body' : 'generic_comment:notification:user:body';
		
		return elgg_echo($key, [
			$entity->getContainerEntity()->getDisplayName(),
			$entity->getOwnerEntity()->getDisplayName(),
			$entity->description,
			$entity->getURL(),
		], $recipient->getLanguage());
	}
}
