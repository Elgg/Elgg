<?php

	global $page_owner;
	
	if (context == "calendar" && $page_owner == $_SESSION['userid']) {
		
		$run_result .= run("templates:draw", array(
							'context' => 'selectedmenuitem',
							'name' => gettext("Your Calendar"),
							'location' => url . $_SESSION['username'] . '/calendar/'
						)
						);
	} else {
		$run_result .= run("templates:draw", array(
							'context' => 'menuitem',
							'name' => gettext("Your Calendar"),
							'location' => url . $_SESSION['username'] . '/calendar/'
						)
						);
	}
	
?>