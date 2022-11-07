<?php

namespace Elgg\SiteNotifications;

/**
 * Event callbacks for notifications
 *
 * @since 4.0
 * @internal
 */
class Notifications {

	/**
	 * Create a site notification
	 *
	 * @param \Elgg\Event $elgg_event 'send', 'notification:site'
	 *
	 * @return void|true
	 */
	public static function createSiteNotifications(\Elgg\Event $elgg_event) {
		/* @var $notification \Elgg\Notifications\Notification */
		$notification = $elgg_event->getParam('notification');
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = $elgg_event->getParam('event');
		
		$note = elgg_call(ELGG_IGNORE_ACCESS | ELGG_DISABLE_SYSTEM_LOG, function() use ($notification, $event) {
			return \SiteNotification::factory($notification, $event);
		});
		
		if ($note instanceof \SiteNotification) {
			return true;
		}
	}
}
