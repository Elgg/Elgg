<?php

	//	ELGG weblog view page

	// Run includes
	require("../includes.php");
	
	run("profile:init");
	run("calendar:init");
	run("friends:init");
	
	define("context", "calendar");
	
	$title = run("profile:display:name") . " :: " . gettext("Event Archives");
	
	$body .= run("calendar:archives:month:view");
	
	$body = run("templates:draw", array(
					'context' => 'contentholder',
					'title' => $title,
					'body' => $body
				)
				);
	
	echo run("templates:draw:page", array(
					$title,
					$body
				)
				);

?>