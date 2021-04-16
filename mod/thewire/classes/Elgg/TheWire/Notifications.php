<?php

namespace Elgg\TheWire;

/**
 * Hook callbacks for notifications
 *
 * @since 4.0
 * @internal
 */
class Notifications {

	/**
	 * Prepare a notification message about a new wire post
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:thewire'
	 *
	 * @return \Elgg\Notifications\Notification
	 */
	public static function prepareCreateTheWireNotification(\Elgg\Hook $hook) {
	
		$entity = $hook->getParam('event')->getObject();
		$owner = $hook->getParam('event')->getActor();
		$language = $hook->getParam('language');
		$descr = $entity->description;
	
		$subject = elgg_echo('thewire:notify:subject', [$owner->getDisplayName()], $language);
		$body = '';
		if ($entity->reply) {
			$parent = thewire_get_parent($entity->guid);
			if ($parent) {
				$parent_owner = $parent->getOwnerEntity();
				$body = elgg_echo('thewire:notify:reply', [$owner->getDisplayName(), $parent_owner->getDisplayName()], $language);
			}
		} else {
			$body = elgg_echo('thewire:notify:post', [$owner->getDisplayName()], $language);
		}
		$body .= "\n\n" . $descr . "\n\n";
		$body .= elgg_echo('thewire:notify:footer', [$entity->getURL()], $language);
	
		$notification = $hook->getValue();
		$notification->subject = $subject;
		$notification->body = $body;
		$notification->summary = elgg_echo('thewire:notify:summary', [elgg_get_excerpt($descr)], $language);
		$notification->url = $entity->getURL();
		
		return $notification;
	}
	
	/**
	 * Add temporary subscription for original poster if not already registered to
	 * receive a notification of reply
	 *
	 * @param \Elgg\Hook $hook 'get', 'subscriptions'
	 *
	 * @return void|array
	 */
	public static function addOriginalPoster(\Elgg\Hook $hook) {
		$event = $hook->getParam('event');
		if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
			return;
		}
	
		if ($event->getAction() !== 'create') {
			return;
		}
		
		$entity = $event->getObject();
		if (!$entity instanceof \ElggWire) {
			return;
		}
		
		$parents = $entity->getEntitiesFromRelationship([
			'type' => 'object',
			'subtype' => 'thewire',
			'relationship' => 'parent',
		]);
		if (empty($parents)) {
			return;
		}
		
		/* @var $parent \ElggWire */
		$parent = $parents[0];
		// do not add a subscription if reply was to self
		if ($parent->owner_guid === $entity->owner_guid) {
			return;
		}
		$subscriptions = $hook->getValue();
		if (array_key_exists($parent->owner_guid, $subscriptions)) {
			// already in the list
			return;
		}
		
		/* @var $parent_owner \ElggUser */
		$parent_owner = $parent->getOwnerEntity();
		$personal_methods = $parent_owner->getNotificationSettings();
		$methods = [];
		foreach ($personal_methods as $method => $state) {
			if ($state) {
				$methods[] = $method;
			}
		}
		
		if (empty($methods)) {
			return;
		}
		
		$subscriptions[$parent->owner_guid] = $methods;
		return $subscriptions;
	}
}
