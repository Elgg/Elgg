<?php

	// Returns the user_type of a particular user as specified in $parameter
	
		global $user_type;
		
		if (!isset($user_type[$parameter])) {
			$temp_user_type = db_query("select users.user_type from users where users.ident = $parameter");
			$user_type[$parameter] = $temp_user_type[0]->user_type;
		}
		
		$run_result = $user_type[$parameter];
		
?>