<?php
	/**
	 * Elgg log browser.
	 * 
	 * @package ElggLogBrowser
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the log browser and set up the menus.
	 *
	 */
	function logbrowser_init()
	{
		global $CONFIG;
		
		if (isadminloggedin())
		{
			add_menu(elgg_echo('logbrowser'), $CONFIG->wwwroot . "pg/logbrowser/",array(
				menu_item(elgg_echo('logbrowser:browse'), $CONFIG->wwwroot."pg/logbrowser/"),
			),'logbrowser');
			
		}
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('logbrowser','logbrowser_page_handler');
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
	
	
	
	// Initialise log
	register_elgg_event_handler('init','system','logbrowser_init');
?>