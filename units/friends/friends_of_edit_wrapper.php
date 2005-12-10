<?php

		global $page_owner;

		$title = run("profile:display:name",array($page_owner)) . " :: ". gettext("Friends who have linked to you") ."";

		$body = run("content:friends:of:manage");
		$body .= run("friends:of:edit",array($page_owner));
		
		$body = run("templates:draw", array(
						'context' => 'contentholder',
						'title' => $title,
						'body' => $body
					)
					);

		$run_result .= $body;
					
?>