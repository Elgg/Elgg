<?php

namespace Elgg\Friends\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Title {

	/**
	 * Adds friending to profile title menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:title'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		$user = $hook->getEntityParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		/* @var $return \Elgg\Menu\MenuItems */
		$return = $hook->getValue();
		
		$menu_items = _elgg_friends_get_add_friend_menu_items($user, true);
		
		$return->merge($menu_items);
		
		return $return;
	}
}
