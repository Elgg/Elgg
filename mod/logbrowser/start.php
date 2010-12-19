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
	
	elgg_extend_view('css/admin', 'logbrowser/css');

	elgg_register_plugin_hook_handler('register', 'menu:user_admin', 'logbrowser_user_admin_menu');
	
	elgg_add_admin_submenu_item('logbrowser', elgg_echo('logbrowser'), 'overview');
}

/**
 * Add to the user admin menu
 */
function logbrowser_user_admin_menu($hook, $type, $return, $params) {
	$user = $params['user'];

	$url = "pg/admin/overview/logbrowser/?user_guid={$user->guid}";
	$item = new ElggMenuItem('logbrowser', elgg_echo('logbrowser:explore'), $url);
	elgg_register_menu_item('user_admin', $item);
}