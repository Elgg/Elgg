<?php

namespace Elgg\Bin\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Topbar {
	
	/**
	 * Register menu items for the topbar menu
	 *
	 * @param \Elgg\Event $event 'register' 'menu:topbar'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
	
		$viewer = elgg_get_logged_in_user_entity();
		if (!$viewer instanceof \ElggUser) {
			 return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'bin',
			'href' => elgg_generate_url('default:bin'),
			'text' => elgg_echo('item:object:bin'),
			'icon' => 'settings',
			'parent_name' => 'account',
			'section' => 'alt',
			'priority' => 100,
		]);
		
		return $return;
	}
}
