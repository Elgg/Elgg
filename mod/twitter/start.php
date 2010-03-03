<?php

	/**
	 * Elgg twitter widget
	 * This plugin allows users to pull in their twitter feed to display on their profile
	 * 
	 * @package ElggTwitter
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	
		function twitter_init() {
    		
    		//extend css if style is required
    		    elgg_extend_view('css','twitter/css');
    		
    		//add a widget
			    add_widget_type('twitter',"Twitter","This is your twitter feed");
			
		}
		
		register_elgg_event_handler('init','system','twitter_init');

?>