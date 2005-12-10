<?php

		global $page_owner;
		
		$title = run("profile:display:name") . " :: ". gettext("Friends I have linked to") ."";

		$body = run("content:friends:manage");
		$body .= run("friends:edit",array($page_owner));
		
		$body = run("templates:draw", array(
						'context' => 'contentholder',
						'title' => $title,
						'body' => $body
					)
					);

		$run_result = $body;
					
?>