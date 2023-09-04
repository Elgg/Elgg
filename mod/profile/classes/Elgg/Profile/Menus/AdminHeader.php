<?php

namespace Elgg\Profile\Menus;

/**
 * Event callbacks for menus
 *
 * @since 5.0
 * @internal
 */
class AdminHeader {
	
	/**
	 * Register menu items for the topbar menu
	 *
	 * @param \Elgg\Event $event 'register' 'menu:admin_header'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
	
		$viewer = elgg_get_logged_in_user_entity();
		if (!$viewer instanceof \ElggUser) {
			 return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'profile',
			'href' => $viewer->getURL(),
			'text' => elgg_echo('profile'),
			'icon' => 'user',
			'parent_name' => 'account',
			'section' => 'alt',
			'priority' => 500,
		]);
		
		return $return;
	}
	
	/**
	 * Register menu items for the admin page menu
	 *
	 * @param \Elgg\Event $event 'register' 'menu:admin_header'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerAdminProfileFields(\Elgg\Event $event) {
		
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities:profile_fields',
			'text' => elgg_echo('admin:configure_utilities:profile_fields'),
			'href' => 'admin/configure_utilities/profile_fields',
			'parent_name' => 'utilities',
		]);
		
		return $return;
	}
}
