<?php

namespace Elgg\Bookmarks\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Footer {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:footer'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'bookmark',
			'text' => elgg_echo('bookmarks:this'),
			'icon' => 'thumbtack',
			'href' => elgg_generate_url('add:object:bookmarks', [
				'guid' => elgg_get_logged_in_user_guid(),
				'address' => current_page_url(),
			]),
			'title' => elgg_echo('bookmarks:this'),
			'rel' => 'nofollow',
		]);
		
		return $return;
	}
}
