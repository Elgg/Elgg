<?php

namespace Elgg\Groups\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Topbar {
	
	/**
	 * Registers optional group invites menu item to topbar
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:topbar'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
	
		$user = elgg_get_logged_in_user_entity();
		if (empty($user)) {
			return;
		}
		
		$count = groups_get_invited_groups($user->guid, false, ['count' => true]);
		if (empty($count)) {
			return;
		}
		
		$result = $hook->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'groups:user:invites',
			'text' => elgg_echo('groups:invitations'),
			'badge' => $count,
			'title' => elgg_echo('groups:invitations:pending', [$count]),
			'icon' => 'users',
			'parent_name' => 'account',
			'section' => 'alt',
			'href' => elgg_generate_url('collection:group:group:invitations', [
				'username' => $user->username,
			]),
		]);
		
		return $result;
	}
}
