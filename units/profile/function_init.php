<?php

	// ELGG profile system initialisation
	
	// ID of profile to view / edit

		global $profile_id;
	
		if (isset($_GET['profile_name'])) {
			$profile_id = (int) run("users:name_to_id", $_GET['profile_name']);
		} else if (isset($_GET['profile_id'])) {
			$profile_id = (int) $_GET['profile_id'];
		} else if (isset($_POST['profileid'])) {
			$profile_id = (int) $_POST['profileid'];
		} else if (isset($_SESSION['userid'])) {
			$profile_id = (int) $_SESSION['userid'];
		} else {
			$profile_id = -1;
		}

		global $page_owner;
		
		$page_owner = $profile_id;

?>