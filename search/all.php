<?php

	//	ELGG search through everything page

	// Run includes
		require("../includes.php");
		
		run("search:init");
		run("search:all:tagtypes");
		
		$title = "Searching Everything";

		$body = run("content:search:all");
		$body .= run("search:all:display", $_REQUEST['tag']);
		
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