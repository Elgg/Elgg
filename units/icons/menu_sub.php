<?php

	if (context == "account") {

		$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Your site picture"),
							'location' => url . '_icons/'
						)
						);
						
	}

?>