<?php

	// Gets all the groups owned by a particular user, as specified in $parameter[0],
	// and return it in a data structure with the idents of all the users in each group
	
		$ident = (int) $parameter[0];
		
		//if (!isset($_SESSION['groups_cache']) || (time() - $_SESSION['groups_cache']->created > 60)) {
		
			$groups = db_query("select * from groups where owner = $ident");
			$tempdata = "";
			
			$groupslist = array();
			if (sizeof($groups) > 0) {
				foreach($groups as $group) {

					$tempdata = "";
					
					// @unset($data);
					$tempdata->name = stripslashes($group->name);
					$tempdata->ident = $group->ident;
					$tempdata->access = $group->access;
					$members = db_query("select group_membership.user_id,
												users.name from group_membership 
												join users on users.ident = group_membership.user_id
												where group_membership.group_id = " . $tempdata->ident);
					$tempdata->members = $members;
					
					$groupslist[] = $tempdata;
					
				}
			}
			
			$_SESSION['groups_cache']->created = time();
			$_SESSION['groups_cache']->data = $groupslist;
			
		//}
		
		$run_result = $_SESSION['groups_cache']->data;

?>