<?php
/**
 * Elgg log browser.
 * 
 * @package ElggLogBrowser
 * @author Curverider Ltd
 * @link http://elgg.com/
 */

/**
 * Initialise the log browser and set up the menus.
 *
 */
function logbrowser_init()
{
	global $CONFIG;
	
	// Register a page handler, so we can have nice URLs
	register_page_handler('logbrowser','logbrowser_page_handler');
	
	// Extend CSS
	elgg_extend_view('css','logbrowser/css');
	
	// Extend context menu with admin logbrowsre link
	if (isadminloggedin()) {
		elgg_extend_view('profile/menu/adminlinks','logbrowser/adminlinks',10000);
	}
	
	elgg_add_submenu_item(array(
		'text' => elgg_echo('logbrowser'),
		'href' => "{$CONFIG->wwwroot}pg/logbrowser",
		'parent_id' => 'overview',
	), 'admin', 'default');
}

/**
 * Log browser page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function logbrowser_page_handler($page) 
{
	global $CONFIG;
	
	// only interested in one page for now
	include($CONFIG->pluginspath . "logbrowser/index.php"); 
}


// Initialise log browser
register_elgg_event_handler('init','system','logbrowser_init');
