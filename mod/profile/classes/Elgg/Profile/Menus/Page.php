<?php

namespace Elgg\Profile\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Page {
	
	/**
	 * Register menu items for the page menu
	 *
	 * @param \Elgg\Event $event 'register' 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerProfileEdit(\Elgg\Event $event) {
	
		$owner = elgg_get_page_owner_entity();
		if (!$owner instanceof \ElggUser) {
			return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit_profile',
			'href' => elgg_generate_entity_url($owner, 'edit'),
			'text' => elgg_echo('profile:edit'),
			'section' => '1_profile',
			'contexts' => ['settings'],
		]);
		
		return $return;
	}
}
