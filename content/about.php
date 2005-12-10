<?php
	
	// Run includes
		require("../includes.php");

	// Draw page
		echo run("templates:draw:page", array(
					sprintf(gettext("About %s"), sitename),
					run("templates:draw", array(
													'contents' => run("content:about"),
													'name' => sprintf(gettext("About %s"), sitename),
													'context' => 'infobox'
												)
												)
			)
			);
		
?>