<?php

	if (logged_on && run("users:flags:get", array("admin",$_SESSION['userid']))) {

		$run_result .= run("templates:draw", array(
						'context' => 'topmenuitem',
						'name' => gettext("Administration"),
						'location' => url . '_admin/'
					)
					);
					
	}

?>