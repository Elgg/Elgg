<?php

namespace Elgg\Notifications\Handlers;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * E-mail about the password reset for a user by a site administrator
 *
 * @since 6.3
 */
class ResetUserPassword extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('email:resetpassword:subject');
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		return elgg_echo('email:resetpassword:body', [
			(string) $this->getParam('password'),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationMethods(): array {
		return ['email'];
	}
}
