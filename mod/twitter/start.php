<?php

	/**
	 * Elgg twitter widget
	 * This plugin allows users to pull in their twitter feed to display on their profile
	 * 
	 * @package ElggTwitter
	 */
	
		function twitter_init() {
    		
    		//extend css if style is required
    		    elgg_extend_view('css/screen', 'twitter/css');
    		
    		//add a widget
			    elgg_register_widget_type('twitter',"Twitter","This is your twitter feed");
			
		}
		
		elgg_register_event_handler('init','system','twitter_init');

?>