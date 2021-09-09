<?php

namespace Elgg\Notifications;

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
		return elgg_echo('generic_comment:notification:subject', [$this->event->getObject()->getContainerEntity()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		if ($this->recipientIsCommentContainerOwner($recipient)) {
			return elgg_echo('generic_comment:notification:owner:summary', [$this->event->getObject()->getContainerEntity()->getDisplayName()], $recipient->getLanguage());
		} else {
			return elgg_echo('generic_comment:notification:user:summary', [$this->event->getObject()->getContainerEntity()->getDisplayName()], $recipient->getLanguage());
		}
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->event->getObject();
		
		$key = $this->recipientIsCommentContainerOwner($recipient) ? 'generic_comment:notification:owner:body' : 'generic_comment:notification:user:body';
		
		return elgg_echo($key, [
			elgg_get_excerpt($entity->description, 1000),
			$entity->getURL(),
		], $recipient->getLanguage());
	}
	
	/**
	 * Is this event configurable by the user on the notification settings page
	 *
	 * @return bool
	 */
	public static function isConfigurableByUser(): bool {
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function excludeOwnerSubscribers(): bool {
		return true;
	}
}
