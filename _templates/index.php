<?php

	//	ELGG template create / select page

	// Run includes
		require("../includes.php");
		
		protect(1);
		
		run("profile:init");
		run("templates:init");
		
		define("context", "account");
		
		$title = run("profile:display:name") . " :: ". gettext("Select / Create Themes");
		
		$body = run("content:templates:view");
		$body .= run("templates:view");
		$body .= run("content:templates:add");
		$body .= run("templates:add");
		
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