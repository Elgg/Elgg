<?php

namespace Elgg\UserValidationByEmail\Notifications;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * Validate the e-mail address of a new user
 *
 * @since 6.3
 */
class ValidateEmailHandler extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$site = elgg_get_site_entity();
		$user = $this->event->getObject();
		if (!$user instanceof \ElggUser) {
			return parent::getNotificationSubject($recipient, $method);
		}
		
		return elgg_echo('email:validate:subject', [
			$user->getDisplayName(),
			$site->getDisplayName(),
		]);
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
		
		$link = elgg_generate_url('account:validation:email:confirm', [
			'u' => $user->guid,
		]);
		
		return elgg_echo('email:validate:body', [
			$site->getDisplayName(),
			elgg_http_get_signed_url($link),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationMethods(): array {
		return ['email'];
	}
}
