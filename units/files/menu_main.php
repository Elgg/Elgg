<?php

	$run_result .= run("templates:draw", array(
						'context' => 'menuitem',
						'name' => 'Files',
						'location' => url . $_SESSION['username'] . '/files/'
					)
					);

?>