<?php
	/**
	 * Elgg GUID Tool
	 * 
	 * @package ElggGUIDTool
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the tool and set menus.
	 */
	function guidtool_init()
	{
		global $CONFIG;
		
		/*if (isadminloggedin())
		{
			add_menu(elgg_echo('guidtool'), $CONFIG->wwwroot . "pg/guidtool/",array(
//				menu_item(elgg_echo('guidtool:browse'), $CONFIG->wwwroot."pg/guidtool/"),
//				menu_item(elgg_echo('guidtool:import'), $CONFIG->wwwroot."pg/guidtool/import/"),
			),'guidtool');
			
		}*/
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('guidtool','guidtool_page_handler');
		
		// Register some actions
		register_action("guidtool/delete",false, $CONFIG->pluginspath . "guidtool/actions/delete.php", true);
		
	}
	
	/**
	 * Post init gumph.
	 */
	function guidtool_page_setup()
	{
		global $CONFIG;
		
		if ((isadminloggedin()) && (get_context()=='admin'))
		{
			add_submenu_item(elgg_echo('guidtool:browse'), $CONFIG->wwwroot."pg/guidtool/");
			add_submenu_item(elgg_echo('guidtool:import'), $CONFIG->wwwroot."pg/guidtool/import/");
		}
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
				case 'view' :
					if ((isset($page[1]) && (!empty($page[1])))) {
						add_submenu_item('GUID:'.$page[1], $CONFIG->url . "pg/guidtool/view/{$page[1]}/");
						add_submenu_item(elgg_echo('guidbrowser:export'), $CONFIG->url . "pg/guidtool/export/{$page[1]}/");
					}
					
				case 'export':
					
					if ((isset($page[1]) && (!empty($page[1])))) {
						
						set_input('entity_guid', $page[1]);
						if ($page[0] == 'view')
							include($CONFIG->pluginspath . "guidtool/view.php");
						else
						{
							if ((isset($page[2]) && (!empty($page[2])))) {
								set_input('format', $page[2]); 
								include($CONFIG->pluginspath . "guidtool/export.php");
							} else {
								set_input('forward_url', $CONFIG->url . "pg/guidtool/export/$page[1]/"); 
								include($CONFIG->pluginspath . "guidtool/format_picker.php");
							} 	
						}
					}
					else include($CONFIG->pluginspath . "guidtool/index.php"); 
				break;
				case 'import' :
					if ((isset($page[1]) && (!empty($page[1])))) {
						set_input('format', $page[1]);
						include($CONFIG->pluginspath . "guidtool/import.php");
					} else {
						set_input('forward_url', $CONFIG->url . "pg/guidtool/import/");  
						include($CONFIG->pluginspath . "guidtool/format_picker.php");
					} 
				break;
				default:
					include($CONFIG->pluginspath . "guidtool/index.php"); 
			}
		}
		else
			include($CONFIG->pluginspath . "guidtool/index.php"); 
	}
	
	/**
	 * Get a list of import actions
	 *
	 */
	function guidtool_get_import_actions()
	{
		global $CONFIG;
		
		$return = array();
		
		foreach ($CONFIG->actions as $action => $handler)
		{
			if (strpos($action, "import/")===0)
				$return[] = substr($action, 7);
		}
		
		return $return;
	}
	
	// Initialise log
	register_elgg_event_handler('init','system','guidtool_init');
	register_elgg_event_handler('pagesetup','system','guidtool_page_setup');
?>