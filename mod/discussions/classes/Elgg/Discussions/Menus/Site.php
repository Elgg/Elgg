<?php

namespace Elgg\Discussions\Menus;

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
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'discussions',
			'icon' => 'comment-dots-regular',
			'text' => elgg_echo('collection:object:discussion'),
			'href' => elgg_generate_url('default:object:discussion'),
		]);
	
		return $return;
	}
}
