<?php
	/**
	 * Elgg GUID Tool
	 * 
	 * @package ElggGUIDTool
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the tool and set menus.
	 */
	function guidtool_init()
	{
		global $CONFIG;
		
		if (isloggedin())
		{
			add_menu(elgg_echo('guidtool'), $CONFIG->wwwroot . "pg/guidtool/",array(
				menu_item(elgg_echo('guidtool:browse'), $CONFIG->wwwroot."pg/guidtool/"),
				menu_item(elgg_echo('guidtool:import'), $CONFIG->wwwroot."pg/guidtool/import/"),
			),'guidtool');
			
		}
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('guidtool','guidtool_page_handler');
	}
	
	/**
	 * Log browser page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function guidtool_page_handler($page) 
	{
		global $CONFIG;
		
		if (isset($page[0]))
		{
			switch ($page[0])
			{
				case 'import' : 
					include($CONFIG->pluginspath . "guidtool/import.php"); 
				break;
				default:
					include($CONFIG->pluginspath . "guidtool/index.php"); 
			}
		}
		else
			include($CONFIG->pluginspath . "guidtool/index.php"); 
	}
	
	
	
	// Initialise log
	register_elgg_event_handler('init','system','guidtool_init');
?>