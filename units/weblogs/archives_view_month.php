<?php

	// Get the current profile ID
	
		global $profile_id;
		
	// Obtain the separate archive pages from the database
	
		$archives = db_query("SELECT distinct 
									EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(posted)) as archivestamp
									FROM `weblog_posts` 
									WHERE owner = $profile_id")
							or die(db_error());

	// If there are any archives ...
	
		if (sizeof($archives) > 0) {
		
	// Get the name of the weblog user
			
		$weblog_name = htmlentities(stripslashes($_REQUEST['weblog_name']));
			
	// Run through them
	
			$run_result .= "<ul>";
	
			foreach($archives as $archive) {
				
	// Extract the year and the month
	
				$year = substr($archive->archivestamp, 0, 4);
				$month = substr($archive->archivestamp, 4, 2);
				
	// Print a link
	
				$run_result .= "<li>";
				$run_result .= "<a href=\"/$weblog_name/weblog/archive/$year/$month/\">";
				$run_result .= date("F",gmmktime(0,0,0,$month,1,$year)) . " " . $year;
				$run_result .= "</a>";
				$run_result .= "</li>";
				
			}
			
			$run_result .= "</ul>";
			
	// If there are no posts to archive, say so!
			
		} else {
			
			$run_result .= "<p>There are no weblog posts to archive as yet.</p>";
			
		}
	
?>