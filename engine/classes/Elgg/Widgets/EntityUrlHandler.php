<?php

namespace Elgg\Widgets;

/**
 * Returns widget urls
 *
 * @since 4.0
 */
class EntityUrlHandler {
	
	/**
	 * Returns widget URLS used in widget titles
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object'
	 *
	 * @return void|string
	 */
	public function __invoke(\Elgg\Event $event) {
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		switch ($widget->handler) {
			case 'content_stats':
				return 'admin/statistics';
			case 'cron_status':
				return 'admin/cron';
			case 'banned_users':
				return 'admin/users/banned';
			case 'new_users':
				return 'admin/users';
			case 'online_users':
				return 'admin/users/online';
			case 'elgg_blog':
				return 'https://elgg.org/blog/all';
		}
	}
}
