<?php

	//	ELGG join-with-no-invite page

	// Run includes
		require("../includes.php");
		
		run("invite:init");
		
		$title = "Join " . sitename;
		
		$body = run("content:invite:join");
		$body .= run("join:no_invite");
		
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