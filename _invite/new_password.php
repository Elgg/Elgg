<?php

	//	ELGG get new password page

	// Run includes
		require("../includes.php");
		
		run("invite:init");
		
		$title = "Get new " . sitename . " password";
		
		$body .= run("invite:password:new");
		
		$body = run("templates:draw", array(
						'context' => 'infobox',
						'name' => $title,
						'contents' => $body
					)
					);
		
		echo run("templates:draw:page", array(
						$title, $body
					)
					);

?>