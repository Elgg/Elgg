<?php

	// View a event's for a particular month
	
	// Get the current profile ID
	
		global $profile_id;
		global $calendar_id;
		
	// If the months haven't been set, they're the current months
		if (!isset($_REQUEST['month'])) {
			$month = gmdate("m");
		} else {
			$month = (int) $_REQUEST['month'];
		}
		
	// If the years haven't been set, they're the current years
		if (!isset($_REQUEST['year'])) {
			$year = gmdate("Y");
		} else {
			$year = (int) $_REQUEST['year'];
		}
		
	// Get all posts in the system that we can see
		
		$where = run("users:access_level_sql_where",$_SESSION['userid']);
		$events = db_query("select * from event 
							where ($where) 
							and owner = " . $calendar_id . " 
							and date_start >= ". mktime(0,0,0,$month,1,$year)."
							and date_start < ". mktime(23,59,59,($month + 1), 0, $year) ."
							order by date_start asc");
		
		if (sizeof($events > 0)) {
			$lasttime = "";
			
			$run_result .= "<h1 class=\"weblogdateheader\">" . gmdate("F Y",gmmktime(0,0,0,$month,1,$year)) . "</h1>\n";
			
			foreach($events as $event) {
				
				$time = gmdate("F d, Y",$event->date_start);
				if ($time != $lasttime) {
					$run_result .= "<h2 class=\"weblogdateheader\">$time</h2>\n";
					$lasttime = $time;
				}
				
				$run_result .= run("calendar:events:view",$event);
				
			}
			
		}

?>