<?php

namespace Elgg\SystemLog\Menus;

/**
 * Event callbacks for menus
 *
 * @since 5.0
 * @internal
 */
class AdminHeader {
	
	/**
	 * Add to the page menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in() || !elgg_in_context('admin')) {
			return;
		}
	
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'administer_utilities:logbrowser',
			'text' => elgg_echo('admin:administer_utilities:logbrowser'),
			'href' => 'admin/administer_utilities/logbrowser',
			'parent_name' => 'utilities',
		]);
	
		return $return;
	}
}
