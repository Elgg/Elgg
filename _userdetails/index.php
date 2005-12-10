<?php

	//	ELGG change user details page

	// Run includes
		require("../includes.php");

		run("profile:init");
		run("userdetails:init");
		
		if (isset($_REQUEST['context'])) {
			define("context", $_REQUEST['context']);
		} else {
			define("context", "account");
		}

		protect(1);
				
		$title = run("profile:display:name") . " :: ". gettext("Edit user details");
		
		$body = run("templates:draw", array(
				'context' => 'contentholder',
				'title' => $title,
				'body' => run("userdetails:edit")
			)
			);
		
		echo run("templates:draw:page", array(
				$title, $body
			)
			);

?>