<?php

	require("../includes.php");
	
	global $page_owner;
	
	run("weblogs:init");
	run("profile:init");	
	run("rss:init");
	
	define('context','resources');
	
	$title = run("profile:display:name") ." :: " . gettext("Feeds");
	
	$body = run("rss:subscriptions",$feed);
	
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