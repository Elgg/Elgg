<?php

	/**
	 * Elgg twitter widget
	 * This plugin allows users to pull in their twitter feed to display on their profile
	 * 
	 * @package ElggTwitter
	 */
	
		function twitter_init() {
    		
    		//extend css if style is required
    		    elgg_extend_view('css','twitter/css');
    		
    		//add a widget
			    add_widget_type('twitter',"Twitter","This is your twitter feed");
			
		}
		
		register_elgg_event_handler('init','system','twitter_init');

?>