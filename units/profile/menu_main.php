<?php

	$run_result .= run("templates:draw", array(
						'context' => 'menuitem',
						'name' => 'View your profile',
						'location' => '/' . $_SESSION['username'] . '/'
					)
					);
	$run_result .= run("templates:draw", array(
						'context' => 'menuitem',
						'name' => 'Edit your profile',
						'location' => '/profile/edit.php'
					)
					);

?>