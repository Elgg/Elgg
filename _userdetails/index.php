<?php

	//	ELGG change user details page

	// Run includes
		require("../includes.php");

		run("userdetails:init");
		run("profile:init");

		protect(1);
				
		$title = run("profile:display:name") . " :: Edit user details";
		
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