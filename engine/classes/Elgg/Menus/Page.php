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
	 * Register links to the plugin settings admin section
	 *
	 * @note Plugin settings are alphabetically sorted in the submenu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminPluginSettings(\Elgg\Event $event) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		$current_route = elgg_get_current_route();
		if (elgg_extract('segments', $current_route->getMatchedParameters()) !== 'plugins' && $current_route->getName() !== 'admin:plugin_settings') {
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
			
			if (!elgg_view_exists("plugins/{$plugin_id}/settings")) {
				continue;
			}
			
			$plugin_name = $plugin->getDisplayName();
			$plugins_with_settings[strtolower($plugin_name)] = [
				'name' => "plugin:settings:{$plugin_id}",
				'href' => elgg_generate_url('admin:plugin_settings', [
					'plugin_id' => $plugin_id,
				]),
				'text' => $plugin_name,
				'section' => 'plugin_settings',
			];
		}
		
		if (empty($plugins_with_settings)) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
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
	 * @param \Elgg\Event $event 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerUserSettings(\Elgg\Event $event) {
		$user = elgg_get_page_owner_entity();
		if (!$user instanceof \ElggUser || !elgg_in_context('settings') || !$user->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
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
	 * @param \Elgg\Event $event 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerUserSettingsPlugins(\Elgg\Event $event) {
		$user = elgg_get_page_owner_entity();
		if (!$user instanceof \ElggUser || !elgg_in_context('settings') || !$user->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => '1_plugins',
			'text' => elgg_echo('usersettings:plugins:opt:linktext'),
			'href' => false,
			'section' => 'configure',
			'show_with_empty_children' => false,
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
	 * Add a link to the avatar edit page
	 *
	 * @param \Elgg\Event $event 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAvatarEdit(\Elgg\Event $event) {
		$user = $event->getParam('entity', elgg_get_page_owner_entity());
		if (!$user instanceof \ElggUser || !$user->canEdit() || !elgg_in_context('settings')) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit_avatar',
			'text' => elgg_echo('avatar:edit'),
			'href' => elgg_generate_entity_url($user, 'edit', 'avatar'),
			'section' => '1_profile',
		]);
		
		return $return;
	}
	
	/**
	 * Moves menu items registered to the page to the admin header
	 *
	 * @param \Elgg\Event $event 'register', 'menu:page'
	 *
	 * @return void
	 *
	 * @since 5.0
	 */
	public static function moveOldAdminSectionsToAdminHeader(\Elgg\Event $event) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$remove_items = [];
		foreach ($return as $menu_item) {
			$section_name = $menu_item->getSection();
			if (!in_array($section_name, ['configure', 'administer', 'information'])) {
				continue;
			}
			
			$menu_item->setSection('default');
			if (empty($menu_item->getParentName())) {
				$menu_item->setParentName($section_name);
			}
			
			elgg_register_menu_item('admin_header', $menu_item);
			$remove_items[] = $menu_item->getID();
			
			elgg_deprecated_notice("The menu item [{$menu_item->getID()}] is using an old section of the admin page menu. These sections have been moved to the 'admin_header' menu. Please update your menu item configuration.", '5.0');
		}
		
		foreach ($remove_items as $id) {
			// need to remove separately because removing during a foreach has issues
			$return->remove($id);
		}
	}
}
