<?php

namespace Elgg\GarbageCollector;

use Elgg\Hook;

/**
 * Garbagecollector cron job
 */
class CronRunner {

	/**
	 * Garbagecollector cron job
	 *
	 * @param Hook $hook Hook
	 *
	 * @return void
	 * @throws \DatabaseException
	 */
	public function __invoke(Hook $hook) {

		$period = $hook->getType();

		if ($period !== elgg_get_plugin_setting('period', 'garbagecollector')) {
			return;
		}

		// Now, because we are nice, trigger a plugin hook to let other plugins do some GC
		elgg_trigger_plugin_hook('gc', 'system', ['period' => $period]);

		$ops = GarbageCollector::instance()->optimize();

		$output = [];
		foreach ($ops as $op) {
			$ok = $op->result ? 'ok' : 'err';
			$output[] = $op->operation . '\t' . $ok . '\t' . $op->completed->format(DATE_ATOM);
		}

		echo implode(PHP_EOL, $output);
	}
}