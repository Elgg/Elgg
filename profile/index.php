<?php

	//	ELGG profile view page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		
		$title = run("profile:display:name");

		$body = run("content:profile:view");
		$body .= run("profile:view");
		
		$body = run("templates:draw", array(
						'context' => 'infobox',
						'name' => $title,
						'contents' => $body
					)
					);
					
		echo run("templates:draw:page", array(
					$title, $body
				)
				);

?>