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
	 * @param \Elgg\Event $event 'prepare', 'system:email'
	 *
	 * @return void|\Elgg\Email
	 */
	public function __invoke(\Elgg\Event $event) {
		$email = $event->getValue();
		if (!$email instanceof \Elgg\Email) {
			return;
		}
		
		$params = $email->getParams();
		
		$notification = elgg_extract('notification', $params);
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
		
		$message_id = $email->createEntityMessageID($object, $event->getAction() !== 'create');
		$email->addHeader('Message-ID', $message_id);
	
		// let's just thread comments by default
		$container = $object->getContainerEntity();
		if ($container instanceof \ElggEntity && $object instanceof \ElggComment) {
			$thread_message_id = "<{$email->createEntityMessageID($container)}>";
			
			$email->addHeader('In-Reply-To', $thread_message_id);
			$email->addHeader('References', $thread_message_id);
		}
		
		return $email;
	}
}
