<?php

	// Run includes
		require("../includes.php");

	// Draw page
		echo run("templates:draw:page", array(
					sitename . " Privacy Policy",
					run("templates:draw", array(
													'contents' => run("content:privacy"),
													'name' => sitename . " Privacy Policy",
													'context' => 'infobox'
												)
												)
			)
			);
		
?>
