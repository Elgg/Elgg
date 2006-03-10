<?php

	if (substr_count($parameter, "community") > 0 && logged_on) {
		$commnum = (int) substr($parameter, 9, 15);
		$result = db_query("select friends.owner from friends
												 join users on users.ident = friends.friend
												 where users.user_type = 'community'
												 and users.ident = $commnum
												 and friends.owner = " . $_SESSION['userid']);
		if (sizeof($result) > 0) {
			$run_result = true;
		} else {
			
			$result = db_query("select ident from users where user_type = 'community' and owner = " . $_SESSION['userid']);
			if (sizeof($result) > 0) {
				$run_result = true;
			}
			
		}
	}

?>