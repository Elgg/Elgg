<?php

namespace Elgg\Email\DelayedQueue;

use Elgg\Notifications\Notification;

/**
 * Handle the queueing of delayed email notifications
 *
 * @since 4.0
 * @internal
 */
class EnqueueHandler {
	
	/**
	 * Handle the 'sending' of the delayed email method
	 *
	 * @param \Elgg\Event $event 'send', 'notification:delayed_email'
	 *
	 * @return null|bool
	 */
	public function __invoke(\Elgg\Event $event): ?bool {
		if ($event->getValue() === true) {
			// assume someone else already sent the message
			return null;
		}
		
		$notification = $event->getParam('notification');
		if (!$notification instanceof Notification) {
			return false;
		}
		
		return _elgg_services()->delayedEmailService->enqueueNotification($notification);
	}
}
