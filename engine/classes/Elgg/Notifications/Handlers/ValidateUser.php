<?php

namespace Elgg\Notifications\Handlers;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * E-mail the user that a site administrator validated their account
 *
 * @since 6.3
 */
class ValidateUser extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$site = elgg_get_site_entity();
		
		return elgg_echo('user:notification:validate:subject', [
			$site->getDisplayName(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$site = elgg_get_site_entity();
		
		return elgg_echo('user:notification:validate:body', [
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
