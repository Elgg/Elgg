<?php

	// Run includes
		require("../includes.php");

	// Draw page
		echo run("templates:draw:page", array(
					sitename . " Terms &amp; Conditions",
					run("templates:draw", array(
													'contents' => run("content:terms"),
													'name' => sitename . " Terms &amp; Conditions",
													'context' => 'infobox'
												)
												)
			)
			);

?>