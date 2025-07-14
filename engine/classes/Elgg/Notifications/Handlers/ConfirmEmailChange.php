<?php

namespace Elgg\Notifications\Handlers;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * Confirm a user e-mail change to the user
 *
 * @since 6.3
 */
class ConfirmEmailChange extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('email:confirm:email:new:subject');
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$site = elgg_get_site_entity();
		
		return elgg_echo('email:confirm:email:new:body', [
			$site->getDisplayName(),
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
