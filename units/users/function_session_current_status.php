<?php

	// Preset logged in
	
		$logged_in = 0;

	// Is the user code and user ID set?
	
		if (isset($_SESSION['usercode']) && $_SESSION['usercode'] != "" && (isset($_SESSION['userid']) && ($_SESSION['userid'] != -1) )) {
	
			$userid = (int) $_SESSION['userid'];
			$usercode = addslashes($_SESSION['usercode']);
			$result = db_query("select * from users where ident = '$userid' and code = '$usercode'");

			if (sizeof($result) > 0) {
				$logged_in = 1;
			}

		}
	
	// Set logged-in status in stone

		define("logged_on",$logged_in);
		
	// If we're not logged in, set the user ID to -1
	
		if (!logged_on) {
			$_SESSION['userid'] = -1;
		}

?>