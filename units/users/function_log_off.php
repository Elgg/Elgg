<?php

	// Function to log off
	
	// Are userid and usercode set?
	
		if (isset($_SESSION['userid']) & isset($_SESSION['usercode'])) {
			
	// Then set the usercode to blank
	
			$userid = (int) $_SESSION['userid'];
			db_query("update users set code = '' where ident = '$userid'");
			
		}
	
	// Set the session variables to blank

		unset($_SESSION['userid']);
		unset($_SESSION['usercode']);
		
	// Set headers to forward to main URL
	
		header("Location: " . url . "\n");

?>