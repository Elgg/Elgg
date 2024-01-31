<?php

namespace Elgg\Bookmarks\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Footer {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:footer'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'bookmark',
			'text' => elgg_echo('bookmarks:this'),
			'icon' => 'thumbtack',
			'href' => elgg_generate_url('add:object:bookmarks', [
				'guid' => elgg_get_logged_in_user_guid(),
				'address' => elgg_get_current_url(),
			]),
			'title' => elgg_echo('bookmarks:this'),
			'rel' => 'nofollow',
			'deps' => ['bookmarks/bookmarks'],
		]);
		
		return $return;
	}
}
