<?php

namespace Elgg\ExternalPages\Menus;

/**
 * Event callbacks for menus
 *
 * @since 5.0
 *
 * @internal
 */
class AdminHeader {

	/**
	 * Adds menu items to the admin page menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities:expages',
			'text' => elgg_echo('admin:configure_utilities:expages'),
			'href' => 'admin/configure_utilities/expages',
			'parent_name' => 'utilities',
		]);
	
		return $return;
	}
}
