<?php

namespace Elgg\Friends\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Topbar {

	/**
	 * Register menu items for the topbar menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:topbar'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
	
		$viewer = elgg_get_logged_in_user_entity();
		if (!$viewer) {
			return;
		}
		
		$badge = null;
		if ((bool) elgg_get_plugin_setting('friend_request', 'friends')) {
			$count = elgg_get_relationships([
				'type' => 'user',
				'relationship_guid' => $viewer,
				'relationship' => 'friendrequest',
				'inverse_relationship' => true,
				'count' => true,
			]);
			if ($count > 0) {
				$badge = $count;
			}
		}
		
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'friends',
			'icon' => 'users',
			'text' => elgg_echo('friends'),
			'href' => elgg_generate_url('collection:friends:owner', [
				'username' => $viewer->username,
			]),
			'badge' => $badge,
			'title' => elgg_echo('friends'),
			'priority' => 300,
			'section' => 'alt',
			'parent_name' => 'account',
		]);
		
		return $return;
	}
}
