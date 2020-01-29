<?php

namespace Elgg\Profile\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Title {
	
	/**
	 * Register menu items for the title menu
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:title'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
	
		$user = $hook->getEntityParam();
		if (!($user instanceof \ElggUser) || !$user->canEdit()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit_profile',
			'href' => elgg_generate_entity_url($user, 'edit'),
			'text' => elgg_echo('profile:edit'),
			'icon' => 'address-card',
			'class' => ['elgg-button', 'elgg-button-action'],
			'contexts' => ['profile', 'profile_edit'],
		]);
		
		return $return;
	}
}
