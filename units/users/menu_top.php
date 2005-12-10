<?php

	$run_result .= run("templates:draw", array(
						'context' => 'topmenuitem',
						'name' => gettext("Account settings"),
						'location' => url . '_userdetails/'
					)
					);

	$run_result .= run("templates:draw", array(
						'context' => 'topmenuitem',
						'name' => gettext("Log off"),
						'location' => url . 'logoff.php?action=log_off'
					)
					);

?>