<?php

namespace Elgg\SystemLog\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Page {
	
	/**
	 * Add to the page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_is_admin_logged_in() || !elgg_in_context('admin')) {
			return;
		}
	
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'administer_utilities:logbrowser',
			'text' => elgg_echo('admin:administer_utilities:logbrowser'),
			'href' => 'admin/administer_utilities/logbrowser',
			'section' => 'administer',
			'parent_name' => 'administer_utilities',
		]);
	
		return $return;
	}
}
