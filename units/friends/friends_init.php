<?php

		global $owner;
		global $page_owner;
		global $profile_id;

		if (isset($_GET['friends_name'])) {
			$owner = (int) run("users:name_to_id", $_GET['friends_name']);
		} else if (isset($_REQUEST['owner'])) {
			$owner = (int) $_REQUEST['owner'];
		} else if (logged_on) {
			$owner = (int) $_SESSION['userid'];
		}
		
		$page_owner = $owner;
		$profile_id = $owner;
		
?>