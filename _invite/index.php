<?php

	//	ELGG invite-a-friend page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		run("invite:init");
		
		define("context", "network");
		
	// You must be logged on to view this!
		if (logged_on) {
		
		$title = gettext("Invite a Friend");
		
		$body = run("content:invite:invite");
		$body .= run("invite:invite");
		
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
				} else {
					header("Location: " . url);
				}

?>