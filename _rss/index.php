<?php

	require("../includes.php");
	
	run("weblogs:init");
	run("profile:init");
	run("rss:init");
	
	define('context','resources');
	global $page_owner;
	
	$title = run("profile:display:name") ." :: " . gettext("Feeds");
	
	run("rss:update:all",$page_owner);
	$body = run("rss:view",$page_owner);
	
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