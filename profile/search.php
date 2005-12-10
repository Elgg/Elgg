<?php

	//	ELGG profile search page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		
		$title = gettext("Search Profiles");

		$body = run("content:profile:search");
		$body .= run("profile:search");
		
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