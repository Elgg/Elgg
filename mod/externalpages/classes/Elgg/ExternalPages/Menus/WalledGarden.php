<?php

namespace Elgg\ExternalPages\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class WalledGarden {

	/**
	 * Adds menu items to the walled garden menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:walled_garden'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		
		$pages = ['about', 'terms', 'privacy'];
		foreach ($pages as $page) {
			$return[] = \ElggMenuItem::factory([
				'name' => $page,
				'text' => elgg_echo("expages:{$page}"),
				'href' => $page,
			]);
		}

		return $return;
	}
}
