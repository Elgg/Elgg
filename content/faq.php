<?php
	
	// Run includes
		require("../includes.php");

// Draw page
		echo run("templates:draw:page", array(
					sprintf(gettext("%s FAQ"),sitename),
					run("templates:draw", array(
													'contents' => run("content:faq"),
													'name' => sprintf(gettext("%s FAQ"),sitename),
													'context' => 'infobox'
												)
												)
			)
			);
		
?>