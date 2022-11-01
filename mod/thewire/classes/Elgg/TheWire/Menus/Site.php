<?php

namespace Elgg\TheWire\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Site {

	/**
	 * Register menu item
	 *
	 * @param \Elgg\Event $event 'register', 'menu:site'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'thewire',
			'icon' => 'comments-regular',
			'text' => elgg_echo('thewire'),
			'href' => elgg_generate_url('default:object:thewire'),
		]);
		
		return $return;
	}
}
