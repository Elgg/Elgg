<?php

	// Returns an SQL "where" clause containing all the access codes that the user can see
	
		if (logged_on) {
			
			$groups = run("groups:getmembership",array($_SESSION['userid']));
			if (sizeof($groups) > 0) {
				foreach($groups as $group) {
					$run_result .= "or access = \"group" . $group->ident . "\" ";
				}
				
			}
						
		}

?>