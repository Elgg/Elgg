<?php

namespace Elgg\Email\DelayedQueue;

/**
 * Cron based handing of the delayed email notification queue
 *
 * @since 4.0
 * @internal
 */
class CronHandler {
	
	/**
	 * Cron handler to dequeue en handle delayed emails
	 *
	 * @param \Elgg\Event $event 'cron', 'daily'|'weekly'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event): void {
		$time = (int) $event->getParam('time');
		$interval = $event->getType();
		
		_elgg_services()->delayedEmailService->processQueuedNotifications($interval, $time);
	}
}
