<?php

namespace Elgg\Notifications;

/**
 * Notification Event Handler for 'user' 'user' 'unban' action
 *
 * @since 4.0
 */
class UnbanUserEventHandler extends NotificationEventHandler {
	
	/**
	 * Tells if the recipient is the user being unbanned
	 *
	 * @return bool
	 */
	protected function recipientIsBannedUser(\ElggUser $recipient): bool {
		return $this->event->getObject()->guid === $recipient->guid;
	}
		
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		if (!$this->recipientIsBannedUser($recipient)) {
			return parent::getNotificationSubject($recipient, $method);
		}
		
		return elgg_echo('user:notification:unban:subject', [elgg_get_site_entity()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		if (!$this->recipientIsBannedUser($recipient)) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		$site = elgg_get_site_entity();
		
		return elgg_echo('user:notification:unban:body', [
			$site->getDisplayName(),
			$site->getURL(),
		], $recipient->getLanguage());
	}
	
	/**
	 * Add the user to the subscribers when (un)banning the account
	 *
	 * {@inheritDoc}
	 */
	public function getSubscriptions(): array {
		$result = parent::getSubscriptions();
		
		if (_elgg_services()->config->security_notify_user_ban) {
			$result[$this->event->getObject()->guid] = ['email'];
		}
		
		return $result;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public static function isConfigurableByUser(): bool {
		return false;
	}
}
