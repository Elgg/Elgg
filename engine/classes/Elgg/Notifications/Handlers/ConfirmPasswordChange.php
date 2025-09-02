<?php

namespace Elgg\Notifications\Handlers;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * Confirm a user password change to the user
 *
 * @since 6.3
 */
class ConfirmPasswordChange extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('user:notification:password_change:subject');
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$site = elgg_get_site_entity();
		$user = $this->event->getObject();
		if (!$user instanceof \ElggUser) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		return elgg_echo('user:notification:password_change:body', [
			$site->getDisplayName(),
			elgg_generate_url('account:password:reset'),
			$site->getURL(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationMethods(): array {
		return ['email'];
	}
}
