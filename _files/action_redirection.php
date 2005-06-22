<?php

	//	ELGG files perform-action-then-redirect page

	// Run includes
		require("../includes.php");
		
		run("files:init");

		global $redirect_url;
		global $messages;
		global $page_owner;
		
		if (isset($_REQUEST['files_owner'])) {
			$page_owner = (int) $_REQUEST['files_owner'];
		}
		
		if (isset($messages) && sizeof($messages) > 0) {
			$_SESSION['messages'] = $messages;
		}
		
		if (defined('redirect_url')) {
			header("Location: " . redirect_url);
		} else {
			header("Location: " . url);
		}
		
?>