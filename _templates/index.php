<?php

	//	ELGG template create / select page

	// Run includes
		require("../includes.php");
		
		protect(1);
		
		run("profile:init");
		run("templates:init");
		
		$title = run("profile:display:name") . " :: Select / Create Template";
		
		$body = run("content:templates:view");
		$body .= run("templates:view");
		$body .= run("content:templates:add");
		$body .= run("templates:add");
		
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