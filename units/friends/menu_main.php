<?php

	$run_result .= run("templates:draw", array(
						'context' => 'menuitem',
						'name' => 'Friends',
						'location' => '/' . $_SESSION['username'] . '/friends/'
					)
					);

?>