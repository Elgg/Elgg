<?php

	//	ELGG invite-a-friend page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		run("invite:init");
		
	// You must be logged on to view this!
		if (logged_on) {
		
		$title = "Invite a Friend";
		
		$body = run("content:invite:invite");
		$body .= run("invite:invite");
		
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
				} else {
					header("Location: " . url);
				}

?>