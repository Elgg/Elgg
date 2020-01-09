<?php

namespace Elgg\GarbageCollector;

use Elgg\DefaultPluginBootstrap;

/**
 * Plugin bootstrap
 */
class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritDoc}
	 */
	public function load() {
		require_once elgg_get_plugins_path() . 'garbagecollector/lib/deprecated.php';
	}

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		elgg_register_plugin_hook_handler('cron', 'all', CronRunner::class);
	}
}
