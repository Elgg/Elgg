<?php

	//	ELGG template create / select page

	// Run includes
		require("../includes.php");
		
		protect(1);
		
		run("profile:init");
		run("templates:init");
		
		$title = "Template Preview";
		
		$body = run("templates:preview");
		
		global $messages;
		$messages[] = "System message 1";
		$messages[] = "System message 2";
		
		echo run("templates:draw:page", array(
						$title, $body
					)
					);

?>