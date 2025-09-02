<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

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
		
		if (elgg_get_config('trash_enabled')) {
			$return[] = \ElggMenuItem::factory([
				'name' => '1_trash',
				'text' => elgg_echo('trash:menu:page'),
				'href' => elgg_generate_url('trash:owner', [
					'username' => $user->username,
				]),
				'section' => 'configure',
			]);
		}
		
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
}
