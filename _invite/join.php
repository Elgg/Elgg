<?php

	//	ELGG invite-a-friend page

	// Run includes
		require("../includes.php");
		
		run("invite:init");
		
		$title = sprintf(gettext("Join %s"),sitename);
		
		$body = run("content:invite:join");
		$body .= run("invite:join");
		
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