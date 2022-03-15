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
		if (!$user instanceof \ElggUser || !$user->isEnabled() || !elgg_is_admin_logged_in()) {
			return;
		}
	
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'logbrowser',
			'icon' => 'search',
			'text' => elgg_echo('logbrowser:explore'),
			'href' => elgg_http_add_url_query_elements('admin/administer_utilities/logbrowser', [
				'user_guid' => $user->guid,
			]),
			'section' => 'admin',
		]);
	
		return $return;
	}
}
