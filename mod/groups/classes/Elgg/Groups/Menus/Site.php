<?php

namespace Elgg\Groups\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Site {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:site'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'groups',
			'icon' => 'users',
			'text' => elgg_echo('groups'),
			'href' => elgg_generate_url('default:group:group'),
		]);
		
		return $return;
	}
}
