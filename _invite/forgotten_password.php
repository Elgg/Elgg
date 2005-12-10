<?php

	//	ELGG generate-a-new-password page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		run("invite:init");
		
		$title = gettext("Generate a New Password");
		
		$body .= run("invite:password:request");
		
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