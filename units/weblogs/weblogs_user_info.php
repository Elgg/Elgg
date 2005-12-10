<?php

	global $profile_id;
	
	// If this is someone else's portfolio, display the user's icon
		if ($profile_id != $_SESSION['userid']) {
			run("users:infobox", array(gettext("Profile Owner"),array($profile_id)));
		} else {
			run("users:infobox", array(gettext("You"),array($profile_id)));
		}

?>