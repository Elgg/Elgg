<?php

	/*
	*	Groups plug-in
	*/

	// Functions to perform upon initialisation
		$function['groups:init'][] = path . "units/groups/groups_init.php";
		$function['groups:init'][] = path . "units/groups/groups_actions.php";
		
	// Add user-owned groups to access levels
		$function['init'][] = path . "units/groups/groups_access_levels.php";

	// Function to retrieve groups
		$function['groups:get'][] = path . "units/groups/get_groups.php";
		$function['groups:get:external'][] = path . "units/groups/get_groups_external.php";
		$function['groups:getmembership'][] = path . "units/groups/get_groups_membership.php";
		
	// Group view / edit screen
		// $function['groups:editpage'][] = path . "units/groups/groups_display_membership.php";
		$function['groups:editpage'][] = path . "units/groups/groups_explanation.php";
		$function['groups:editpage'][] = path . "units/groups/groups_create.php";
		$function['groups:editpage'][] = path . "units/groups/groups_edit_existing.php";
		
		
	// Individual group editing function
		$function['groups:edit:display'][] = path . "units/groups/groups_edit_display.php";

	// Check access levels
		$function['users:access_level_check'][] = path . "units/groups/group_access_level_check.php";
	
	// Obtain SQL "where" string for access levels
		$function['users:access_level_sql_where'][] = path . "units/groups/function_access_level_sql_where.php";
		
	// Menu button
		$function['menu:main'][] = path . "units/groups/menu_main.php";
		
?>