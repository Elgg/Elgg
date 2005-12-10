<?php

	if (context == "account") {

		$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Change theme"),
							'location' => url . '_templates/'
						)
						);
			
	}

?>