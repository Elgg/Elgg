<?php

	// Returns an SQL "where" clause containing all the access codes that the user can see
	
		$run_result = " access = \"public\" ";
	
		if (logged_on) {
			
			$run_result .= "or owner = \"" . $_SESSION['userid'] . "\" ";
			$run_result .= "or access = \"LOGGED_IN\" ";
			$run_result .= "or access = \"user" . $_SESSION['userid'] . "\" ";
						
		}
		
?>