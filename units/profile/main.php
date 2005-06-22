<?php

	// ELGG Profile system

	// Profile initialisation
		$function['profile:init'][] = path . "units/profile/function_init.php";
		$function['profile:init'][] = path . "units/profile/function_editfield_defaults.php";
		$function['profile:init'][] = path . "units/profile/function_actions.php";
	
	// Initialisation for the search function
		$function['search:init'][] = path . "units/profile/function_init.php";
		$function['search:init'][] = path . "units/profile/function_editfield_defaults.php";
		$function['search:all:tagtypes'][] = path . "units/profile/function_search_all_tagtypes.php";
		$function['search:all:tagtypes:rss'][] = path . "units/profile/function_search_all_tagtypes_rss.php";
		
	// Function to search through profiles
		$function['search:display_results'][] = path . "units/profile/function_search.php";
		$function['search:display_results:rss'][] = path . "units/profile/function_search_rss.php";
		
	// Functions to view and edit individual profile fields		
		$function['profile:editfield:display'][] = path . "units/profile/function_editfield_display.php";
		$function['profile:field:display'][] = path . "units/profile/function_field_display.php";
	
	// Function to edit all profile fields
		$function['profile:edit'][] = path . "units/profile/function_edit.php";
		
	// Function to view all profile fields
		$function['profile:view'][] = path . "units/profile/edit_link.php";
		$function['profile:view'][] = path . "units/profile/function_view.php";
		
	// Function to display user's name
		$function['profile:display:name'][] = path . "units/profile/function_display_name.php";
	
	// "View" and "Edit" buttons
		$function['menu:main'][] = path . "units/profile/menu_main.php";
	
	// Descriptive text
		$function['content:profile:edit'][] = path . "units/profile/content_edit.php";

	// Establish permissions
		$function['permissions:check'][] = path . "units/profile/permissions_check.php";
				
?>