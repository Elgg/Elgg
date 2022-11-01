<?php

namespace Elgg\ExternalPages\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Page {

	/**
	 * Adds menu items to the admin page menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:page'
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
			'section' => 'configure',
			'parent_name' => 'configure_utilities',
		]);
	
		return $return;
	}
}
