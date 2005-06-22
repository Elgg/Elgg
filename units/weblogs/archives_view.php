<?php

	// Get the current profile ID
	
		global $profile_id;
		
	// Obtain the separate archive pages from the database
	
		$archives = db_query("SELECT distinct 
									EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(posted)) as archivestamp
									FROM `weblog_posts` 
									WHERE weblog = $profile_id
									order by posted desc");

	// If there are any archives ...
	
		if (sizeof($archives) > 0) {
		
			$run_result .= "<h1 class=\"weblogdateheader\">Weblog Archive</h1>";
			
	// Get the name of the weblog user
			
		$weblog_name = htmlentities(stripslashes($_REQUEST['weblog_name']));
			
	// Run through them
	
			$lastyear = 0;
	
			foreach($archives as $archive) {
				
	// Extract the year and the month
	
				$year = substr($archive->archivestamp, 0, 4);
				$month = substr($archive->archivestamp, 4, 2);
				
				if ($year != $lastyear) {
					if ($lastyear .= 0) {
						$run_result .= "</ul>";
					}
					$lastyear = $year;
					$run_result .= "<h2 class=\"weblogdateheader\">$year</h2>";
					$run_result .= "<ul>";
				}
				
	// Print a link
	
				$run_result .= "<li>";
				$run_result .= "<a href=\"".url."$weblog_name/weblog/archive/$year/$month/\">";
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