<?php

namespace Elgg\File\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
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
			'name' => 'file',
			'icon' => 'copy-regular',
			'text' => elgg_echo('collection:object:file'),
			'href' => elgg_generate_url('default:object:file'),
		]);
		
		return $return;
	}
}
