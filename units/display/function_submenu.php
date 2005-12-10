<?php

	if (logged_on == 1) {
		
		$run_result .= run("templates:draw", array(
				'context' => 'submenu',
				'menuitems' => run("menu:sub")
			)
			);
				
	}

?>