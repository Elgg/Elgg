<?php

	// Main admin panel screen
	
	// Site stats
	
	if (logged_on && run("users:flags:get", array("admin", $_SESSION['userid']))) {
	
		$run_result .= "<h5>" . gettext("Site statistics") . "</h5>";
		
		// Number of users of each type
		$users = db_query("select user_type, count(ident) as numusers from users group by user_type");
		if (sizeof($users) > 0) {
			foreach($users as $user) {
				
				$run_result .= run("templates:draw", array(
					'context' => 'databox',
					'name' => sprintf(gettext("Accounts of type '%s'"), $user->user_type),
					'column1' => $user->numusers,
					'column2' => "&nbsp;"
				)
				);
				
			}
		}
		
		// Number of weblog posts
		$weblog_posts = db_query("select count(ident) as numposts from weblog_posts");
		$weblog_comments = db_query("select count(ident) as numposts from weblog_comments");
		$weblog_posts_7days = db_query("select count(ident) as numposts from weblog_posts where posted > " . (time() - (86400 * 7)));
		$weblog_comments_7days = db_query("select count(ident) as numposts from weblog_comments where posted > " . (time() - (86400 * 7)));
		$run_result .= run("templates:draw", array(
					'context' => 'databox',
					'name' => gettext("Weblog statistics"),
					'column1' => "<b>" . gettext("All-time:") . "</b> " . sprintf(gettext("%d weblog posts, %d comments"),$weblog_posts[0]->numposts, $weblog_comments[0]->numposts) . "<br /><b>" . gettext("Last 7 days:") . "</b> " . sprintf(gettext("%d weblog posts, %d comments"),$weblog_posts_7days[0]->numposts, $weblog_comments_7days[0]->numposts),
					'column2' => "&nbsp;"
				)
				);
				
		// Number of files
		$files = db_query("select count(ident) as numfiles, sum(size) as totalsize from files");
		$files_7days = db_query("select count(ident) as numfiles, sum(size) as totalsize from files where time_uploaded > " . (time() - (86400 * 7)));
		$run_result .= run("templates:draw", array(
					'context' => 'databox',
					'name' => gettext("File statistics"),
					'column1' => "<b>" . gettext("All-time:") . "</b> " . sprintf(gettext("%d files (%d bytes)"),$files[0]->numfiles, $files[0]->totalsize) . "<br /><b>" . gettext("Last 7 days:") . "</b> " . sprintf(gettext("%d files (%d bytes)"),$files_7days[0]->numfiles, $files_7days[0]->totalsize),
					'column2' => "&nbsp;"
				)
				);
	
	// Users online right now
	
		$run_result .= "<h5>" . gettext("Users online now") . "</h5>";
		$run_result .= "<p>" . gettext("The following users have an active session and have performed an action within the past 10 minutes.") . "</p>";
		
		$users = db_query("select * from users where code != '' and last_action > " . (time() - 600). " order by username asc");
		if (sizeof($users > 0)) {
			$run_result .= run("templates:draw", array(
					'context' => 'databox',
					'name' => "<b>" . gettext("Username") . "</b>",
					'column1' => "<b>" . gettext("Full name") . "</b>",
					'column2' => "<b>" . gettext("Email address") . "</b>"
				)
				);
			foreach($users as $user) {
				$run_result .= run("admin:users:panel",$user);
			}
		}
		
		$run_result .= "<p>" . sprintf(gettext("%d users in total."),sizeof($users)) . "</p>";
		
	}

?>