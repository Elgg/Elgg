<?php
	
	// Run includes
		require("../includes.php");

	// Draw page
		echo run("templates:draw:page", array(
					"About " . sitename,
					run("templates:draw", array(
													'contents' => run("content:about"),
													'name' => "About " . sitename,
													'context' => 'infobox'
												)
												)
			)
			);
		
?>