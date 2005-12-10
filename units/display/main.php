<?php

	// Display functions
	
	// Initialise
		$function['init'][] = path . "units/display/function_init.php";
	
	// Top of page
		$function['display:topofpage'][] = path . "units/display/function_topofpage.php";
		$function['display:topofpage'][] = path . "units/display/function_messages.php";
		
	// Bottom of page
		$function['display:bottomofpage'][] = path . "units/display/function_bottomofpage.php";
		
	// Log on pane
		$function['display:log_on_pane'][] = path . "units/display/function_log_on_pane.php";
		$function['display:sidebar'][] = path . "units/display/function_log_on_pane.php";
		
	// Menus
		$function['display:menus:main'][] = path . "units/display/function_mainmenu.php";
		$function['display:menus:main'][] = path . "units/display/function_usermenu.php";
		
		$function['display:menus:sub'][] = path . "units/display/function_submenu.php";
		
	// Form elements
		$function['display:input_field'][] = path . "units/display/function_input_field_display.php";
		$function['display:output_field'][] = path . "units/display/function_output_field_display.php";

	// TEMPLATING ---
	
	// Adds data to the various strings used in templating
		$function['display:addstring'][] = path . "units/display/function_display_addstring.php";
		
?>