<?php

namespace Elgg\Profile\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Topbar {
	
	/**
	 * Register menu items for the topbar menu
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:topbar'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
	
		$viewer = elgg_get_logged_in_user_entity();
		if (!$viewer) {
			 return;
		}
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'profile',
			'href' => $viewer->getURL(),
			'text' => elgg_echo('profile'),
			'icon' => 'user',
			'parent_name' => 'account',
			'section' => 'alt',
			'priority' => 100,
		]);
		
		return $return;
	}
}
