<?php

namespace Elgg\Profile\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class UserHover {
	
	/**
	 * Register menu items for the user hover menu
	 *
	 * @param \Elgg\Event $event 'register' 'menu:user_hover'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
	
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$user = $event->getEntityParam();
		if (!$user instanceof \ElggUser || !$user->isEnabled() || !$user->canEdit()) {
			return;
		}
		
		$return = $event->getValue();
		
		$return->remove('avatar:edit');
		
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
