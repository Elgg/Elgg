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
		/* @var Elgg\Notifications\Notification */
		$notification = $hook->getParam('notification');
		if ($notification->summary) {
			$message = $notification->summary;
		} else {
			$message = $notification->subject;
		}
	
		$object = null;
		$event = $hook->getParam('event');
		if (isset($event)) {
			$object = $event->getObject();
		}
	
		$actor = $notification->getSender();
		$recipient = $notification->getRecipient();
		$url = $notification->url;
		
		$note = elgg_call(ELGG_IGNORE_ACCESS, function() use ($recipient, $message, $actor, $object, $url) {
			return \SiteNotificationFactory::create($recipient, $message, $actor, $object, $url);
		});
		
		if ($note instanceof \SiteNotification) {
			return true;
		}
	}
}
