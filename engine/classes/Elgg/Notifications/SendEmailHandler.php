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
	 * @param \Elgg\Hook $hook 'send', 'notification:email'
	 *
	 * @return void|bool
	 */
	public function __invoke(\Elgg\Hook $hook) {
		if ($hook->getValue() === true) {
			// assume someone else already sent the message
			return;
		}
	
		$message = $hook->getParam('notification');
		if (!$message instanceof \Elgg\Notifications\Notification) {
			return false;
		}
	
		$sender = $message->getSender();
		if (!$sender) {
			return false;
		}
	
		$recipient = $message->getRecipient();
		if (!$recipient || !$recipient->email) {
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
