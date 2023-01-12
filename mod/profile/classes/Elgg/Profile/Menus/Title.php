<?php

namespace Elgg\Profile\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Title {
	
	/**
	 * Register menu items for the title menu
	 *
	 * @param \Elgg\Event $event 'register' 'menu:title'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
	
		$user = $event->getEntityParam();
		if (!$user instanceof \ElggUser || !$user->canEdit() || !elgg_in_context('profile')) {
			return;
		}
		
		$return = $event->getValue();
		
		$return->remove('avatar:edit');
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit_profile',
			'href' => elgg_generate_entity_url($user, 'edit'),
			'text' => elgg_echo('profile:edit'),
			'icon' => 'address-card',
			'class' => ['elgg-button', 'elgg-button-action'],
		]);
		
		return $return;
	}
}
