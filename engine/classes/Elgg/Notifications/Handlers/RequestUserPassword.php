<?php

namespace Elgg\Notifications\Handlers;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * E-mail the password reset to the user
 *
 * @since 6.3
 */
class RequestUserPassword extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('email:changereq:subject');
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$user = $this->event->getObject();
		if (!$user instanceof \ElggUser) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		$link = elgg_generate_url('account:password:change', [
			'u' => $user->guid,
			'c' => $user->passwd_conf_code,
		]);
		$link = elgg_http_get_signed_url($link, '+1 day');
		
		return elgg_echo('email:changereq:body', [
			(string) $this->getParam('ip_address'),
			$link,
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationMethods(): array {
		return ['email'];
	}
}
