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
		
		$now = time();
		$time = get_plugin_setting('days', 'updateclient');
		$last_checked = get_plugin_setting('last_checked', 'updateclient');
		if (($time) && ($last_checked < $now - (86400 * $time)))
		{
			updateclient_check_core();
		}
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
	
	/**
	 * Send a message to the admin notifications page.
	 *
	 * @param unknown_type $subject
	 * @param unknown_type $message
	 */
	function updateclient_notify_message($subject, $message)
	{
		notify_user(2,1,$subject,$message);
	}
	
	/**
	 * Get updates.
	 *
	 * @return unknown
	 */
	function updateclient_check_core()
	{
		global $CONFIG;
		
		// Phone home and check core
		$url = get_plugin_setting('updateserver', 'updateclient');
		
		$result = send_api_get_call($url, array('method' => 'elgg.system.getlatestversion'), array());
		
		if (($result) && ($result->status == 0))
		{
			$result = $result->result;
			
			include_once($CONFIG->url . "version.php");
		
			if (
				($version != $result['version']) ||
				($release != $result['release'])
			)
			{
				// Notify
				updateclient_notify_message(
					elgg_echo('updateclient:message:title'),
					sprintf(elgg_echo('updateclient:message:body'),
						$release['release'],
						$release['version'],
						$release['codename'],
						$release['url'],
						$release['notes']
					)
				);
				
			}
		}
		
		// Set last_checked
		set_plugin_setting('last_checked', time(), 'updateclient');
		
		return true;
	}
	
	// Initialise
	register_elgg_event_handler('init','system','updateclient_init');
?>