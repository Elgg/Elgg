<?php

	//	ELGG search through everything page

	// Run includes
		require("../includes.php");
		
		run("search:init");
		run("search:all:tagtypes");

		header("Content-Type: text/xml");
		echo run("search:all:display:rss", $_REQUEST['tag']);
		
?>