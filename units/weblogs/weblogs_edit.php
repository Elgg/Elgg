<?php

	// Edit weblog posts

	// If a post ID has been specified, edit a specific post - otherwise create a new one
	
		if (isset($_REQUEST['weblog_post_id']) && isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")) {
			
			$id = (int) $_REQUEST['weblog_post_id'];
			$run_result .= run("weblogs:posts:edit",$id);
			
		} else {
			
			$run_result .= run("weblogs:posts:add");
			
		}

		// echo run("users:access_level_sql_where");
		
?>