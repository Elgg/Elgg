<?php
	/**
	 * Update client.
	 * 
	 * @package ElggUpdateClient
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */	

	$DEFAULT_UPDATE_SERVER;

	/**
	 * Client update initialisation.
	 */
	function updateclient_init()
	{
		global $DEFAULT_UPDATE_SERVER;
		
		$DEFAULT_UPDATE_SERVER = 'http://updates.elgg.org/services/api/rest.php';
		
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
		return send_admin_message($subject, $message);
	}
	
	/**
	 * Get updates.
	 *
	 * @return unknown
	 */
	function updateclient_check_core()
	{
		global $CONFIG, $DEFAULT_UPDATE_SERVER;
		
		// Phone home and check core
		$url = get_plugin_setting('updateserver', 'updateclient');
		if (!$url) $url = $DEFAULT_UPDATE_SERVER;
		
		$result = send_api_get_call($url, array('method' => 'elgg.system.getlatestversion'), array());
		
		if (($result) && ($result->status == 0))
		{
			$result = $result->result;
		
			// Get version information
			$version = get_version();
			$release = get_version(true);
		
			if (
				($version != $result['version']) ||
				($release != $result['release'])
			)
			{
				// Notify
				updateclient_notify_message(
					elgg_echo('updateclient:message:title'),
					sprintf(elgg_echo('updateclient:message:body'),
						$result['release'],
						$result['version'],
						$result['codename'],
						$result['url'],
						$result['notes']
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