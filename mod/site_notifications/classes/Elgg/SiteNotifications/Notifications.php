<?php

namespace Elgg\SiteNotifications;

/**
 * Hook callbacks for notifications
 *
 * @since 4.0
 * @internal
 */
class Notifications {

	/**
	 * Create a site notification
	 *
	 * @param \Elgg\Hook $hook 'send', 'notification:site'
	 *
	 * @return void|true
	 */
	public static function createSiteNotifications(\Elgg\Hook $hook) {
		/* @var $notification \Elgg\Notifications\Notification */
		$notification = $hook->getParam('notification');
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = $hook->getParam('event');
		
		$note = elgg_call(ELGG_IGNORE_ACCESS | ELGG_DISABLE_SYSTEM_LOG, function() use ($notification, $event) {
			return \SiteNotification::factory($notification, $event);
		});
		
		if ($note instanceof \SiteNotification) {
			return true;
		}
	}
}
