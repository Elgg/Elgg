<?php

		ini_set("upload_max_filesize","5242880");

		global $owner;
		
		if (isset($_GET['files_name'])) {
			$owner = (int) run("users:name_to_id", $_GET['files_name']);
		} else if (isset($_REQUEST['owner'])) {
			$owner = (int) $_REQUEST['owner'];
		} else if (isset($_SESSION['userid'])) {
			$owner = (int) $_SESSION['userid'];
		}

		global $owner_username;		
		$owner_username = run("users:id_to_name",$owner);
		
		global $page_owner;
		$page_owner = $owner;
		
		global $profile_id;
		$profile_id = $owner;
		
		global $folder;
		
		if (isset($_REQUEST['folder'])) {
			$folder = (int) $_REQUEST['folder'];
			$result = db_query("select count(ident) as x from file_folders where ident = $folder and owner = $owner");
			if ($result[0]->x < 1) {
				$folder = -1;
			}
		} else {
			$folder = -1;
		}
		
?>