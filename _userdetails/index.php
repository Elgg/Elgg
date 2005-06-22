<?php

	//	ELGG change user details page

	// Run includes
		require("../includes.php");

		run("profile:init");
		run("userdetails:init");

		protect(1);
				
		$title = run("profile:display:name") . " :: Edit details";
		
		$body = run("templates:draw", array(
				'context' => 'infobox',
				'name' => $title,
				'contents' => run("userdetails:edit")
			)
			);
		
		echo run("templates:draw:page", array(
				$title, $body
			)
			);

?>