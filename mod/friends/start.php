<?php

	/**
	 * Elgg Friends widget
	 * This plugin allows users to put a list of their friends, on their profile
	 * 
	 * @package ElggFriends
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	
		function friends_init() {
    		
    		// Load system configuration
				global $CONFIG;
    		
    		//add a widget
			    add_widget_type('friends',elgg_echo("friends"),elgg_echo('friends:widget:description'));
			
		}
		
		register_elgg_event_handler('init','system','friends_init');
        
?>