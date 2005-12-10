<?php

	require("includes.php");
	
	if (logged_on) {
		$body = run("content:mainindex");
	} else {
		$body = run("content:mainindex:loggedout");
	}
	
	echo run("templates:draw:page", array(
					sitename,
					run("templates:draw", array(
													'body' => $body,
													'title' => gettext("Main Index"),
													'context' => 'contentholder'
												)
												)
			)
			);
			
?>