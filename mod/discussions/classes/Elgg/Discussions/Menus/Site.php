<?php

namespace Elgg\Discussions\Menus;

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
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_get_plugin_setting('enable_global_discussions', 'discussions')) {
			return;
		}
		
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'discussions',
			'text' => elgg_echo('collection:object:discussion'),
			'href' => elgg_generate_url('default:object:discussion'),
		]);
	
		return $return;
	}
}
