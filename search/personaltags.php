<?php

	//	ELGG display popular tags page

	// Run includes
		require("../includes.php");
		
		run("search:init");
		run("profile:init");
		
		global $page_owner;
		
		$title = run("users:display:name", $page_owner) . " :: " . gettext("Tags");

		$body = run("content:tags");
		$body .= run("search:tags:personal:display");
		
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