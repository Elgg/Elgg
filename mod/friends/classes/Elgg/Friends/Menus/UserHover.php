<?php

namespace Elgg\Friends\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class UserHover {

	/**
	 * Adds friending to user hover menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:user_hover'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		
		$user = $event->getEntityParam();
		if (!$user instanceof \ElggUser || !$user->isEnabled()) {
			return;
		}
		
		/* @var $return \Elgg\Menu\MenuItems */
		$return = $event->getValue();
		
		$menu_items = _elgg_friends_get_add_friend_menu_items($user);
		
		$return->merge($menu_items);
		
		return $return;
	}
}
