<?php

	//	ELGG manage files page

	// Run includes
		require("../includes.php");
		
	// Initialise functions for user details, icon management and profile management
		run("userdetails:init");
		run("profile:init");
		run("files:init");
		
		define("context", "files");

	// Whose files are we looking at?

		global $page_owner;
		$title = run("profile:display:name") . " :: " . gettext("Edit Folder");

		$body = run("content:folders:edit");
		$body .= run("files:folders:edit");
		
		echo run("templates:draw:page", array(
					$title,
					run("templates:draw", array(
							'context' => 'contentholder',
							'title' => $title,
							'body' => $body
						)
						)
				)
				);
				
?>