<?php

	global $page_owner;

	if (context == "files" && $page_owner == $_SESSION['userid']) {

		$run_result .= run("templates:draw", array(
							'context' => 'selectedmenuitem',
							'name' => gettext("Your Files"),
							'location' => url . $_SESSION['username'] . '/files/'
						)
						);
	} else {
		
		$run_result .= run("templates:draw", array(
							'context' => 'menuitem',
							'name' => gettext("Your Files"),
							'location' => url . $_SESSION['username'] . '/files/'
						)
						);

		
	}

?>