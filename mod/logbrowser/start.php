<?php
/**
 * Elgg log browser.
 *
 * @package ElggLogBrowser
 */

elgg_register_event_handler('init', 'system', 'logbrowser_init');

/**
 * Initialize the log browser plugin.
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
 */
function logbrowser_user_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];

	$return[] = \ElggMenuItem::factory([
		'name' => 'logbrowser',
		'href' => "admin/administer_utilities/logbrowser?user_guid={$user->guid}",
		'text' => elgg_echo('logbrowser:explore'),
		'icon' => 'search',
		'section' => 'admin',
	]);
	
	return $return;
}
