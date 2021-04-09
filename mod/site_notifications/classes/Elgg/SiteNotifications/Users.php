<?php

namespace Elgg\SiteNotifications;

/**
 * User related hook / event handling
 *
 * @since 4.0
 * @internal
 */
class Users {
	
	/**
	 * Enable site notifications when a user is created
	 *
	 * @param \Elgg\Event $event 'create', 'user'
	 *
	 * @return void
	 */
	public static function enableSiteNotifications(\Elgg\Event $event): void {
		
		$user = $event->getObject();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$user->setNotificationSetting('site', true);
	}
}
