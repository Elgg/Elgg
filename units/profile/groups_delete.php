<?php

	// groups:delete
	// When an access group is deleted, revert all profile items restricted to that group to private

		if (isset($parameter) && logged_on) {
			
			// Grab group ID
				$group_id = (int) $parameter;
			// Create 'private' access string for current user
				$access = "user" . $_SESSION['userid'];
				
			// Update profile_data table, setting access to $access 
			// where the owner is the current user and access = 'group$group_id'
				db_query("update profile_data set access = '$access' where access = 'group".$group_id."' and owner = '".$_SESSION['userid']."'");

		}

?>