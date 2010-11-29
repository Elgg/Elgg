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
	global $CONFIG;
	
	// Extend CSS
	elgg_extend_view('css/admin', 'logbrowser/css');
	
	// Extend context menu with admin logbrowser link
	if (isadminloggedin()) {
		elgg_extend_view('profile/menu/adminlinks', 'logbrowser/adminlinks', 10000);
	}
	
	elgg_add_admin_submenu_item('logbrowser', elgg_echo('logbrowser'), 'overview');
}
