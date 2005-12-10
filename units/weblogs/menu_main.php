<?php

	global $page_owner;

	if (context == "weblog" && $page_owner == $_SESSION['userid']) {

		$run_result .= run("templates:draw", array(
							'context' => 'selectedmenuitem',
							'name' => gettext("Your Blog"),
							'location' => url . $_SESSION['username'] . '/weblog/'
						)
						);
	} else {
		$run_result .= run("templates:draw", array(
							'context' => 'menuitem',
							'name' => gettext("Your Blog"),
							'location' => url . $_SESSION['username'] . '/weblog/'
						)
						);
	}
					
?>