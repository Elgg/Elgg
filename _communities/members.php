<?php

	//	ELGG manage community members page

	// Run includes
		require("../includes.php");
		
	// Initialise functions for user details, icon management and profile management
		run("userdetails:init");
		run("profile:init");
		run("friends:init");

	// Whose friends are we looking at?
		global $page_owner;
		
	// You must be logged on to view this!
	//	protect(1);
		
		$title = run("profile:display:name") . " :: Members";
								
		echo run("templates:draw:page", array(
					$title, run("templates:draw", array(
						'context' => 'infobox',
						'name' => $title,
						'contents' => run("communities:members",array($page_owner))
					)
					)
				)
				);

?>