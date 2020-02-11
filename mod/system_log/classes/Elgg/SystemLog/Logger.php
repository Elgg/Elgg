<?php

namespace Elgg\SystemLog;

/**
 * Hook callbacks for systemlog
 *
 * @since 4.0
 * @internal
 */
class Logger {

	/**
	 * Default system log handler, allows plugins to override, extend or disable logging.
	 *
	 * @param \Elgg\Event $event 'log', 'systemlog'
	 *
	 * @return true
	 */
	public static function log(\Elgg\Event $event) {
		$object = $event->getObject();
		system_log($object['object'], $object['event']);
	
		return true;
	}
	
	/**
	 * System log listener.
	 * This function listens to all events in the system and logs anything appropriate.
	 *
	 * @param \Elgg\Event $event 'all', 'all'
	 *
	 * @return true
	 */
	public static function listen(\Elgg\Event $event) {
		if (($event->getType() != 'systemlog') && ($event->getName() != 'log')) {
			elgg_trigger_event('log', 'systemlog', ['object' => $event->getObject(), 'event' => $event->getName()]);
		}
	
		return true;
	}
	
	/**
	 * Disables the logging
	 *
	 * @param \Elgg\Event $event 'all', 'all'
	 *
	 * @return true
	 */
	public static function disableLogging(\Elgg\Event $event) {
		// disable the system log for upgrades to avoid exceptions when the schema changes.
		elgg_unregister_event_handler('log', 'systemlog', 'Elgg\SystemLog\Logger::log');
		elgg_unregister_event_handler('all', 'all', 'Elgg\SystemLog\Logger::listen');
	}
}
