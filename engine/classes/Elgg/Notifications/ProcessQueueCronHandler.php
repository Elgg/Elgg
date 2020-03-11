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
	 * @param \Elgg\Hook $hook 'cron', 'minute'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Hook $hook) {
		// calculate when we should stop
		$stop_time = $hook->getParam('time') + (int) elgg_get_config('notifications_max_runtime', 45);
		_elgg_services()->notifications->processQueue($stop_time);
	}
}
