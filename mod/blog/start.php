<?php

	/**
	 * Blog initialisation
	 *
	 * These parameters are required for the event API, but we won't use them:
	 * 
	 * @param unknown_type $event
	 * @param unknown_type $object_type
	 * @param unknown_type $object
	 */

		function blog_init() {
			
			// Load system configuration
				global $CONFIG;
				
			// Load translations
				register_translations($CONFIG->pluginspath . "blog/languages/");
				
			// Set up menu
				add_menu(elgg_echo('blog'), $CONFIG->wwwroot . "mod/blog/",array(
					menu_item(elgg_echo('blogread'),$CONFIG->wwwroot."mod/blog/"),
					menu_item(elgg_echo('blogwrite'),$CONFIG->wwwroot."mod/blog/edit.php"),
				));
				
		}

	// Make sure the blog initialisation function is called on initialisation
		register_event_handler('init','system','blog_init');
		
?>