<?php
	/**
	 * Update client.
	 * 
	 * @package ElggUpdateClient
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */	

	/**
	 * Client update initialisation.
	 */
	function updateclient_init()
	{
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('updateclient','updateclient_page_handler');
		
	}
	
	/**
	 * Handle pages.
	 *
	 * @param unknown_type $page
	 */
	function updateclient_page_handler($page)
	{
		global $CONFIG;
		
		if (isset($page[0]))
		{
			
    		add_submenu_item(elgg_echo('updateclient:label:core'), $CONFIG->url . "pg/updateclient/core/");
    		//add_submenu_item(elgg_echo('updateclient:label:plugins'), $CONFIG->url . "pg/updateclient/plugins/");
			
			// See what context we're using
			switch($page[0])
			{		
				case "core" :
					include($CONFIG->pluginspath . "updateclient/index.php");
				break;		
				case "plugins" :
					include($CONFIG->pluginspath . "updateclient/plugin.php");
				break;
    			
    			default:
    				include($CONFIG->pluginspath . "updateclient/index.php");
			}
		}
		else
			include($CONFIG->pluginspath . "updateclient/index.php");
	}
	
	function updateclient_check_core()
	{
		
	}
	
	// Initialise
	register_elgg_event_handler('init','system','updateclient_init');
?>