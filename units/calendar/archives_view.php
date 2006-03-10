<?php

	// Get the current profile ID
	
	global $profile_id;
	global $calendar_id;
	$url = url;
	
	// Obtain the separate archive pages from the database
	
	$archives = db_query("SELECT DISTINCT 
								EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(date_start)) as archivestamp
								FROM `event` 
								WHERE owner = " . $calendar_id . " " .
								"order by date_start desc");
	
	// If there are any archives ...
	$archive = gettext("Event Archive"); // gettext variable
	
	if (sizeof($archives) > 0) {
		
		$run_result .= "<h1 class=\"weblogdateheader\">$archive</h1>";
		
	// Get the name of the weblog user
		
		$username = htmlentities(stripslashes($_REQUEST['username']));
		
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
			$run_result .= "<a href=\"" . $url . $username . "/calendar/archive/$year/$month/\">";
			$run_result .= date("F",gmmktime(0,0,0,$month+1,1,$year)) . " " . $year;
			$run_result .= "</a>";
			$run_result .= "</li>";
			
		}
		
		$run_result .= "</ul>";
		
	// If there are no posts to archive, say so!
		
	} else {
		$noBlogs = gettext("There are no weblog posts to archive as yet."); // gettext variable - NOT SURE ABOUT THIS POSITION
		$run_result .= "<p>There are no weblog posts to archive as yet.</p>";
		
	}
	
?>