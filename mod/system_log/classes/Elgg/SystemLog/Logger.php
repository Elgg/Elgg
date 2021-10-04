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
		SystemLog::instance()->insert($object['object'], $object['event']);
	
		return true;
	}
	
	/**
	 * System log listener.
	 * This function listens to all events in the system and logs anything appropriate.
	 *
	 * @param \Elgg\Event $event 'all', 'all'
	 *
	 * @return void
	 */
	public static function listen(\Elgg\Event $event) {
		$type = $event->getType();
		$name = $event->getName();
		
		if ($type === 'systemlog' && $name === 'log') {
			return;
		}
		
		// ignore before and after events if there are no event handlers registered
		if (strpos($name, ':after') > 0 || strpos($name, ':before') > 0) {
			if (!elgg()->events->hasHandler($name, $type) && !elgg()->events->hasHandler($name, 'all')) {
				return;
			}
		}
		
		elgg_trigger_event('log', 'systemlog', ['object' => $event->getObject(), 'event' => $event->getName()]);
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
