<?php

namespace Elgg\Friends\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Page {

	/**
	 * Register menu items for the friends page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
	
		$owner = elgg_get_page_owner_entity();
		if (!$owner instanceof \ElggUser) {
			return;
		}
	
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'friends',
			'text' => elgg_echo('friends'),
			'href' => elgg_generate_url('collection:friends:owner', [
				'username' => $owner->username,
			]),
			'contexts' => ['friends'],
		]);
	
		if (!(bool) elgg_get_plugin_setting('friend_request', 'friends')) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'friends:of',
				'text' => elgg_echo('friends:of'),
				'href' => elgg_generate_url('collection:friends_of:owner', [
					'username' => $owner->username,
				]),
				'contexts' => ['friends'],
			]);
		}
	
		return $return;
	}
}
