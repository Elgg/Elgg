<?php

	if (context == "account") {

		$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Edit user details"),
							'location' => url . '_userdetails/'
						)
						);
			
	}

?>