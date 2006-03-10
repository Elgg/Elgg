<?php

	require("../includes.php");
	
	run("weblogs:init");
	run("profile:init");
	run("rss:init");
	
	define('context','resources');
	global $page_owner;
	
	run("rss:update:all:cron",$page_owner);

?>