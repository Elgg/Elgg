<?php
	
	// Run includes
		require("../includes.php");

	// Draw page
		echo run("templates:draw:page", array(
					sprintf(gettext("Running Your Own %s"),sitename),
					run("templates:draw", array(
													'contents' => run("content:run_your_own"),
													'name' => sprintf(gettext("Running Your Own %s"), sitename),
													'context' => 'infobox'
												)
												)
			)
			);
		
?>