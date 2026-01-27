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
			return null;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities:external_pages',
			'text' => elgg_echo('collection:object:external_page'),
			'href' => 'admin/configure_utilities/external_pages',
			'parent_name' => 'utilities',
		]);
	
		return $return;
	}
}
