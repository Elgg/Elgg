<?php
	
	// Run includes
		require("../includes.php");

// Draw page
		echo run("templates:draw:page", array(
					sitename . " FAQ",
					run("templates:draw", array(
													'contents' => run("content:faq"),
													'name' => sitename . " FAQ",
													'context' => 'infobox'
												)
												)
			)
			);
		
?>