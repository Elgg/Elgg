<?php

namespace Elgg\GarbageCollector;

/**
 * Garbage collector cron job
 */
class CronRunner {

	/**
	 * Garbage collector cron job
	 *
	 * @param \Elgg\Event $event 'cron', 'all'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event): void {
		$period = $event->getType();
		if ($period !== elgg_get_plugin_setting('period', 'garbagecollector')) {
			return;
		}
		
		/* @var $cron_logger \Elgg\Logger\Cron */
		$cron_logger = $event->getParam('logger');
		
		// Now, because we are nice, trigger an event to let other plugins do some GC
		$params = $event->getParams();
		$params['period'] = $period;
		elgg_trigger_event_results('gc', 'system', $params);
		
		if ((bool) elgg_get_plugin_setting('optimize', 'garbagecollector')) {
			// optimize database tables
			$instance = GarbageCollector::instance();
			$instance->setLogger($cron_logger);
			
			$instance->optimize(true);
		}
	}
}
