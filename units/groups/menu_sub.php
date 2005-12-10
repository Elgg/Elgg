<?php

	if (context == "network") {

		if (logged_on) {
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("Access controls"),
								'location' => url . '_groups/'
							)
							);
		}
						
	}

?>