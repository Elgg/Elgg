<?php

	/**
	 * Elgg Message board
	 * This plugin allows users and groups to attach a message board to their profile for other users
	 * to post comments and media.
	 *
	 * @todo allow users to attach media such as photos and videos as well as other resources such as bookmarked content
	 * 
	 * @package ElggMessageBoard
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	/**
	 * MessageBoard initialisation
	 *
	 * These parameters are required for the event API, but we won't use them:
	 * 
	 * @param unknown_type $event
	 * @param unknown_type $object_type
	 * @param unknown_type $object
	 */
	 
    function messageboard_init() {
        
        // Load system configuration
			global $CONFIG;
				
        // Extend system CSS with our own styles, which are defined in the messageboard/css view
			elgg_extend_view('css','messageboard/css');
        
        // Register a page handler, so we can have nice URLs
			register_page_handler('messageboard','messageboard_page_handler');
        
	    // add a messageboard widget
            add_widget_type('messageboard',"". elgg_echo("messageboard:board") . "","" . elgg_echo("messageboard:desc") . ".", "profile");
            
    
    }
    
    /**
	 * Messageboard page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
		function messageboard_page_handler($page) {
			
			global $CONFIG;
			
			// The username should be the file we're getting
			if (isset($page[0])) {
				set_input('username',$page[0]);
			}
			// Include the standard messageboard index
			include($CONFIG->pluginspath . "messageboard/index.php");
			
		}
   

    // Make sure the shouts initialisation function is called on initialisation
		    register_elgg_event_handler('init','system','messageboard_init');

    // Register actions
		global $CONFIG;
		register_action("messageboard/add",false,$CONFIG->pluginspath . "messageboard/actions/add.php");
		register_action("messageboard/delete",false,$CONFIG->pluginspath . "messageboard/actions/delete.php");
		
?>