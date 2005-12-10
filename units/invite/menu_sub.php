<?php

	if (context == "network" && logged_on) {

		$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Invite a friend"),
							'location' => url . '_invite/'
						)
						);

		$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Page help"),
							'location' => url . 'help/network_help.php'
						)
						);


	}

?>