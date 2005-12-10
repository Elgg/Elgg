<?php

	//	ELGG join-with-no-invite page

	// Run includes
		require("../includes.php");
		
		run("invite:init");
		
		$title = sprintf(gettext("Join %s"), sitename);
		
		$body = run("content:invite:join");
		$body .= run("join:no_invite");
		
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