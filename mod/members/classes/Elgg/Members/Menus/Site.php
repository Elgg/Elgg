<?php

namespace Elgg\Members\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Site {

	/**
	 * Add members menu item
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'members',
			'icon' => 'address-book-regular',
			'text' => elgg_echo('members'),
			'href' => elgg_generate_url('collection:user:user'),
		]);
		
		return $return;
	}
}
