<?php

namespace Elgg\Notifications;

/**
 * Enqueue mention notifications
 *
 * @since 5.0
 */
class MentionsEnqueueEventHandler {
	
	/**
	 * @var array Contains entity GUIDs already queued this script run (to prevent doubles)
	 */
	protected static array $queued = [];
	
	/**
	 * Queue a mentions notification event for later handling
	 *
	 * @param \Elgg\Event $event 'create:after'|'update:after', 'all'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$object = $event->getObject();
		if (!$object instanceof \ElggEntity || $object->access_id === ACCESS_PRIVATE || in_array($object->guid, self::$queued)) {
			return;
		}
		
		// prevent double enqueue
		self::$queued[] = $object->guid;
		
		_elgg_services()->notifications->enqueueEvent('mentions', $object);
	}
}
