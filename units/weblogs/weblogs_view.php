<?php

	// View a weblog
	
	// Get the current profile ID
	
		global $profile_id;
		
	// If the weblog offset hasn't been set, it's 0
		if (!isset($_REQUEST['weblog_offset'])) {
			$weblog_offset = 0;
		} else {
			$weblog_offset = $_REQUEST['weblog_offset'];
		}
		$weblog_offset = (int) $weblog_offset;

		$url = url;
		
		
	// Get all posts in the system that we can see
	
		$where = run("users:access_level_sql_where",$_SESSION['userid']);
		$posts = db_query("select * from weblog_posts where ($where) and weblog = $profile_id order by posted desc limit $weblog_offset,25");
		$numberofposts = db_query("select count(*) as numberofposts from weblog_posts where ($where) and weblog = $profile_id");
		$numberofposts = $numberofposts[0]->numberofposts;
				
		if (sizeof($posts > 0) || sizeof($friendsposts > 0)) {
			
			$lasttime = "";
			
			foreach($posts as $post) {
				
				$time = strftime("%B %d, %Y",$post->posted);
				if ($time != $lasttime) {
					$run_result .= "<h2 class=\"weblog_dateheader\">$time</h2>\n";
					$lasttime = $time;
				}
				
				$run_result .= run("weblogs:posts:view",$post);
				
			}
			
			$weblog_name = htmlentities(stripslashes($_REQUEST['weblog_name']));
			
			if ($numberofposts - ($weblog_offset + 25) > 0) {
				$display_weblog_offset = $weblog_offset + 25;
				$back = gettext("Back");
				$run_result .= <<< END
				
				<a href="{$url}{$weblog_name}/weblog/skip={$display_weblog_offset}">&lt;&lt; $back</a>
				
END;
			}
			if ($weblog_offset > 0) {
				$display_weblog_offset = $weblog_offset - 25;
				if ($display_weblog_offset < 0) {
					$display_weblog_offset = 0;
				}
				$next = gettext("Next");
				$run_result .= <<< END
				
				<a href="{$url}{$weblog_name}/weblog/skip={$display_weblog_offset}">$next &gt;&gt;</a>
				
END;
			}
			
		}

?>