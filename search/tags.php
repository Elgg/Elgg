<?php

	//	ELGG display popular tags page

	// Run includes
		require("../includes.php");
		
		run("search:init");
		
		$title = gettext("Some Tags");

		$body = run("content:tags");
		$body .= run("search:tags:display");
		
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