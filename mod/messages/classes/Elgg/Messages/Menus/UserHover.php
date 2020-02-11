<?php

namespace Elgg\Messages\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class UserHover {
	
	/**
	 * Add to the user hover menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:user_hover'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		$user = $hook->getEntityParam();
		if (!elgg_is_logged_in() || !$user instanceof \ElggUser) {
			return;
		}
		
		if (elgg_get_logged_in_user_guid() === $user->guid) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'send',
			'text' => elgg_echo('messages:sendmessage'),
			'icon' => 'mail',
			'href' => elgg_generate_url('add:object:messages', [
				'send_to' => $user->guid,
			]),
			'section' => 'action',
		]);
	
		return $return;
	}
}
