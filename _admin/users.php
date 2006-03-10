<?php

	//	ELGG content flagging admin panel page

	// Run includes
		require("../includes.php");
		
	// Initialise functions for user details, icon management and profile management
		run("admin:init");

		define("context", "admin");
		
	// You must be logged on to view this!
								
		echo run("templates:draw:page", array(
					gettext("Manage users"),
					run("templates:draw", array(
						'context' => 'contentholder',
						'title' => gettext("Manage users"), 
						'body' => run("admin:users")
					)
					)
				)
				);

?>