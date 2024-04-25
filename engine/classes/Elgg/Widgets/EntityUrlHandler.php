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
	 * @param \Elgg\Event $event 'entity:url', 'object:widget'
	 *
	 * @return null|string
	 */
	public function __invoke(\Elgg\Event $event): ?string {
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return null;
		}
		
		$admin_segments = null;
		switch ($widget->handler) {
			case 'content_stats':
				$admin_segments = 'statistics';
				break;
			case 'cron_status':
				$admin_segments = 'cron';
				break;
			case 'banned_users':
				$admin_segments = 'users/banned';
				break;
			case 'new_users':
				$admin_segments = 'users';
				break;
			case 'online_users':
				$admin_segments = 'users/online';
				break;
			case 'elgg_blog':
				return 'https://elgg.org/blog/all';
		}
		
		return $admin_segments ? elgg_generate_url('admin', ['segments' => $admin_segments]) : null;
	}
}
