<?php

namespace Elgg\Notifications;

/**
 * Sends email
 *
 * @since 4.0
 */
class SendEmailHandler {
	
	/**
	 * Send an email notification
	 *
	 * @param \Elgg\Event $event 'send', 'notification:email'
	 *
	 * @return void|bool
	 */
	public function __invoke(\Elgg\Event $event) {
		if ($event->getValue() === true) {
			// assume someone else already sent the message
			return;
		}
	
		$message = $event->getParam('notification');
		if (!$message instanceof \Elgg\Notifications\Notification) {
			return false;
		}
	
		$sender = $message->getSender();
		$recipient = $message->getRecipient();
		if (!$recipient->email) {
			return false;
		}
	
		$email = \Elgg\Email::factory([
			'from' => $sender,
			'to' => $recipient,
			'subject' => $message->subject,
			'body' => $message->body,
			'params' => $message->params,
		]);
	
		return _elgg_services()->emails->send($email);
	}
}
