<?php

namespace Elgg\Pages;

/**
 * Hook callbacks for notifications
 *
 * @since 4.0
 * @internal
 */
class Notifications {

	/**
	 * Prepare a notification message about a new page
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:page' | 'notification:create:object:page_top'
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function preparePageCreateNotification(\Elgg\Hook $hook) {
		
		$event = $hook->getParam('event');
		
		$entity = $event->getObject();
		if (!$entity instanceof \ElggPage) {
			return;
		}
		
		$owner = $event->getActor();
		$language = $hook->getParam('language');
	
		$descr = $entity->description;
		$title = $entity->getDisplayName();
	
		$notification = $hook->getValue();
		$notification->subject = elgg_echo('pages:notify:subject', [$title], $language);
		$notification->body = elgg_echo('pages:notify:body', [
			$owner->getDisplayName(),
			$title,
			$descr,
			$entity->getURL(),
		], $language);
		$notification->summary = elgg_echo('pages:notify:summary', [$entity->getDisplayName()], $language);
		$notification->url = $entity->getURL();
		
		return $notification;
	}
}
