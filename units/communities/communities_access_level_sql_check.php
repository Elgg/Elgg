<?php

	// Returns an SQL "where" clause containing all the access codes that the user can see
	
		if (logged_on) {
			
			$communities = db_query("select users.* from friends left join users on users.ident = friends.friend where users.user_type = 'community' and users.owner <> " . $_SESSION['userid'] . " and friends.owner = " . $_SESSION['userid']);
			if (sizeof($communities) > 0) {
				foreach($communities as $community) {
					$run_result .= "or access = \"community" . $community->ident . "\" ";
				}
			}
			$communities = db_query("select users.* from users where users.owner = " . $_SESSION['userid']);
			if (sizeof($communities) > 0) {
				foreach($communities as $community) {
					$run_result .= "or access = \"community" . $community->ident . "\" ";
				}
			}
						
		}

?>