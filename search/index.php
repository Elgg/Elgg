<?php

	//	ELGG profile search page

	// Run includes
		require("../includes.php");
		
		run("search:init");
		run("search:all:tagtypes");
		
		$title = "Search";

		$body = run("content:profile:search");
		$body .= run("search:display");
		
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