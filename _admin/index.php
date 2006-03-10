<?php

	//	ELGG main admin panel page

	// Run includes
		require("../includes.php");
		
	// Initialise functions for user details, icon management and profile management
		run("admin:init");

		define("context", "admin");
		
	// You must be logged on to view this!
								
		echo run("templates:draw:page", array(
					gettext("Administration"),
					run("templates:draw", array(
						'context' => 'contentholder',
						'title' => gettext("Administration"), 
						'body' => run("admin:main")
					)
					)
				)
				);

?>