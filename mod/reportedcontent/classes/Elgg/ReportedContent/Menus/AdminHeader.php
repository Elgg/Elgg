<?php

namespace Elgg\ReportedContent\Menus;

/**
 * Event callbacks for menus
 *
 * @since 5.0
 * @internal
 */
class AdminHeader {
	
	/**
	 * Add report user link to hover menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
				
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'administer_utilities:reportedcontent',
			'text' => elgg_echo('admin:administer_utilities:reportedcontent'),
			'href' => 'admin/administer_utilities/reportedcontent',
			'parent_name' => 'administer',
		]);
	
		return $return;
	}
}
