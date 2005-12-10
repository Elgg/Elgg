<?php

	// Run includes
		require("../includes.php");

	// Draw page
		echo run("templates:draw:page", array(
					sprintf(gettext("%s Terms &amp; Conditions"),sitename),
					run("templates:draw", array(
													'contents' => run("content:terms"),
													'name' => sprintf(gettext("%s Terms &amp; Conditions"),sitename),
													'context' => 'infobox'
												)
												)
			)
			);

?>