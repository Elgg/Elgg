<?php

	$run_result .= run("templates:draw", array(
						'context' => 'menuitem',
						'name' => 'Friends',
						'location' => url . $_SESSION['username'] . '/friends/'
					)
					);

?>