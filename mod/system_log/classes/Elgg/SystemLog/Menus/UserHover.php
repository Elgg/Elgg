<?php

namespace Elgg\SystemLog\Menus;

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
		if (!$user instanceof \ElggUser) {
			return;
		}
	
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'logbrowser',
			'href' => "admin/administer_utilities/logbrowser?user_guid={$user->guid}",
			'text' => elgg_echo('logbrowser:explore'),
			'icon' => 'search',
			'section' => 'admin',
		]);
	
		return $return;
	}
}
