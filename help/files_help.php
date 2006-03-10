<?php
	
	// Run includes
		require("../includes.php");
	
	define("context", "files");

	// Draw page
		echo run("templates:draw:page", array(
					"Help for " . sitename,
					run("templates:draw", array(
													'body' => run("help:files"),
													'title' => gettext("'Your Files' help"),
													'context' => 'contentholder'
												)
												)
			)
			);
		
?>