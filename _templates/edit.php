<?php

	//	ELGG template edit page

	// Run includes
		require("../includes.php");
		
		protect(1);
		
		run("profile:init");
		run("templates:init");
		
		$title = run("profile:display:name") . " :: Template Edit";
		
		$body = run("content:templates:edit");
		
		if (isset($_REQUEST['id'])) {
			$id = (int) $_REQUEST['id'];
			$body .= run("templates:edit",$id);
		} else {
			$body = run("templates:edit");
		}
		
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