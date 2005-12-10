<?php

	//	ELGG profile edit page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		define("context", "profile");
		
		protect(1);

		global $page_owner;
		
		$title = run("users:display:name", $page_owner) . " :: ". gettext("Edit profile") ."";
		
		$body = run("content:profile:edit");
		$body .= run("profile:edit");
		
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