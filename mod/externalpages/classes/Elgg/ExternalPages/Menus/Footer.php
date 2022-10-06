<?php

namespace Elgg\ExternalPages\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Footer {

	/**
	 * Adds menu items to the footer menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:footer'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$return = $event->getValue();
		
		$pages = ['about', 'terms', 'privacy'];
		foreach ($pages as $page) {
			$return[] = \ElggMenuItem::factory([
				'name' => $page,
				'text' => elgg_echo("expages:{$page}"),
				'href' => $page,
				'section' => 'meta',
			]);
		}

		return $return;
	}
}
