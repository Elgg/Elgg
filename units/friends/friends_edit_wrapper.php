<?php

		global $page_owner;
		
		$title = run("profile:display:name") . " :: Friends";

		$body = run("content:friends:manage");
		$body .= run("friends:edit",array($page_owner));
		
		$body = run("templates:draw", array(
						'context' => 'infobox',
						'name' => $title,
						'contents' => $body
					)
					);

		$run_result = $body;
					
?>