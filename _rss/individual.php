<?php

	require("../includes.php");
	
	run("weblogs:init");
	run("profile:init");
	run("rss:init");
	
	define('context','resources');
	global $page_owner;
	
	if (isset($_REQUEST['feed'])) {
		$feed = (int) $_REQUEST['feed'];
	} else {
		$feed = -1;
	}
	
	$title = gettext("Feed detail");
	
	run("rss:update",$feed);
	$body = run("rss:view:feed",$feed);
	
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