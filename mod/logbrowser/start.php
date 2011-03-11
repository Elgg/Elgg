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
	
	elgg_register_admin_menu_item('administer', 'logbrowser', 'utilities');
}

/**
 * Add to the user hover menu
 */
function logbrowser_user_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];

	$url = "admin/overview/logbrowser?user_guid={$user->guid}";
	$item = new ElggMenuItem('logbrowser', elgg_echo('logbrowser:explore'), $url);
	$item->setSection('admin');
	$return[] = $item;

	return $return;
}
