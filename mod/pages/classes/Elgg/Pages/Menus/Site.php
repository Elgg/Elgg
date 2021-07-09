<?php

namespace Elgg\Pages\Menus;

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
			'name' => 'pages',
			'icon' => 'file-alt-regular',
			'text' => elgg_echo('collection:object:page'),
			'href' => elgg_generate_url('default:object:page'),
		]);
	
		return $return;
	}
}
