<?php

	global $page_owner;
	
	if (context == "profile" && $page_owner == $_SESSION['userid']) {	

		$run_result .= run("templates:draw", array(
							'context' => 'selectedmenuitem',
							'name' => gettext("Your Profile"),
							'location' => url . $_SESSION['username'] . '/'
						)
						);
	} else {
		
		$run_result .= run("templates:draw", array(
							'context' => 'menuitem',
							'name' => gettext("Your Profile"),
							'location' => url . $_SESSION['username'] . '/'
						)
						);
		
	}

?>