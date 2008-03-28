<?php

	function exporttest_init($event, $object_type, $object = null) {
		
		global $CONFIG;
			
		add_menu("Export GUID",$CONFIG->wwwroot . "mod/exporttest/",array(
				menu_item("The GUID Exporter",$CONFIG->wwwroot."mod/exporttest/"),
		));
	}

	
	// Make sure test_init is called on initialisation
	register_event_handler('init','system','exporttest_init');
?>