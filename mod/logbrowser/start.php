<?php
/**
 * Elgg log browser.
 * 
 * @package ElggLogBrowser
 */

/**
 * Initialise the log browser and set up the menus.
 *
 */
function logbrowser_init() {
	global $CONFIG;
	
	// Extend CSS
	elgg_extend_view('css','logbrowser/css');
	
	// Extend context menu with admin logbrowsre link
	if (isadminloggedin()) {
		elgg_extend_view('profile/menu/adminlinks','logbrowser/adminlinks',10000);
	}
	
	elgg_add_admin_submenu_item('logbrowser', elgg_echo('logbrowser'), 'overview');
}

// Initialise log browser
register_elgg_event_handler('init','system','logbrowser_init');
