<?php

		global $page_owner;

		$title = run("profile:display:name",array($page_owner)) . " :: Friend Of";

		$body = run("content:friends:of:manage");
		$body .= run("friends:of:edit",array($page_owner));
		
		$body = run("templates:draw", array(
						'context' => 'infobox',
						'name' => $title,
						'contents' => $body
					)
					);

		$run_result .= $body;
					
?>