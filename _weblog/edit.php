<?php

	//	ELGG weblog edit / add post page

	// Run includes
		require("../includes.php");
		
		run("weblogs:init");
		run("profile:init");
		run("friends:init");
		
		$title = run("profile:display:name") . " :: Weblog";
		
		$body = run("content:weblogs:edit");
		$body .= run("weblogs:edit");

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