<?php

namespace Elgg\Bookmarks;

/**
 * Hook callbacks for notifications
 *
 * @since 4.0
 *
 * @internal
 */
class Notifications {

	/**
	 * Prepare notification
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:bookmarks'
	 *
	 * @return \Elgg\Notifications\Notification
	 */
	public static function prepareCreateBookmark(\Elgg\Hook $hook) {
		$entity = $hook->getParam('event')->getObject();
		$owner = $hook->getParam('event')->getActor();
		$language = $hook->getParam('language');
	
		$descr = $entity->description;
		$title = $entity->getDisplayName();
	
		$notification = $hook->getValue();
		
		$notification->subject = elgg_echo('bookmarks:notify:subject', [$title], $language);
		$notification->body = elgg_echo('bookmarks:notify:body', [
			$owner->getDisplayName(),
			$title,
			$entity->address,
			$descr,
			$entity->getURL()
		], $language);
		$notification->summary = elgg_echo('bookmarks:notify:summary', [$title], $language);
		$notification->url = $entity->getURL();
		return $notification;
	}
}
