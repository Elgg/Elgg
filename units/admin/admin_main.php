<?php

	// Main admin panel screen
	
	// Site stats
	
	if (logged_on && run("users:flags:get", array("admin", $_SESSION['userid']))) {
	
		$run_result .= "<h2>" . gettext("Site statistics") . "</h2>";
		
		// Number of users of each type
		$users = db_query("select user_type, count(*) as numusers from users group by user_type");
		if (sizeof($users) > 0) {
			foreach($users as $user) {
				
				$run_result .= run("templates:draw", array(
					'context' => 'adminTable',
					'name' => "<h3>" . sprintf(gettext("Accounts of type '%s'"), $user->user_type) . "</h3> ",
					'column1' => "<p>" . $user->numusers . "</p> ",
					'column2' => "&nbsp;"
				)
				);
				
			}
		}
		
		// Number of weblog posts
		$weblog_posts = db_query("select count(*) as numposts from weblog_posts");
		$weblog_comments = db_query("select count(*) as numposts from weblog_comments");
		$weblog_posts_7days = db_query("select count(*) as numposts from weblog_posts where posted > (UNIX_TIMESTAMP() - (86400 * 7))");
		$weblog_comments_7days = db_query("select count(*) as numposts from weblog_comments where posted > (UNIX_TIMESTAMP() - (86400 * 7))");
		$run_result .= run("templates:draw", array(
					'context' => 'adminTable',
					'name' => "<h3>" . gettext("Weblog statistics") . "</h3> ",
					'column1' => "<h4>" . gettext("All-time:") . "</h4><p>" . sprintf(gettext("%d weblog posts, %d comments"),$weblog_posts[0]->numposts, $weblog_comments[0]->numposts) . "</p><h4>" . gettext("Last 7 days:") . "</h4><p>" . sprintf(gettext("%d weblog posts, %d comments"),$weblog_posts_7days[0]->numposts, $weblog_comments_7days[0]->numposts) . "</p>",
					'column2' => "&nbsp;"
				)
				);
				
		// Number of files
		$files = db_query("select count(*) as numfiles, sum(size) as totalsize from files");
		$files_7days = db_query("select count(*) as numfiles, sum(size) as totalsize from files where time_uploaded > (UNIX_TIMESTAMP() - (86400 * 7))");
		$run_result .= run("templates:draw", array(
					'context' => 'adminTable',
					'name' => "<h3>" . gettext("File statistics") . "</h3> ",
					'column1' => "<h4>" . gettext("All-time:") . "</h4> <p>" . sprintf(gettext("%d files (%d bytes)"),$files[0]->numfiles, $files[0]->totalsize) . "</p><h4>" . gettext("Last 7 days:") . "</h4><p>" . sprintf(gettext("%d files (%d bytes)"),$files_7days[0]->numfiles, $files_7days[0]->totalsize) . "</p>",
					'column2' => "&nbsp;"
				)
				);
	
		// DB size
		$totaldbsize = 0;
		$dbsize = db_query("show table status");
		foreach($dbsize as $atable) {
			$totaldbsize += intval($atable->Data_length) + intval($atable->Index_length);
		}
		$run_result .= run("templates:draw", array(
					'context' => 'adminTable',
					'name' => "<h3>" . gettext("Database statistics") . "</h3> ",
					'column1' => "<h4>" . gettext("Total database size:") . "</h4> <p>" . sprintf(gettext("%d bytes"),$totaldbsize) . "</p>",
					'column2' => "&nbsp;"
				)
				);
	
	// Users online right now
	
		$run_result .= "<h2>" . gettext("Users online now") . "</h2>";
		$run_result .= "<p>" . gettext("The following users have an active session and have performed an action within the past 10 minutes.") . "</p>";
		
		$users = db_query("select * from users where code != '' and last_action > (UNIX_TIMESTAMP() - 600) order by username asc");
		if (sizeof($users > 0)) {
			$run_result .= run("templates:draw", array(
					'context' => 'adminTable',
					'name' => "<h3>" . gettext("Username") . "</h3>",
					'column1' => "<h3>" . gettext("Full name") . "</h3>",
					'column2' => "<h3>" . gettext("Email address") . "</h3>"
				)
				);
			foreach($users as $user) {
				$run_result .= run("admin:users:panel",$user);
			}
		}
		
		$run_result .= "<p>" . sprintf(gettext("%d users in total."),sizeof($users)) . "</p>";
		
	}

?>