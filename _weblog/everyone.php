<?php

	//	ELGG weblog view page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		run("friends:init");
		run("weblogs:init");
		
		$title = "All Weblogs";		

		$body = run("content:weblogs:view");
		$body .= run("weblogs:everyone:view");
		
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