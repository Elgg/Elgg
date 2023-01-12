<?php

namespace Elgg\Profile\Menus;

/**
 * Event callbacks for menus
 *
 * @since 5.0
 */
class Filter {
	
	/**
	 * Register menu items for the filter menu
	 *
	 * @param \Elgg\Event $event 'register' 'menu:filter:profile/edit'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerProfileEdit(\Elgg\Event $event) {
	
		$user = elgg_get_page_owner_entity();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit_profile',
			'href' => elgg_generate_entity_url($user, 'edit'),
			'text' => elgg_echo('profile:edit'),
			'priority' => 50,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit_profile_header',
			'href' => elgg_generate_entity_url($user, 'edit', 'header'),
			'text' => elgg_echo('profile:edit:header'),
			'priority' => 60,
		]);
		
		return $return;
	}
}
