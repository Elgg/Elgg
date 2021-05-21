<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;
use Elgg\Router\Route;

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
	
	/**
	 * Register the default All and Mine filter menu items
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:filter'
	 *
	 * @return MenuItems
	 */
	public static function registerFilterTabs(\Elgg\Hook $hook): MenuItems {
		/* @var $result MenuItems */
		$result = $hook->getValue();
		
		$entity_type = $hook->getParam('entity_type', '');
		$entity_subtype = $hook->getParam('entity_subtype', '');
		if (empty($entity_type) || empty($entity_subtype)) {
			$route = elgg_get_current_route();
			if ($route instanceof Route) {
				// assume route name as '<purpose>:<entity type>:<entity subtype>:<sub>'
				// eg collection:object:blog:owner or view:group:group
				// @see http://learn.elgg.org/en/stable/guides/routing.html#routes-names
				$route_parts = explode(':', $route->getName());
				$entity_type = elgg_extract(1, $route_parts, '');
				$entity_subtype = elgg_extract(2, $route_parts, '');
			}
		}
		
		$all_link = $hook->getParam('all_link');
		if (empty($all_link)) {
			if (elgg_route_exists("collection:{$entity_type}:{$entity_subtype}:all")) {
				$all_link = elgg_generate_url("collection:{$entity_type}:{$entity_subtype}:all");
			} elseif (elgg_route_exists("collection:{$entity_type}:all")) {
				$all_link = elgg_generate_url("collection:{$entity_type}:all");
			}
		}
		
		if (!empty($all_link)) {
			$result[] = \ElggMenuItem::factory([
				'name' => 'all',
				'text' => elgg_echo('all'),
				'href' => $all_link,
				'priority' => 200,
			]);
		}
		
		$user = elgg_get_logged_in_user_entity();
		if ($user instanceof \ElggUser) {
			$mine_link = $hook->getParam('mine_link');
			if (empty($mine_link)) {
				if (elgg_route_exists("collection:{$entity_type}:{$entity_subtype}:owner")) {
					$mine_link = elgg_generate_url("collection:{$entity_type}:{$entity_subtype}:owner", [
						'username' => $user->username,
					]);
				} elseif (elgg_route_exists("collection:{$entity_type}:owner")) {
					$mine_link = elgg_generate_url("collection:{$entity_type}:owner", [
						'username' => $user->username,
					]);
				}
			}
			
			if (!empty($mine_link)) {
				$result[] = \ElggMenuItem::factory([
					'name' => 'mine',
					'text' => elgg_echo('mine'),
					'href' => $mine_link,
					'priority' => 300,
				]);
			}
		}
		
		return $result;
	}
}
