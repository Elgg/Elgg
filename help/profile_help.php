<?php
	
	// Run includes
		require("../includes.php");

		define("context", "profile");

// Draw page
		echo run("templates:draw:page", array(
					"Profile help",
					run("templates:draw", array(
													'body' => run("help:profile"),
													'title' => gettext("'Your Profile' help"),
													'context' => 'contentholder'
												)
												)
			)
			);
		
?>