<?php
	/**
	 * Elgg OpenDD aggregator
	 * 
	 * @package ElggOpenDD
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the opendd plugin.
	 * Register actions, set up menus
	 */
	function opendd_init()
	{
		global $CONFIG;
		
		// Set up the menu for logged in users
		if (isloggedin()) 
		{
			add_menu(elgg_echo('opendd'), $CONFIG->wwwroot . "pg/opendd/{$_SESSION['user']->username}",array(
				menu_item(elgg_echo('opendd:your'), $CONFIG->wwwroot."pg/opendd/{$_SESSION['user']->username}"),
				menu_item(elgg_echo('opendd:feeds'), $CONFIG->wwwroot."pg/opendd/{$_SESSION['user']->username}/feeds/"),
				menu_item(elgg_echo('opendd:manage'), $CONFIG->wwwroot . "pg/opendd/{$_SESSION['user']->username}/manage/"),
			),'opendd');
		}
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('opendd','opendd_page_handler');
		
		// Register opendd url
		register_entity_url_handler('opendd_url','object','oddfeed');
		
		// Actions
		register_action("opendd/feed/subscribe",false, $CONFIG->pluginspath . "opendd/actions/opendd/feed/subscribe.php");
		register_action("opendd/feed/delete",false, $CONFIG->pluginspath . "opendd/actions/opendd/feed/delete.php");
		
		// Extend some views
		extend_view('css','opendd/css');
		
		
		// Subscribe fields
		$CONFIG->opendd = array(
			'feedurl' => 'text',
		);
		
	}
	
	/**
	 * Group page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function opendd_page_handler($page) 
	{
		global $CONFIG;
		
		if (isset($page[0]))
			set_input('username',$page[0]);
		
		if (isset($page[1]))
		{
			// See what context we're using
			switch($page[1])
			{		
				case "view" :
					if (isset($page[2]))
					{
						set_input('feed_guid', $page[2]);
						include($CONFIG->pluginspath . "opendd/viewfeed.php");
					}
				break;		
    			case "manage":  
   					include($CONFIG->pluginspath . "opendd/manage.php");
          		break;
          		case "feeds" :
					include($CONFIG->pluginspath . "opendd/feeds.php");
				break;
          		case "activity" :
          			if (isset($page[2]))
					{
						switch ($page[2])
						{
							case 'opendd' :
							default :
								set_input('view', 'odd');
								include($CONFIG->pluginspath . "opendd/index.php");
						}
					}
					break;
    			default:
    				include($CONFIG->pluginspath . "opendd/index.php");
			}
		}
		else
			include($CONFIG->pluginspath . "opendd/index.php");
	}
	
	/**
	 * Register a url to handle opendd feeds.
	 *
	 * @param ElggEntity $feed The feed object.
	 * @return string
	 */
	function opendd_url($feed) 
	{
		global $CONFIG;
		return $CONFIG->wwwroot . "pg/opendd/" . $feed->getOwnerEntity()->username . "/view/{$feed->guid}";
	}

	
	// Make sure the groups initialisation function is called on initialisation
	register_elgg_event_handler('init','system','opendd_init');
?>