<?php

	// Add some access levels
	
		$data['access'][] = array("Public","PUBLIC");
		$data['access'][] = array("Logged in users","LOGGED_IN");
		$data['access'][] = array("Private","user" . $_SESSION['userid']);

?>