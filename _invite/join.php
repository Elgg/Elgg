<?php

	//	ELGG invite-a-friend page

	// Run includes
		require("../includes.php");
		
		run("invite:init");
		
		$title = "Join Elgg";
		
		$body = run("content:invite:join");
		$body .= run("invite:join");
		
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