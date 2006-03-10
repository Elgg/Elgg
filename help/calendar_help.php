<?php

	// Run includes
		require("../includes.php");

       define("context", "calendar");

	// Draw page
		echo run("templates:draw:page", array(
					gettext("Calendar help"),
					run("templates:draw", array(
													'body' => run("help:calendar"),
													'title' => gettext("'Your calendar' help"),
													'context' => 'contentholder'
												)
												)
			)
			);
		
?>
