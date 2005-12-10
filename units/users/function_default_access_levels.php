<?php

	// Add some access levels
	
		$data['access'][] = array(gettext("Public"),"PUBLIC");
		$data['access'][] = array(gettext("Logged in users"),"LOGGED_IN");
		$data['access'][] = array(gettext("Private"),user . $_SESSION['userid']);

?>