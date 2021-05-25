<?php

namespace Elgg\SystemLog;

use Elgg\DefaultPluginBootstrap;

/**
 * Plugin bootstrap class
 *
 * @since 4.0
 */
class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 */
	public function deactivate() {
		// deregister logger events
		$events = $this->elgg()->events;
		
		$events->unregisterHandler('log', 'systemlog', 'Elgg\SystemLog\Logger::log');
		$events->unregisterHandler('all', 'all', 'Elgg\SystemLog\Logger::listen');
	}
}
