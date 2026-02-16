<?php

namespace Elgg\Notifications\Events;

/**
 * Apply subscriptions based on preferences
 *
 * @since 4.0
 * @internal
 */
class CreateContent {
	
	/**
	 * Subscribe to content you just created in order to receive notifications
	 *
	 * @param \Elgg\Event $event 'create:after', 'object'|'group'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event): void {
		$entity = $event->getObject();
		if (!$entity instanceof \ElggObject && !$entity instanceof \ElggGroup) {
			return;
		}
		
		$owner = $entity->getOwnerEntity();
		if (!$owner instanceof \ElggUser) {
			return;
		}
		
		// If an object is subscribable we should subscribe the owner
		if ($entity instanceof \ElggObject && !$entity->hasCapability('subscribable')) {
			// not subscribable, so check if there are notification events for this object
			$notification_events = _elgg_services()->notifications->getEvents();
			if (!isset($notification_events[$entity->getType()]) || !isset($notification_events[$entity->getType()][$entity->getSubtype()])) {
				// no notification events registered for this entity type/subtype
				// so there is no need to subscribe
				// this also prevents the database from flooding with relationships that are never used (e.g. subscriptions to site notifications)
				return;
			}
		}
		
		$enabled_methods = $owner->getNotificationSettings('content_create', true);
		if (empty($enabled_methods)) {
			return;
		}
		
		$entity->addSubscription($owner->guid, $enabled_methods);
	}
}
