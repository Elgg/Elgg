<?php

	// View a weblog's posts for a particular month
	
	// Get the current profile ID
	
		global $profile_id;
		
	// If the months haven't been set, they're the current months
		if (!isset($_REQUEST['month'])) {
			$month = (int) gmdate("m");
		} else {
			$month = (int) $_REQUEST['month'];
		}
		
	// If the years haven't been set, they're the current years
		if (!isset($_REQUEST['year'])) {
			$year = (int) gmdate("Y");
		} else {
			$year = (int) $_REQUEST['year'];
		}
		
	// Get all posts in the system that we can see
	
		$where = run("users:access_level_sql_where",$_SESSION['userid']);
		$posts = db_query("select * from weblog_posts 
							where ($where) 
							and owner = $profile_id 
							and posted >= ".gmmktime(0,0,0,$month,1,$year)."
							and posted < ".gmmktime(0,0,0,($month + 1), 1, $year)."
							order by posted asc");
				
		if (sizeof($posts > 0) || sizeof($friendsposts > 0)) {
			
			$lasttime = "";
			
			$run_result .= "<h1 class=\"weblogdateheader\">" . gmdate("F Y",gmmktime(0,0,0,$month,1,$year)) . "</h1>\n";
			
			foreach($posts as $post) {
				
				$time = gmdate("F d, Y",$post->posted);
				if ($time != $lasttime) {
					$run_result .= "<h2 class=\"weblogdateheader\">$time</h2>\n";
					$lasttime = $time;
				}
				
				$run_result .= run("weblogs:posts:view",$post);
				
			}
			
		}

?>