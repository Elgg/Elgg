<?php

namespace Elgg\Notifications;

/**
 * Process the notification queue
 *
 * @since 4.0
 */
class ProcessQueueCronHandler {
	
	/**
	 * Process notification queue
	 *
	 * @param \Elgg\Event $event 'cron', 'minute'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		// calculate when we should stop
		$stop_time = $event->getParam('time') + (int) elgg_get_config('notifications_max_runtime');
		_elgg_services()->notifications->processQueue($stop_time);
	}
}
