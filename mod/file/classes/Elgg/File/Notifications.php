<?php

namespace Elgg\File;

/**
 * Hook callbacks for notifications
 *
 * @since 4.0
 *
 * @internal
 */
class Notifications {

	/**
	 * Prepare a notification message about a new file
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:file'
	 *
	 * @return Elgg\Notifications\Notification
	 */
	public static function prepareCreateFile(\Elgg\Hook $hook) {
		$entity = $hook->getParam('event')->getObject();
		$owner = $hook->getParam('event')->getActor();
		$language = $hook->getParam('language');
	
		$descr = $entity->description;
		$title = $entity->getDisplayName();
		
		$notification = $hook->getValue();
		$notification->subject = elgg_echo('file:notify:subject', [$title], $language);
		$notification->body = elgg_echo('file:notify:body', [
			$owner->getDisplayName(),
			$title,
			$descr,
			$entity->getURL()
		], $language);
		$notification->summary = elgg_echo('file:notify:summary', [$title], $language);
		$notification->url = $entity->getURL();
		return $notification;
	}
}
