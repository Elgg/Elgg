<?php

	// ELGG weblog system initialisation
	
	// ID of profile to view / edit

		global $profile_id;
	
		if (isset($_GET['weblog_name'])) {
			$profile_id = (int) run("users:name_to_id", $_GET['weblog_name']);
		} else if (isset($_GET['profile_id'])) {
			$profile_id = (int) $_GET['profile_id'];
		} else if (isset($_POST['profile_id'])) {
			$profile_id = (int) $_POST['profileid'];
		} else if (isset($_SESSION['userid'])) {
			$profile_id = (int) $_SESSION['userid'];
		} else {
			$profile_id = -1;
		}

		global $page_owner;
		
		$page_owner = $profile_id;
		
		global $page_userid;
		
		$page_userid = run("users:id_to_name", $profile_id);

	// Add RSS to metatags
	
		global $metatags;
		$metatags .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"".url."$page_userid/weblog/rss\" />\n";
				
?>