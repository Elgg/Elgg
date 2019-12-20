<?php

namespace Elgg\Discussions;

/**
 * Menu functions
 */
class Menus {
	
	/**
	 * Adds discussions menu item to site menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 *
	 * @since 3.3
	 */
	public static function registerSiteMenuItem(\Elgg\Hook $hook) {
		
		if (!elgg_get_plugin_setting('enable_global_discussions', 'discussions')) {
			return;
		}
		
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'discussions',
			'text' => elgg_echo('collection:object:discussion'),
			'href' => elgg_generate_url('collection:object:discussion:all'),
		]);
	
		return $return;
	}
}
