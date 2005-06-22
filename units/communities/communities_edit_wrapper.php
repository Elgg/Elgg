<?php

		global $page_owner;
		
		$title = run("profile:display:name") . " :: Communities";

		$body = run("content:communities:manage");
		$body .= run("communities:edit",array($page_owner));
		
		$body = run("templates:draw", array(
						'context' => 'infobox',
						'name' => $title,
						'contents' => $body
					)
					);

		$run_result = $body;
					
?>