<?php

	// Returns an SQL "where" clause containing all the access codes that the user can see
	
		if (logged_on) {
			
			$run_result = " owner = " . $_SESSION['userid'] . " ";
			$run_result .= " or access IN ('public', 'LOGGED_IN', 'user" . $_SESSION['userid'] . "') ";
			
		} else {
			
			$run_result = " access = 'public' ";
			
		}
		
?>