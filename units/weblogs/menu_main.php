<?php

	$run_result .= run("templates:draw", array(
						'context' => 'menuitem',
						'name' => 'View your weblog',
						'location' => '/' . $_SESSION['username'] . '/weblog/'
					)
					);

	$run_result .= run("templates:draw", array(
						'context' => 'menuitem',
						'name' => 'Update your weblog',
						'location' => '/_weblog/edit.php'
					)
					);
					
?>