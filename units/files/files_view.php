<?php

	/*
	*	View files
	*/

	// Get owner and current folder
	
		global $owner;
		global $folder;
			
	// Check to ensure we have access to this folder, if we're not in the root
	
		if ($folder != -1) {
			
			$access = db_query("select access from file_folders where owner = $owner and ident = $folder");
			if (sizeof($access) > 0) {
				$access = $access[0]->access;
				$accessible = run("users:access_level_check",$access);
			} else {
				$accessible = false;
			}
			
		}
		
	// If we're in the root or an accessible folder, view it
		
		if ($accessible || $folder == -1) {
			$run_result .= run("files:folder:view",$folder);
		}
		
	// If this is the user's own file repository, allow him or her to edit it
	
		if ($owner == $_SESSION['userid']) {
			
			$run_result .= run("files:folder:edit",$folder);
			
		} else {
			
			//
			
		}
	
?>