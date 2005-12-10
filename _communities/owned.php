<?php

	//	ELGG manage community members page

	// Run includes
		require("../includes.php");
		
	// Initialise functions for user details, icon management and profile management
		run("userdetails:init");
		run("profile:init");
		run("friends:init");
		
		define("context", "network");

	// Whose friends are we looking at?
		global $page_owner;
		
	// You must be logged on to view this!
	//	protect(1);
		
		$title = run("profile:display:name") . " :: " . gettext("Owned Communities");
								
		echo run("templates:draw:page", array(
					$title, run("templates:draw", array(
						'context' => 'contentholder',
						'title' => $title,
						'body' => run("communities:owned",array($page_owner))
					)
					)
				)
				);

?>