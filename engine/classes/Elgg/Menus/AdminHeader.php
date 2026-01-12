<?php

namespace Elgg\Menus;

use Elgg\Database\QueryBuilder;
use Elgg\Menu\MenuItems;
use Elgg\Menu\PreparedMenu;
use Elgg\Values;

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
		
		$online_users_count = max(1, elgg_count_entities([
			'type' => 'user',
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) {
					return $qb->compare("{$main_alias}.last_action", '>=', Values::normalizeTimestamp('-10 minutes'), ELGG_VALUE_TIMESTAMP);
				}
			],
		]));
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'online_users_count',
			'icon' => 'user',
			'text' => false,
			'title' => elgg_echo('admin:statistics:label:numonline'),
			'badge' => elgg_format_element('span', ['title' => $online_users_count], Values::shortFormatOutput($online_users_count)),
			'deps' => ['admin/users/online'],
			'href' => 'admin/users/online',
			'priority' => 10,
			'section' => 'alt',
		]);
		
		// link back to the site
		$return[] = \ElggMenuItem::factory([
			'name' => 'view_site',
			'icon' => 'home',
			'text' => elgg_echo('admin:view_site'),
			'href' => elgg_get_site_url(),
			'parent_name' => 'account',
			'priority' => 100,
			'section' => 'alt',
		]);
		
		// logout action
		$return[] = \ElggMenuItem::factory([
			'name' => 'admin_logout',
			'icon' => 'sign-out-alt',
			'text' => elgg_echo('logout'),
			'href' => elgg_generate_action_url('logout'),
			'parent_name' => 'account',
			'priority' => 1000,
			'section' => 'alt',
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
			'link_class' => ['elgg-avatar', 'elgg-avatar-small'],
			'section' => 'alt',
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
			'icon' => 'warning',
			'text' => elgg_echo('admin:configure_utilities:maintenance'),
			'href' => 'admin/configure_utilities/maintenance',
			'link_class' => 'elgg-maintenance-mode-warning',
			'priority' => 700,
		]);
		
		return $return;
	}
	
	/**
	 * Add the administer section to the admin header menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminAdminister(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'administer',
			'text' => elgg_echo('menu:page:header:administer'),
			'href' => false,
			'priority' => 10,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'dashboard',
			'text' => elgg_echo('admin:dashboard'),
			'href' => 'admin',
			'priority' => 10,
			'parent_name' => 'administer',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'plugins',
			'text' => elgg_echo('admin:plugins'),
			'href' => elgg_generate_url('admin', ['segments' => 'plugins']),
			'priority' => 30,
			'parent_name' => 'administer',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'users',
			'text' => elgg_echo('admin:users'),
			'href' => 'admin/users',
			'priority' => 40,
			'parent_name' => 'administer',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'upgrades',
			'text' => elgg_echo('admin:upgrades'),
			'href' => 'admin/upgrades',
			'priority' => 600,
			'parent_name' => 'administer',
		]);
				
		return $return;
	}
	
	/**
	 * Prepare the users menu item in the administer section on admin pages
	 *
	 * @param \Elgg\Event $event 'prepare', 'menu:admin_header'
	 *
	 * @return PreparedMenu|null
	 */
	public static function prepareAdminAdministerUsersChildren(\Elgg\Event $event): ?PreparedMenu {
		if (!elgg_is_admin_logged_in()) {
			return null;
		}
		
		/* @var $result PreparedMenu */
		$result = $event->getValue();
		
		$default = $result->getSection('default');
		
		/* @var $administer \ElggMenuItem */
		$administer = $default->get('administer');
		if (!$administer instanceof \ElggMenuItem || empty($administer->getChildren())) {
			return null;
		}
		
		/* @var $users \ElggMenuItem */
		$users = null;
		foreach ($administer->getChildren() as $child) {
			if ($child->getID() === 'users') {
				$users = $child;
				break;
			}
		}
		
		if (!$users instanceof \ElggMenuItem || empty($users->getChildren())) {
			return null;
		}
		
		$children = $users->getChildren();
		
		$selected = $users->getSelected();
		array_unshift($children, \ElggMenuItem::factory([
			'name' => 'users:all',
			'text' => elgg_echo('all'),
			'href' => 'admin/users',
			'parent_name' => 'users',
			'priority' => 1,
			'selected' => $selected,
		]));
		$users->setChildren($children);
		
		if ($selected) {
			$users->addItemClass('elgg-has-selected-child');
			$users->addItemClass('elgg-state-selected');
		}
		
		$users->setHref(false);
		
		return $result;
	}
	
	/**
	 * Add the configure section to the admin page menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminConfigure(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure',
			'text' => elgg_echo('menu:page:header:configure'),
			'href' => false,
			'priority' => 20,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'settings:basic',
			'text' => elgg_echo('admin:site_settings'),
			'href' => 'admin/site_settings',
			'priority' => 10,
			'parent_name' => 'configure',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'settings:icons',
			'text' => elgg_echo('admin:site_icons'),
			'href' => 'admin/site_icons',
			'priority' => 20,
			'parent_name' => 'configure',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'settings:theme',
			'text' => elgg_echo('admin:theme'),
			'href' => 'admin/theme',
			'priority' => 25,
			'parent_name' => 'configure',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'security',
			'text' => elgg_echo('admin:security'),
			'href' => 'admin/security',
			'priority' => 30,
			'parent_name' => 'configure',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities:maintenance',
			'text' => elgg_echo('admin:configure_utilities:maintenance'),
			'href' => 'admin/configure_utilities/maintenance',
			'priority' => 40,
			'parent_name' => 'configure',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities:robots',
			'text' => elgg_echo('admin:configure_utilities:robots'),
			'href' => 'admin/configure_utilities/robots',
			'priority' => 50,
			'parent_name' => 'configure',
		]);
						
		return $return;
	}
	
	/**
	 * Add the utilities section to the admin page menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminUtilities(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'utilities',
			'text' => elgg_echo('menu:page:header:utilities'),
			'href' => false,
			'priority' => 30,
		]);

		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities:menu_items',
			'text' => elgg_echo('admin:configure_utilities:menu_items'),
			'href' => 'admin/configure_utilities/menu_items',
			'parent_name' => 'utilities',
		]);
		
		return $return;
	}
	
	/**
	 * Register menu items for default widgets
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminDefaultWidgets(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		if (empty(elgg_trigger_event_results('get_list', 'default_widgets', [], []))) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'default_widgets',
			'text' => elgg_echo('admin:configure_utilities:default_widgets'),
			'href' => 'admin/configure_utilities/default_widgets',
			'parent_name' => 'utilities',
		]);
		
		return $return;
	}
	
	/**
	 * Add the information section to the admin page menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminInformation(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'information',
			'text' => elgg_echo('menu:page:header:information'),
			'href' => false,
			'priority' => 40,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'server',
			'text' => elgg_echo('admin:server'),
			'href' => 'admin/server',
			'parent_name' => 'information',
			'priority' => 50,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'information:security',
			'text' => elgg_echo('admin:security'),
			'href' => 'admin/security/information',
			'parent_name' => 'information',
			'priority' => 60,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'information:performance',
			'text' => elgg_echo('admin:performance'),
			'href' => 'admin/performance',
			'parent_name' => 'information',
			'priority' => 70,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'statistics',
			'text' => elgg_echo('admin:statistics'),
			'href' => 'admin/statistics',
			'parent_name' => 'information',
			'priority' => 80,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'cron',
			'text' => elgg_echo('admin:cron'),
			'href' => 'admin/cron',
			'parent_name' => 'information',
			'priority' => 90,
		]);
		
		return $return;
	}
}
