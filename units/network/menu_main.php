<?php

	global $page_owner;

	if (context == "network" && $page_owner == $_SESSION['userid']) {
		$run_result .= run("templates:draw", array(
							'context' => 'selectedmenuitem',
							'name' => gettext("Your Network"),
							'location' => url . $_SESSION['username'] . '/friends/'
						)
						);
	} else {
		$run_result .= run("templates:draw", array(
							'context' => 'menuitem',
							'name' => gettext("Your Network"),
							'location' => url . $_SESSION['username'] . '/friends/'
						)
						);
	}

?>