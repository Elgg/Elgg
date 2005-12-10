<?php

	//	ELGG weblog edit / add post page

	// Run includes
		require("../includes.php");
		
		run("weblogs:init");
		run("profile:init");
		run("friends:init");
		
		define("context", "weblog");
		
		$title = run("profile:display:name") . " :: " . gettext("Blog");
		
		$body = run("content:weblogs:edit");
		$body .= run("weblogs:edit");

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