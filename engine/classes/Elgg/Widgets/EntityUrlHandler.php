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
	 * @param \Elgg\Hook $hook 'entity:url', 'object'
	 *
	 * @return void|string
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$widget = $hook->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		switch ($widget->handler) {
			case 'content_stats':
				return 'admin/statistics';
			case 'cron_status':
				return 'admin/cron';
			case 'new_users':
				return 'admin/users/newest';
			case 'online_users':
				return 'admin/users/online';
		}
	}
}
