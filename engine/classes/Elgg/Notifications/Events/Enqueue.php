<?php

namespace Elgg\Notifications\Events;

/**
 * Enqueue notification event
 *
 * @since 4.0
 * @internal
 */
class Enqueue {
	
	/**
	 * Queue a notification event for later handling
	 *
	 * Checks to see if this event has been registered for notifications.
	 * If so, it adds the event to a notification queue.
	 *
	 * This function triggers the 'enqueue', 'notification' event.
	 *
	 * @param \Elgg\Event $event 'all', 'all'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$object = $event->getObject();
		if (!$object instanceof \ElggData) {
			return;
		}
		
		_elgg_services()->notifications->enqueueEvent($event->getName(), $object);
	}
}
