<?php

	/**
	 * Simple dashboard plugin
	 *
	 * These parameters are required for the event API, but we won't use them:
	 * 
	 * @param unknown_type $event
	 * @param unknown_type $object_type
	 * @param unknown_type $object
	 */

		function dashboard_init($event, $object_type, $object = null) {
			
			global $CONFIG;
				
		}

	// Make sure test_init is called on initialisation
		register_elgg_event_handler('init','system','dashboard_init');
		
?>