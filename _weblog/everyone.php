<?php

	//	ELGG weblog view page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		run("friends:init");
		run("weblogs:init");
		
		define("context", "weblog");
		
		$title = gettext("All blogs");		

		$body = run("content:weblogs:view");
		$body .= run("weblogs:everyone:view");
		
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