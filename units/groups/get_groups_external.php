<?php

	// Gets all the groups owned by a particular user, as specified in $parameter[0],
	// and return it in a data structure with the idents of all the users in each group
	
		$ident = (int) $parameter[0];
		
		// if (!isset($_SESSION['groups_cache']) || (time() - $_SESSION['groups_cache']->created > 60)) {
		
			$where1 = run("users:access_level_sql_where",$ident);
			$groups = db_query("select groups.name, groups.ident, groups.access, groups.owner, 
										users.name as ownername, users.ident as owneruserid, users.username as ownerusername
										from group_membership 
										left join groups on groups.ident = group_membership.group_id
										left join users on users.ident = groups.owner
										where ($where1) and group_membership.user_id = $ident");
			$tempdata = "";
			
			$groupslist = array();
			if (sizeof($groups) > 0) {
				foreach($groups as $group) {
					
					// @unset($data);
					$tempdata->name = stripslashes($group->name);
					$tempdata->ident = $group->ident;
					$tempdata->access = $group->access;
					$tempdata->ownername = stripslashes($group->ownername);
					$tempdata->ownerusername = stripslashes($group->ownerusername);
					$tempdata->owneruserid = stripslashes($group->owneruserid);
					$groupslist[] = $tempdata;
					
				}
			}
			
		// }
		
		$run_result = $groupslist;

?>