<?php

namespace Elgg\ReportedContent\Notifications;

/**
 * Prevents comments notifications
 *
 * @since 5.0
 */
class PreventCommentNotification {
	
	/**
	 * Prevent comment notifications for reported content
	 *
	 * @param \Elgg\Event $event 'enqueue', 'notification'
	 *
	 * @return bool|null
	 */
	public function __invoke(\Elgg\Event $event): ?bool {
		$object = $event->getObject();
		if (!$object instanceof \ElggComment) {
			return null;
		}
		
		$container = $object->getContainerEntity();
		if (!$container instanceof \ElggReportedContent) {
			return null;
		}
		
		return false;
	}
}
