<?php

		global $owner;
		global $page_owner;
		global $profile_id;

		if (isset($_GET['friends_name'])) {
			$owner = (int) run("users:name_to_id", $_GET['friends_name']);
		} else if (isset($_REQUEST['owner'])) {
			$owner = (int) $_REQUEST['owner'];
		}  else if (isset($page_owner)) {
			$owner = (int) $page_owner;
		} else {
			$owner = -1;
		}
		/*if (logged_on) {
			$owner = (int) $_SESSION['userid'];
		}*/
		
		$page_owner = $owner;
		$profile_id = $owner;
		
		global $page_userid;
		
		$page_userid = run("users:id_to_name", $page_owner);
		
		global $metatags;
		
		if ($owner != -1) {
			$metatags .= "<link rel=\"meta\" type=\"application/rdf+xml\" title=\"FOAF\" href=\"".url."$page_userid/foaf\" />";
		}
		
?>