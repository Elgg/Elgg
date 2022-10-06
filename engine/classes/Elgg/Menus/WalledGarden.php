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
	 * @param \Elgg\Event $event 'register', 'menu:walled_garden'
	 *
	 * @return void|MenuItems
	 */
	public static function registerHome(\Elgg\Event $event) {
		if (elgg_get_current_url() === elgg_get_site_url()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'home',
			'text' => elgg_echo('walled_garden:home'),
			'href' => elgg_get_site_url(),
			'priority' => 10,
		]);
		
		return $return;
	}
}
