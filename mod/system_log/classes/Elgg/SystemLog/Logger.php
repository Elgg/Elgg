<?php

namespace Elgg\SystemLog;

/**
 * Event callbacks for systemlog
 *
 * @since 4.0
 * @internal
 */
class Logger {

	/**
	 * @var string[]
	 */
	protected static $sequence_ids = [];
	
	/**
	 * Default system log handler, allows plugins to override, extend or disable logging.
	 *
	 * @param \Elgg\Event $event 'log', 'systemlog'
	 *
	 * @return void
	 */
	public static function log(\Elgg\Event $event): void {
		$object = $event->getObject();
		SystemLog::instance()->insert($object['object'], $object['event']);
	}
	
	/**
	 * System log listener.
	 * This function listens to all events in the system and logs anything appropriate.
	 *
	 * @param \Elgg\Event $event 'all', 'all'
	 *
	 * @return void
	 */
	public static function listen(\Elgg\Event $event): void {
		$type = $event->getType();
		$name = $event->getName();
		
		if ($type === 'systemlog' && $name === 'log') {
			return;
		}
		
		// check if this event is part of a sequence and can't be logged yet
		// so we can delay the logging to the :after event
		$sequence_id = $event->getSequenceID();
		if (isset($sequence_id) && str_ends_with($name, ':before')) {
			$object = $event->getObject();
			if ($object instanceof \ElggData && empty($object->getSystemLogID())) {
				self::$sequence_ids[$sequence_id] = $sequence_id;
			}
		}
		
		// validate :before and :after events
		if (str_ends_with($name, ':after') || str_ends_with($name, ':before')) {
			if (isset($sequence_id) && isset(self::$sequence_ids[$sequence_id]) && str_ends_with($name, ':after')) {
				// this was a delayed event sequence
				unset(self::$sequence_ids[$sequence_id]);
			} elseif (!elgg()->events->hasHandler($name, $type) && !elgg()->events->hasHandler($name, 'all')) {
				// ignore before and after events if there are no event handlers registered
				return;
			}
		}
		
		elgg_trigger_event('log', 'systemlog', [
			'object' => $event->getObject(),
			'event' => "{$name}:{$type}",
		]);
	}
	
	/**
	 * Disables the logging
	 *
	 * @param \Elgg\Event $event 'all', 'all'
	 *
	 * @return void
	 */
	public static function disableLogging(\Elgg\Event $event): void {
		// disable the system log for upgrades to avoid exceptions when the schema changes.
		elgg_unregister_event_handler('log', 'systemlog', 'Elgg\SystemLog\Logger::log');
		elgg_unregister_event_handler('all', 'all', 'Elgg\SystemLog\Logger::listen');
	}
}
