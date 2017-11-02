<?php
/**
 * Elgg log browser.
 *
 * @package ElggLogBrowser
 */

/**
 * Initialize the log browser plugin
 *
 * @return void
 */
function logbrowser_init() {
	
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'logbrowser_user_hover_menu');
	
	elgg_register_menu_item('page', [
		'name' => 'administer_utilities:logbrowser',
		'text' => elgg_echo('admin:administer_utilities:logbrowser'),
		'href' => 'admin/administer_utilities/logbrowser',
		'section' => 'administer',
		'parent_name' => 'administer_utilities',
		'context' => 'admin',
	]);
}

/**
 * Add to the user hover menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:user_hover'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 */
function logbrowser_user_hover_menu($hook, $type, $return, $params) {
	
	$user = elgg_extract('entity', $params);
	if (!$user instanceof ElggUser) {
		return;
	}

	$return[] = \ElggMenuItem::factory([
		'name' => 'logbrowser',
		'href' => "admin/administer_utilities/logbrowser?user_guid={$user->guid}",
		'text' => elgg_echo('logbrowser:explore'),
		'icon' => 'search',
		'section' => 'admin',
	]);
	
	return $return;
}

return function() {
	elgg_register_event_handler('init', 'system', 'logbrowser_init');
};
