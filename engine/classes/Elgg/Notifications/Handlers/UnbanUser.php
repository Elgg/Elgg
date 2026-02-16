<?php

namespace Elgg\Notifications\Handlers;

use Elgg\Notifications\NonConfigurableNotificationEventHandler;

/**
 * Notification Event Handler for 'user' 'user' 'unban' action
 *
 * @since 4.0
 */
class UnbanUser extends NonConfigurableNotificationEventHandler {
	
	/**
	 * Tells if the recipient is the user being unbanned
	 *
	 * @param \ElggUser $recipient the recipient to check
	 *
	 * @return bool
	 */
	protected function recipientIsBannedUser(\ElggUser $recipient): bool {
		return $this->getEventEntity()?->guid === $recipient->guid;
	}
		
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		if (!$this->recipientIsBannedUser($recipient)) {
			return parent::getNotificationSubject($recipient, $method);
		}
		
		return elgg_echo('user:notification:unban:subject', [elgg_get_site_entity()->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		if (!$this->recipientIsBannedUser($recipient)) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		$site = elgg_get_site_entity();
		
		return elgg_echo('user:notification:unban:body', [
			$site->getDisplayName(),
			$site->getURL(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function addMuteLink(): bool {
		return false;
	}
	
	/**
	 * Add the user to the subscribers when (un)banning the account
	 *
	 * {@inheritdoc}
	 */
	public function getSubscriptions(): array {
		$result = parent::getSubscriptions();
		
		if (_elgg_services()->config->security_notify_user_ban) {
			$result[$this->getEventEntity()?->guid] = ['email'];
		}
		
		return $result;
	}
}
