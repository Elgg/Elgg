<?php

namespace Elgg\InviteFriends\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
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
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		if (!elgg_get_config('allow_registration')) {
			return;
		}
		
		$result = $hook->getValue();
		$result[] = \ElggMenuItem::factory([
			'name' => 'invite',
			'text' => elgg_echo('friends:invite'),
			'href' => elgg_generate_url('default:user:user:invite', [
				'username' => $user->username,
			]),
			'contexts' => ['friends'],
		]);
		
		return $result;
	}
}
