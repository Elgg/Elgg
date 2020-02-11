<?php

namespace Elgg\Groups\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Site {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'groups',
			'icon' => 'users',
			'text' => elgg_echo('groups'),
			'href' => elgg_generate_url('default:group:group'),
		]);
		
		return $return;
	}
}
