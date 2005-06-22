<?php

	$run_result .= run("templates:draw", array(
						'context' => 'menuitem',
						'name' => 'View your profile',
						'location' => url . $_SESSION['username'] . '/'
					)
					);
	$run_result .= run("templates:draw", array(
						'context' => 'menuitem',
						'name' => 'Edit your profile',
						'location' => url . 'profile/edit.php'
					)
					);

?>