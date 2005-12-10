<?php

	// Elgg administration utilities
	// Ben Werdmuller, September 2005

	// These utilities allow users tagged with the 'administration' flag
	// to perform tasks on other users' accounts, including editing posts,
	// banning or deleting accounts, adding accounts in bulk and so on.
	
	
	// Permissions check
	// Establishes permissions; if the question is 'does this admin user
	// have permissions', the answer is 'yes'
		$function['permissions:check'][] = path . "units/admin/permissions_check.php";
		
	// Administration panel menu options
		$function['menu:sub'][] = path . "units/admin/menu_sub.php";
		
	// Main admin panel screen
		$function['admin:main'][] = path . "units/admin/admin_main.php";
		
	// Content flagging system
		$function['profile:view'][] = path . "units/admin/display_content_flag_form.php";
		$function['weblogs:posts:view:individual'][] = path . "units/admin/display_content_flag_form.php";
		$function['files:folder:view'][] = path . "units/admin/display_content_flag_form.php";
		
	// Content flag administration
		$function['admin:contentflags'][] = path . "units/admin/admin_contentflags.php";

	// Extra administration of user details
		$function['userdetails:edit:details'][] = path . "units/admin/admin_userdetails.php";
	// Menu to view all users
		$function['admin:users'][] = path . "units/admin/admin_users.php";
		
	// Bulk user addition screen
		$function['admin:users:add'][] = path . "units/admin/admin_users_add.php";
		
	// Display a user control panel when given a database row from elgg.users
		$function['admin:users:panel'][] = path . "units/admin/admin_users_panel.php";
		
	// Admin-related actions
		$function['init'][] = path . "units/admin/admin_actions.php";
	
	// Top-menu
		$function['menu:top'] = array_merge(array(path . "units/admin/menu_top.php"), $function['menu:top']);
		
?>