<?php

	// Gets all the groups owned by a particular user, as specified in $parameter[0],
	// and return it in a data structure with the idents of all the users in each group
	
		$ident = (int) $parameter[0];
		
		if (!isset($_SESSION['groups_membership_cache'][$ident]) || (time() - $_SESSION['groups_membership_cache'][$ident]->created > 60)) {
		
			$groups = db_query("select groups.* from group_membership left join groups on groups.ident = group_membership.group_id where user_id = $ident");
			
			$membership = array();
			if (sizeof($groups) > 0) {
				foreach($groups as $group) {
					$tempdata = "";
					
					// @unset($data);
					$tempdata->name = stripslashes($group->name);
					$tempdata->ident = $group->ident;
					/* $members = db_query("select group_membership.user_id,
												users.name from group_membership 
												left join users on users.ident = group_membership.user_id
												where group_membership.group_id = " . $tempdata->ident);
					$tempdata->members = $members; */
					
					$membership[] = $tempdata;
					
				}
			}
			
			$_SESSION['groups_membership_cache'][$ident]->created = time();
			$_SESSION['groups_membership_cache'][$ident]->data = $membership;
			
		}
		
		$run_result = $_SESSION['groups_membership_cache'][$ident]->data;

?>