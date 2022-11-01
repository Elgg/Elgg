<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items for the admin_header menu
 *
 * @since 4.0
 * @internal
 */
class AdminHeader {
	
	/**
	 * Add the default menu items
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return void|MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$admin = elgg_get_logged_in_user_entity();
		
		// link back to the site
		$return[] = \ElggMenuItem::factory([
			'name' => 'view_site',
			'icon' => 'home',
			'text' => elgg_echo('admin:view_site'),
			'href' => elgg_get_site_url(),
			'parent_name' => 'account',
			'priority' => 100,
		]);
		
		// logout action
		$return[] = \ElggMenuItem::factory([
			'name' => 'admin_logout',
			'icon' => 'sign-out-alt',
			'text' => elgg_echo('logout'),
			'href' => elgg_generate_action_url('logout'),
			'parent_name' => 'account',
			'priority' => 1000,
		]);
		
		// link to admin profile
		$return[] = \ElggMenuItem::factory([
			'name' => 'account',
			'text' => elgg_echo('account'),
			'href' => false,
			'icon' => elgg_view('output/img', [
				'src' => $admin->getIconURL('small'),
				'alt' => $admin->getDisplayName(),
			]),
			'link_class' => 'elgg-avatar-small',
			'icon_alt' => 'angle-down',
		]);
		
		return $return;
	}
	
	/**
	 * Add the maintenance link
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return void|MenuItems
	 */
	public static function registerMaintenance(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		if (!elgg_get_config('elgg_maintenance_mode')) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'maintenance',
			'text' => elgg_echo('admin:configure_utilities:maintenance'),
			'href' => 'admin/configure_utilities/maintenance',
			'link_class' => 'elgg-maintenance-mode-warning',
			'priority' => 700,
		]);
		
		return $return;
	}
}
