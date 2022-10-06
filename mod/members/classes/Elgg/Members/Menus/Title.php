<?php

namespace Elgg\Members\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Title {
	
	/**
	 * Add create user admin menu item
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'add_user',
			'icon' => 'user-plus',
			'text' => elgg_echo('admin:users:add'),
			'href' => 'admin/users/add',
			'context' => 'members',
			'link_class' => 'elgg-button elgg-button-action',
		]);
		
		return $return;
	}
}
