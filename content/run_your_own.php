<?php
	
	// Run includes
		require("../includes.php");

	// Draw page
		echo run("templates:draw:page", array(
					"Running Your Own " . sitename,
					run("templates:draw", array(
													'contents' => run("content:run_your_own"),
													'name' => "Running Your Own " . sitename,
													'context' => 'infobox'
												)
												)
			)
			);
		
?>