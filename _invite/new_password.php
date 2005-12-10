<?php

	//	ELGG get new password page

	// Run includes
		require("../includes.php");
		
		run("invite:init");
		
		$title = sprintf(gettext("Get new %s password"), sitename);
		
		$body .= run("invite:password:new");
		
		$body = run("templates:draw", array(
						'context' => 'contentholder',
						'title' => $title,
						'body' => $body
					)
					);
		
		echo run("templates:draw:page", array(
						$title, $body
					)
					);

?>