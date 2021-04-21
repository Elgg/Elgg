<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the filter menu
 *
 * @since 4.0
 * @internal
 */
class Filter {
	
	/**
	 * Add menu items to the filter menu on the admin upgrades page
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:admin/upgrades'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminUpgrades(\Elgg\Hook $hook) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'pending',
			'text' => elgg_echo('admin:upgrades:menu:pending'),
			'href' => 'admin/upgrades',
			'priority' => 100,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'completed',
			'text' => elgg_echo('admin:upgrades:menu:completed'),
			'href' => 'admin/upgrades/finished',
			'priority' => 200,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'db',
			'text' => elgg_echo('admin:upgrades:menu:db'),
			'href' => 'admin/upgrades/db',
			'priority' => 300,
		]);
		
		return $return;
	}
	
	/**
	 * Register the settings tab to the notification settings pages
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:settings/notifications'
	 *
	 * @return void|MenuItems
	 */
	public static function registerNotificationSettings(\Elgg\Hook $hook) {
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggUser || !$page_owner->canEdit()) {
			return;
		}
		
		/* @var $result MenuItems */
		$result = $hook->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'settings',
			'text' => elgg_echo('usersettings:notifications:menu:filter:settings'),
			'href' => elgg_generate_url('settings:notifications', [
				'username' => $page_owner->username,
			]),
			'priority' => 100,
		]);
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'users',
			'text' => elgg_echo('collection:user:user'),
			'href' => elgg_generate_url('settings:notifications:users', [
				'username' => $page_owner->username,
			]),
			'priority' => 200,
		]);
	}
}
