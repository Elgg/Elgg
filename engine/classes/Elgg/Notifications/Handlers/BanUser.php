<?php

namespace Elgg\Notifications\Handlers;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * E-mail the user that their account got banned
 *
 * @since 6.3
 */
class BanUser extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$site = elgg_get_site_entity();
		
		return elgg_echo('user:notification:ban:subject', [$site->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$site = elgg_get_site_entity();
		
		return elgg_echo('user:notification:ban:body', [
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
