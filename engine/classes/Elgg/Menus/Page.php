<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;
use Elgg\Menu\PreparedMenu;

/**
 * Register menu items for the page menu
 *
 * @since 4.0
 * @internal
 */
class Page {
	
	/**
	 * Add the administer section to the admin page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminAdminister(\Elgg\Hook $hook) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'dashboard',
			'text' => elgg_echo('admin:dashboard'),
			'href' => 'admin',
			'priority' => 10,
			'section' => 'administer',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'plugins',
			'text' => elgg_echo('admin:plugins'),
			'href' => 'admin/plugins',
			'priority' => 30,
			'section' => 'administer',
		]);
		
		// Users
		$return[] = \ElggMenuItem::factory([
			'name' => 'users',
			'text' => elgg_echo('admin:users'),
			'href' => false,
			'priority' => 40,
			'section' => 'administer',
		]);
		$return[] = \ElggMenuItem::factory([
			'name' => 'users:online',
			'text' => elgg_echo('admin:users:online'),
			'href' => 'admin/users/online',
			'priority' => 10,
			'section' => 'administer',
			'parent_name' => 'users',
		]);
		$return[] = \ElggMenuItem::factory([
			'name' => 'users:admins',
			'text' => elgg_echo('admin:users:admins'),
			'href' => 'admin/users/admins',
			'priority' => 20,
			'section' => 'administer',
			'parent_name' => 'users',
		]);
		$return[] = \ElggMenuItem::factory([
			'name' => 'users:newest',
			'text' => elgg_echo('admin:users:newest'),
			'href' => 'admin/users/newest',
			'priority' => 30,
			'section' => 'administer',
			'parent_name' => 'users',
		]);
		$return[] = \ElggMenuItem::factory([
			'name' => 'users:add',
			'text' => elgg_echo('admin:users:add'),
			'href' => 'admin/users/add',
			'priority' => 40,
			'section' => 'administer',
			'parent_name' => 'users',
		]);
		$return[] = \ElggMenuItem::factory([
			'name' => 'users:unvalidated',
			'text' => elgg_echo('admin:users:unvalidated'),
			'href' => 'admin/users/unvalidated',
			'priority' => 50,
			'section' => 'administer',
			'parent_name' => 'users',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'upgrades',
			'text' => elgg_echo('admin:upgrades'),
			'href' => 'admin/upgrades',
			'priority' => 600,
			'section' => 'administer',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'administer_utilities',
			'text' => elgg_echo('admin:administer_utilities'),
			'href' => false,
			'priority' => 50,
			'section' => 'administer',
		]);
		
		return $return;
	}
	
	/**
	 * Add the configure section to the admin page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminConfigure(\Elgg\Hook $hook) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'settings:basic',
			'text' => elgg_echo('admin:site_settings'),
			'href' => 'admin/site_settings',
			'priority' => 10,
			'section' => 'configure',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'security',
			'text' => elgg_echo('admin:security'),
			'href' => 'admin/security',
			'priority' => 30,
			'section' => 'configure',
		]);
		
		// Utilities
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities',
			'text' => elgg_echo('admin:configure_utilities'),
			'href' => false,
			'priority' => 600,
			'section' => 'configure',
		]);
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities:maintenance',
			'text' => elgg_echo('admin:configure_utilities:maintenance'),
			'href' => 'admin/configure_utilities/maintenance',
			'section' => 'configure',
			'parent_name' => 'configure_utilities',
		]);
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities:menu_items',
			'text' => elgg_echo('admin:configure_utilities:menu_items'),
			'href' => 'admin/configure_utilities/menu_items',
			'section' => 'configure',
			'parent_name' => 'configure_utilities',
		]);
		$return[] = \ElggMenuItem::factory([
			'name' => 'configure_utilities:robots',
			'text' => elgg_echo('admin:configure_utilities:robots'),
			'href' => 'admin/configure_utilities/robots',
			'section' => 'configure',
			'parent_name' => 'configure_utilities',
		]);
		
		return $return;
	}
	
	/**
	 * Register menu items for default widgets
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminDefaultWidgets(\Elgg\Hook $hook) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		if (empty(elgg_trigger_plugin_hook('get_list', 'default_widgets', null, []))) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'default_widgets',
			'text' => elgg_echo('admin:configure_utilities:default_widgets'),
			'href' => 'admin/configure_utilities/default_widgets',
			'section' => 'configure',
			'parent_name' => 'configure_utilities',
		]);

		return $return;
	}
	
	/**
	 * Add the information section to the admin page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminInformation(\Elgg\Hook $hook) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'server',
			'text' => elgg_echo('admin:server'),
			'href' => 'admin/server',
			'section' => 'information',
			'priority' => 50,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'information:security',
			'text' => elgg_echo('admin:security'),
			'href' => 'admin/security/information',
			'section' => 'information',
			'priority' => 60,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'information:performance',
			'text' => elgg_echo('admin:performance'),
			'href' => 'admin/performance',
			'section' => 'information',
			'priority' => 70,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'statistics',
			'text' => elgg_echo('admin:statistics'),
			'href' => 'admin/statistics',
			'section' => 'information',
			'priority' => 80,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'cron',
			'text' => elgg_echo('admin:cron'),
			'href' => 'admin/cron',
			'section' => 'information',
			'priority' => 90,
		]);
		
		return $return;
	}
	
	/**
	 * Register links to the plugin settings
	 *
	 * @note Plugin settings are alphabetically sorted in the submenu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminPluginSettings(\Elgg\Hook $hook) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		// plugin settings
		$active_plugins = elgg_get_plugins('active');
		if (empty($active_plugins)) {
			// nothing added because no items
			return;
		}
		
		$plugins_with_settings = [];
		
		foreach ($active_plugins as $plugin) {
			$plugin_id = $plugin->getID();
			
			if (!elgg_view_exists("plugins/{$plugin_id}/settings") ) {
				continue;
			}
			$plugin_name = $plugin->getDisplayName();
			$plugins_with_settings[$plugin_name] = [
				'name' => "plugin:settings:{$plugin_id}",
				'href' => elgg_generate_url('admin:plugin_settings', [
					'plugin_id' => $plugin_id,
				]),
				'text' => $plugin_name,
				'parent_name' => 'plugin_settings',
				'section' => 'configure',
			];
		}
		
		if (empty($plugins_with_settings)) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'plugin_settings',
			'text' => elgg_echo('admin:plugin_settings'),
			'href' => false,
			'section' => 'configure',
		]);
		
		ksort($plugins_with_settings);
		$priority = 0;
		foreach ($plugins_with_settings as $plugin_item) {
			$priority += 10;
			$plugin_item['priority'] = $priority;
			$return[] = \ElggMenuItem::factory($plugin_item);
		}
		
		return $return;
	}
	
	/**
	 * Register menu items for the user settings
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerUserSettings(\Elgg\Hook $hook) {
		$user = elgg_get_page_owner_entity();
		if (!$user instanceof \ElggUser || !elgg_in_context('settings') || !$user->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => '1_account',
			'text' => elgg_echo('usersettings:user:opt:linktext'),
			'href' => elgg_generate_url('settings:account', [
				'username' => $user->username,
			]),
			'section' => 'configure',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => '1_notifications',
			'text' => elgg_echo('usersettings:notifications:menu:page'),
			'href' => elgg_generate_url('settings:notifications', [
				'username' => $user->username,
			]),
			'section' => 'configure',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => '1_statistics',
			'text' => elgg_echo('usersettings:statistics:opt:linktext'),
			'href' => elgg_generate_url('settings:statistics', [
				'username' => $user->username,
			]),
			'section' => 'configure',
		]);
		
		return $return;
	}
	
	/**
	 * Register menu items for the user plugin settings
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerUserSettingsPlugins(\Elgg\Hook $hook) {
		$user = elgg_get_page_owner_entity();
		if (!$user instanceof \ElggUser || !elgg_in_context('settings') || !$user->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => '1_plugins',
			'text' => elgg_echo('usersettings:plugins:opt:linktext'),
			'href' => false,
			'section' => 'configure',
		]);
		
		$active_plugins = elgg_get_plugins();
		foreach ($active_plugins as $plugin) {
			$plugin_id = $plugin->getID();
			if (!elgg_view_exists("plugins/{$plugin_id}/usersettings")) {
				continue;
			}
			
			$title = $plugin->getDisplayName();
			if (elgg_language_key_exists("{$plugin_id}:usersettings:title")) {
				$title = elgg_echo("{$plugin_id}:usersettings:title");
			}
			
			$return[] = \ElggMenuItem::factory([
				'name' => $plugin_id,
				'text' => $title,
				'href' => elgg_generate_url('settings:tools', [
					'username' => $user->username,
					'plugin_id' => $plugin_id,
				]),
				'parent_name' => '1_plugins',
				'section' => 'configure',
			]);
		}
		
		return $return;
	}
	
	/**
	 * Remove the plugins menu item on the user settings page if no children
	 *
	 * Items can be removed by plugins, which would leave an empty menu item
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'menu:page'
	 *
	 * @return void|PreparedMenu
	 */
	public static function cleanupUserSettingsPlugins(\Elgg\Hook $hook) {
		/* @var $return PreparedMenu */
		$return = $hook->getValue();
		if (!$return->count() || !elgg_in_context('settings')) {
			return;
		}
		
		$configure = $return->getSection('configure');
		if (!$configure->count()) {
			return;
		}
		
		$plugins = $configure->get('1_plugins');
		if (!$plugins instanceof \ElggMenuItem || $plugins->getChildren()) {
			return;
		}
		
		$configure->remove('1_plugins');
		
		return $return;
	}
	
	/**
	 * Add a link to the avatar edit page
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAvatarEdit(\Elgg\Hook $hook) {
		$user = $hook->getParam('entity', elgg_get_page_owner_entity());
		if (!$user instanceof \ElggUser || !$user->canEdit() || !elgg_in_context('settings')) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit_avatar',
			'text' => elgg_echo('avatar:edit'),
			'href' => elgg_generate_entity_url($user, 'edit', 'avatar'),
			'section' => '1_profile',
		]);
		
		return $return;
	}
}
