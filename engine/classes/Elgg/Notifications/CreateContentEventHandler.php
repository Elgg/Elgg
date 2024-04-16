<?php

namespace Elgg\Notifications;

/**
 * Apply subscriptions based on preferences
 *
 * @since 4.0
 * @internal
 */
class CreateContentEventHandler {
	
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
		
		if ($entity instanceof \ElggObject) {
			$notification_events = _elgg_services()->notifications->getEvents();
			if (!isset($notification_events[$entity->getType()]) || !isset($notification_events[$entity->getType()][$entity->getSubtype()])) {
				// no notification events registered for this entity type/subtype
				// so there is no need to subscribe
				// this also prevents the database from flooding with relationships that are never used (e.g. subscriptions to site notifications)
				return;
			}
		}
		
		$content_preferences = $owner->getNotificationSettings('content_create');
		$enabled_methods = array_keys(array_filter($content_preferences));
		
		// loop through all notification types
		$methods = elgg_get_notification_methods();
		foreach ($enabled_methods as $method) {
			// only enable supported methods
			if (!in_array($method, $methods)) {
				continue;
			}
			
			$entity->addSubscription($owner->guid, $method);
		}
	}
}
