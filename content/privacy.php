<?php

	// Run includes
		require("../includes.php");

	// Draw page
		echo run("templates:draw:page", array(
					sprintf(gettext("%s Privacy Policy"),sitename),
					run("templates:draw", array(
													'contents' => run("content:privacy"),
													'name' => sprintf(gettext("%s Privacy Policy"), sitename),
													'context' => 'infobox'
												)
												)
			)
			);
		
?>
