<?php
	global $page_owner;

	if (context == "resources" && $page_owner == $_SESSION['userid']) {
		$run_result .= run("templates:draw", array(
							'context' => 'selectedmenuitem',
							'name' => gettext("Your Resources"),
							'location' => url . $_SESSION['username'] . '/feeds/'
						)
						);
	} else {
		$run_result .= run("templates:draw", array(
							'context' => 'menuitem',
							'name' => gettext("Your Resources"),
							'location' => url . $_SESSION['username'] . '/feeds/'
						)
						);
	}

?>