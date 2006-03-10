<?php
	
	// Run includes
		require("../includes.php");
	
		define("context", "network");
	
	// Draw page
		echo run("templates:draw:page", array(
					"Network help ",
					run("templates:draw", array(
													'body' => run("help:network"),
													'title' => gettext("'Your Network' help"),
													'context' => 'contentholder'
												)
												)
			)
			);
		
?>