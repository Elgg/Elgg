<?php

namespace Elgg\GarbageCollector;

/**
 * Garbagecollector cron job
 */
class CronRunner {

	/**
	 * Garbagecollector cron job
	 *
	 * @param \Elgg\Event $event event
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {

		$period = $event->getType();

		if ($period !== elgg_get_plugin_setting('period', 'garbagecollector')) {
			return;
		}

		// Now, because we are nice, trigger an event to let other plugins do some GC
		elgg_trigger_event_results('gc', 'system', ['period' => $period]);

		$ops = GarbageCollector::instance()->optimize();

		$output = [];
		foreach ($ops as $op) {
			$ok = $op->result ? 'ok' : 'err';
			$output[] = $op->operation . ': ' . $ok . '. Completed: ' . $op->completed->format(DATE_ATOM);
		}

		echo implode(PHP_EOL, $output) . PHP_EOL;
	}
}
