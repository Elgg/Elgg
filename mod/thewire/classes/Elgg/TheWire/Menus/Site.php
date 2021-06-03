<?php

namespace Elgg\TheWire\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Site {

	/**
	 * Register menu item
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'thewire',
			'icon' => 'comments-regular',
			'text' => elgg_echo('thewire'),
			'href' => elgg_generate_url('default:object:thewire'),
		]);
		
		return $return;
	}
}
