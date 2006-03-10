<?php

	// Run includes
		require("../includes.php");

		define("context", "weblog");

	// Draw page
		echo run("templates:draw:page", array(
					"Blog help",
					run("templates:draw", array(
													'body' => run("help:blogs"),
													'title' => gettext("'Your blog' help"),
													'context' => 'contentholder'
												)
												)
			)
			);
		
?>
