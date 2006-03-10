<?php

	require("../includes.php");
	
	run("profile:init");
	run("weblogs:init");
	run("rss:init");
	
	define('context','resources');
	global $page_owner;
	
	$title = gettext("Popular feeds");
	
	$body = run("rss:subscriptions:popular",$feed);
	
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