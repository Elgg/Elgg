<?php

namespace Elgg\Notifications;

/**
 * Enqueue notification event
 *
 * @since 4.0
 */
class EnqueueEventHandler {
	
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
