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
	 * @param \Elgg\Hook $hook 'cron', 'daily'|'weekly'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Hook $hook): void {
		$time = $hook->getParam('time');
		$interval = $hook->getType();
		
		_elgg_services()->delayedEmailService->processQueuedNotifications($interval, $time);
	}
}
