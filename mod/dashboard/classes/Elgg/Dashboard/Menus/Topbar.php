<?php

namespace Elgg\Dashboard\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Topbar {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:topbar'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'dashboard',
			'href' => elgg_generate_url('default:dashboard'),
			'text' => elgg_echo('dashboard'),
			'icon' => 'th-large',
			'priority' => 100,
			'section' => 'alt',
			'parent_name' => 'account',
		]);
	
		return $return;
	}
}
