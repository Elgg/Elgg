<?php

	$run_result .= run("templates:draw", array(
						'context' => 'menuitem',
						'name' => 'Files',
						'location' => '/' . $_SESSION['username'] . '/files/'
					)
					);

?>