<?php

namespace Elgg\Bookmarks\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Page {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_is_logged_in()) {
			return;
		}
		// only show bookmarklet on bookmark pages
		if (!elgg_in_context('bookmarks')) {
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity() ?: elgg_get_logged_in_user_entity();
				
		if ($page_owner instanceof \ElggGroup) {
			$title = elgg_echo('bookmarks:bookmarklet:group');
		} else {
			$title = elgg_echo('bookmarks:bookmarklet');
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'bookmarklet',
			'text' => $title,
			'href' => elgg_generate_url('bookmarklet:object:bookmarks', ['guid' => $page_owner->guid]),
		]);
	
		return $return;
	}
}
