<?php

namespace Elgg\InviteFriends\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Page {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$result = $event->getValue();
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
