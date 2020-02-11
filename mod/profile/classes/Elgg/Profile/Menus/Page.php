<?php

namespace Elgg\Profile\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Page {
	
	/**
	 * Register menu items for the admin page menu
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerAdminProfileFields(\Elgg\Hook $hook) {
	
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities:profile_fields',
			'text' => elgg_echo('admin:configure_utilities:profile_fields'),
			'href' => 'admin/configure_utilities/profile_fields',
			'section' => 'configure',
			'parent_name' => 'configure_utilities',
		]);
		
		return $return;
	}
	
	/**
	 * Register menu items for the page menu
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerProfileEdit(\Elgg\Hook $hook) {
	
		$owner = elgg_get_page_owner_entity();
		if (!$owner instanceof \ElggUser) {
			return;
		}
		
		$return = $hook->getValue();
		
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
