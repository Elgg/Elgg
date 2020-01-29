<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the walled_garden menu
 *
 * @since 4.0
 * @internal
 */
class WalledGarden {

	/**
	 * Adds home link to walled garden menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:walled_garden'
	 *
	 * @return void|MenuItems
	 */
	public static function registerHome(\Elgg\Hook $hook) {
		if (current_page_url() === elgg_get_site_url()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'home',
			'text' => elgg_echo('walled_garden:home'),
			'href' => elgg_get_site_url(),
			'priority' => 10,
		]);
		
		return $return;
	}
}
