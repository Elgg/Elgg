<?php
/**
 * Elgg user settings functions.
 * Functions for adding and manipulating options on the user settings panel.
 *
 * @package Elgg.Core
 * @subpackage Settings.User
 */
/**
 * Register menu items for the user settings page menu
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _elgg_user_settings_menu_register($hook, $type, $return, $params) {
	$user = elgg_get_page_owner_entity();
	if (!$user) {
		return;
	}
	if (!elgg_in_context('settings')) {
		return;
	}
	
	$return[] = \ElggMenuItem::factory([
		'name' => '1_account',
		'text' => elgg_echo('usersettings:user:opt:linktext'),
		'href' => "settings/user/{$user->username}",
		'section' => 'configure',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => '1_plugins',
		'text' => elgg_echo('usersettings:plugins:opt:linktext'),
		'href' => '#',
		'section' => 'configure',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => '1_statistics',
		'text' => elgg_echo('usersettings:statistics:opt:linktext'),
		'href' => "settings/statistics/{$user->username}",
		'section' => 'configure',
	]);
	// register plugin user settings menu items
	$active_plugins = elgg_get_plugins();
	
	foreach ($active_plugins as $plugin) {
		$plugin_id = $plugin->getID();
		if (!elgg_view_exists("usersettings/$plugin_id/edit") && !elgg_view_exists("plugins/$plugin_id/usersettings")) {
			continue;
		}
		if (elgg_language_key_exists($plugin_id . ':usersettings:title')) {
			$title = elgg_echo($plugin_id . ':usersettings:title');
		} else {
			$title = $plugin->getFriendlyName();
		}
		
		$return[] = \ElggMenuItem::factory([
			'name' => $plugin_id,
			'text' => $title,
			'href' => "settings/plugins/{$user->username}/$plugin_id",
			'parent_name' => '1_plugins',
			'section' => 'configure',
		]);
	}
	
	return $return;
}
/**
 * Prepares the page menu to strip out empty plugins menu item for user settings
 *
 * @param string $hook   prepare
 * @param string $type   menu:page
 * @param array  $value  array of menu items
 * @param array  $params menu related parameters
 *
 * @return array
 * @access private
 */
function _elgg_user_settings_menu_prepare($hook, $type, $value, $params) {
	if (empty($value)) {
		return $value;
	}
	
	if (!elgg_in_context("settings")) {
		return $value;
	}
	
	$configure = elgg_extract("configure", $value);
	if (empty($configure)) {
		return $value;
	}
	
	foreach ($configure as $index => $menu_item) {
		if (!($menu_item instanceof ElggMenuItem)) {
			continue;
		}
		
		if ($menu_item->getName() == "1_plugins") {
			if (!$menu_item->getChildren()) {
				// no need for this menu item if it has no children
				unset($value["configure"][$index]);
			}
		}
	}
	
	return $value;
}
/**
 * Page handler for user settings
 *
 * @param array $page Pages array
 *
 * @return bool
 * @access private
 */
function _elgg_user_settings_page_handler($page) {
	if (!isset($page[0])) {
		$page[0] = 'user';
	}
	if (isset($page[1])) {
		$user = get_user_by_username($page[1]);
		elgg_set_page_owner_guid($user->guid);
	} else {
		$user = elgg_get_logged_in_user_entity();
		elgg_set_page_owner_guid($user->guid);
	}
	$vars['username'] = $user->username;
	switch ($page[0]) {
		case 'statistics':
			echo elgg_view_resource('settings/statistics', $vars);
			return true;
		case 'plugins':
			if (isset($page[2])) {
				$vars['plugin_id'] = $page[2];
				echo elgg_view_resource('settings/tools', $vars);
				return true;
			}
			break;
		case 'user':
			echo elgg_view_resource("settings/account", $vars);
			return true;
	}
	return false;
}
/**
 * Initialize the user settings library
 *
 * @return void
 * @access private
 */
function _elgg_user_settings_init() {
	elgg_register_page_handler('settings', '_elgg_user_settings_page_handler');
	elgg_register_plugin_hook_handler('register', 'menu:page', '_elgg_user_settings_menu_register');
	elgg_register_plugin_hook_handler('prepare', 'menu:page', '_elgg_user_settings_menu_prepare');
	
	elgg_register_action('usersettings/default_access');
	elgg_register_action('usersettings/email');
	elgg_register_action('usersettings/language');
	elgg_register_action('usersettings/name');
	elgg_register_action('usersettings/password');
	elgg_register_action('usersettings/username', null, 'admin');
	// extend the account settings form
	elgg_extend_view('core/settings/account', 'core/settings/account/username', 100);
	elgg_extend_view('core/settings/account', 'core/settings/account/name', 110);
	elgg_extend_view('core/settings/account', 'core/settings/account/password', 120);
	elgg_extend_view('core/settings/account', 'core/settings/account/email', 130);
	elgg_extend_view('core/settings/account', 'core/settings/account/language', 140);
	elgg_extend_view('core/settings/account', 'core/settings/account/default_access', 150);
}
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_user_settings_init');
};