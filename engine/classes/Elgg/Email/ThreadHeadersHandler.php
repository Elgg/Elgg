<?php

namespace Elgg\Email;

/**
 * Sets the thread headers for emails
 *
 * @since 4.0
 */
class ThreadHeadersHandler {
	
	/**
	 * Adds default thread SMTP headers to group messages correctly.
	 * Note that it won't be sufficient for some email clients. Ie. Gmail is looking at message subject anyway.
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'system:email'
	 *
	 * @return void|\Elgg\Email
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$email = $hook->getValue();
		if (!$email instanceof \Elgg\Email) {
			return;
		}
	
		$notificationParams = $email->getParams();
	
		$notification = elgg_extract('notification', $notificationParams);
		if (!$notification instanceof \Elgg\Notifications\Notification) {
			return;
		}
	
		$object = elgg_extract('object', $notification->params);
		if (!$object instanceof \ElggEntity) {
			return;
		}
	
		$event = elgg_extract('event', $notification->params);
		if (!$event instanceof \Elgg\Notifications\NotificationEvent) {
			return;
		}
	
		$hostname = parse_url(elgg_get_site_url(), PHP_URL_HOST);
		$urlPath = parse_url(elgg_get_site_url(), PHP_URL_PATH);
	
		if ($event->getAction() === 'create') {
			// create event happens once per entity and we need to guarantee message id uniqueness
			// and at the same time have thread message id that we don't need to store
			$messageId = "{$urlPath}.entity.{$object->guid}@{$hostname}";
		} else {
			$mt = microtime(true);
			$messageId = "{$urlPath}.entity.{$object->guid}.$mt@{$hostname}";
		}
	
		$email->addHeader('Message-ID', $messageId);
	
		// let's just thread comments by default
		$container = $object->getContainerEntity();
		if ($container instanceof \ElggEntity && $object instanceof \ElggComment) {
			$threadMessageId = "<{$urlPath}.entity.{$container->guid}@{$hostname}>";
			$email->addHeader('In-Reply-To', $threadMessageId);
			$email->addHeader('References', $threadMessageId);
		}
	
		return $email;
	}
}
