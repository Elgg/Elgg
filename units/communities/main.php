<?php

	// Communities module
	
	/*
	
		A brief explanation:
		
		Communities are a specialisation of users. Each community is just another
		row in the users table, albeit with user_type set to 'community', which
		allows it to have all the features of a regular user.
		
		Friendships are stored in the same way too, but displayed as memberships.
		The 'owner' field of the users table stores the moderator for a community
		(for regular users it's set to -1).
		
		TO DO:
		
			- Allow a moderator to restrict access to communities
			- Allow moderators to delete all weblog postings and file uploads
	
	*/
	
	// Add communities to access levels
		$function['init'][] = path . "units/communities/communities_access_levels.php";
	
	// Communities actions
		$function['communities:init'][] = path . "units/communities/communities_actions.php";
		
	// Communities modifications of friends actions
		$function['friends:init'][] = path . "units/communities/communities_actions.php";
	
	// Communities bar down the right hand side
		$function['display:sidebar'][] = path . "units/communities/communities_owned.php";
		$function['display:sidebar'][] = path . "units/communities/community_memberships.php";

	// 'Communities' aspect to the little menus beneath peoples' icons
		$function['users:infobox:menu'][] = path . "units/communities/user_info_menu.php";
		
	// Permissions for communities
		$function['permissions:check'][] = path . "units/communities/permissions_check.php";
		
	// View community memberships
		$function['communities:editpage'][] = path . "units/communities/communities_edit_wrapper.php";
		$function['communities:edit'][] = path . "units/communities/communities_edit.php";
		$function['communities:edit'][] = path . "units/communities/communities_create.php";
		$function['communities:members'][] = path . "units/communities/communities_members.php";
		$function['communities:owned'][] = path . "units/communities/communities_moderator_of.php";
		$function['communities:owned'][] = path . "units/communities/communities_create.php";

	// Check access levels
		$function['users:access_level_check'][] = path . "units/communities/communities_access_level_check.php";
		
	// Obtain SQL "where" string for access levels
		$function['users:access_level_sql_where'][] = path . "units/communities/communities_access_level_sql_check.php";
				
	// Link to edit icons
		$function['profile:edit:link'][] = path . "units/communities/profile_edit_link.php";
		
	// Edit profile details
		$function['userdetails:edit'][] = path . "units/communities/userdetails_edit.php";
		
?>