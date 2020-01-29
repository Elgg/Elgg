<?php

namespace Elgg\Blog;

/**
 * Hook callbacks for notifications
 *
 * @since 4.0
 * @internal
 */
class Notifications {

	/**
	 * Prepare notification
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:publish:object:blog'
	 *
	 * @return \Elgg\Notifications\Notification
	 */
	public static function preparePublishBlog(\Elgg\Hook $hook) {
		$notification = $hook->getValue();
		$entity = $hook->getParam('event')->getObject();
		$owner = $hook->getParam('event')->getActor();
		$language = $hook->getParam('language');
	
		$notification->subject = elgg_echo('blog:notify:subject', [$entity->getDisplayName()], $language);
		$notification->body = elgg_echo('blog:notify:body', [
			$owner->getDisplayName(),
			$entity->getDisplayName(),
			$entity->getExcerpt(),
			$entity->getURL()
		], $language);
		$notification->summary = elgg_echo('blog:notify:summary', [$entity->getDisplayName()], $language);
		$notification->url = $entity->getURL();
	
		return $notification;
	}
}
