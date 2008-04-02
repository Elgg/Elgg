<?php
	function guidbrowser_init($event, $object_type, $object = null) {
		
		global $CONFIG;
			// register_translations($CONFIG->pluginspath . "guidbrowser/languages/");
		add_menu("GUID Browser",$CONFIG->wwwroot . "mod/tasklist/",array(
				menu_item("The GUID browser",$CONFIG->wwwroot."mod/guidbrowser/"),
		));
	}
	
	function guidbrowser_displayentity($entity)
	{
		// display summary
		// display full on clickdown
	}
	
	function guidbrowser_display($offset = 0, $limit = 10, $type = "", $subtype = "")
	{
		
	}
	
	
	// Make sure test_init is called on initialisation
	register_event_handler('init','system','guidbrowser_init');
?>