<?php

	// User-related functions
	
	// Get current access level
	
		function accesslevel($owner = -1) {
			
			$currentaccess = 0;

	// For now, there are three access levels: 0 (logged out), 1 (logged in) and 1000 (me)

			if (logged_on == 1) {
				$currentaccess++;
			}
			
			if ($_SESSION['userid'] == $owner) {
				$currentaccess += 1000;
			}
			
			return $currentaccess;
			
		}
	
	// Protect users to a certain access level
	
		function protect($level, $owner = -1) {
		
			if (accesslevel($owner) < $level) {
				run("access_denied");

				// run("display:bottomofpage");
				exit();
			}
			
		}

?>