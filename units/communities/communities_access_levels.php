<?php

	// Get communities
	
		$communities = db_query("select * from users where owner = " . $_SESSION['userid'] . " and user_type = 'community'");
	
		if (sizeof($communities) > 0 && $communities != null) {
			foreach($communities as $community) {
				
				$data['access'][] = array(gettext("Community") .": " . $community->name, "community" . $community->ident);
				
			}
		}
		
		$communities = db_query("select users.* from friends 
										left join users on users.ident = friends.friend 
										where users.user_type = 'community' 
										and users.owner <> " . $_SESSION['userid'] . "
										and friends.owner = " . $_SESSION['userid']);
		
		if (sizeof($communities) > 0 && $communities != null) {
			foreach($communities as $community) {
				
				$data['access'][] = array(gettext("Community") . ": " . $community->name, "community" . $community->ident);
				
			}
		}
		
		$communities = db_query("select * from users where owner = " . $_SESSION['userid']);

?>