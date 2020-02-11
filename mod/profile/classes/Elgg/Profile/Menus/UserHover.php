<?php

namespace Elgg\Profile\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class UserHover {
	
	/**
	 * Register menu items for the user hover menu
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:user_hover'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
	
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$user = $hook->getEntityParam();
		if (!($user instanceof \ElggUser) || !$user->canEdit()) {
			return;
		}
		
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'profile:edit',
			'text' => elgg_echo('profile:edit'),
			'icon' => 'address-card',
			'href' => elgg_generate_entity_url($user, 'edit'),
			'section' => (elgg_get_logged_in_user_guid() == $user->guid) ? 'action' : 'admin',
		]);
		
		return $return;
	}
}
