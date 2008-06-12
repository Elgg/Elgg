<?php

	// TEMPORARY!
	
		function widgettest_init() {
			
			add_widget_type('widgettest',"Test widget!","This is a test widget.");
			
		}
		
		register_elgg_event_handler('init','system','widgettest_init');

?>