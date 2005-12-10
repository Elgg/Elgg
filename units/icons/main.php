<?php

	/*
	*	Icons plug-in
	*/

	// Actions
		$function["icons:init"][] = path . "units/icons/function_actions.php";
	
	// Icon management
		$function["icons:edit"][] = path . "units/icons/function_edit_icons.php";	
		$function["icons:add"][] = path . "units/icons/function_add_icons.php";
	
	// Menu button
		$function["menu:sub"][] = path . "units/icons/menu_sub.php";
		
	// Permissions check
		$function["permissions:check"][] = path . "units/icons/permissions_check.php";
		
?>