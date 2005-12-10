<?php

	//	ELGG recent activity page

	// Run includes
		require("../includes.php");
		
	// Initialise functions for user details, icon management and profile management
		run("profile:init");

	// Whose friends are we looking at?
		global $page_owner;
		
	// Weblog context
		define("context", "weblog");
		
	// You must be logged on to view this!
		protect(1);
		
		$title = run("profile:display:name") . " :: " . gettext("Recent activity");

	// If we haven't specified a start time, start time = 1 day ago
	
		if (!isset($_REQUEST['starttime'])) {
			$starttime = time() - 86400;
		} else {
			$starttime = (int) $_REQUEST['starttime'];
		}
		
		$body = "<p>" . gettext("Currently viewing recent activity since ") . gmdate("F d, Y",$starttime) . ".</p>";
		
		$body .= "<p>" . gettext("You may view recent activity during the following time-frames:") . "</p>";
		
		$body .= "<ul><li><a href=\"index.php?starttime=" . (time() - 86400) . "\">" . gettext("The last 24 hours") . "</a></li>";
		$body .= "<li><a href=\"index.php?starttime=" . (time() - (86400 * 2)) . "\">" . gettext("The last 48 hours") . "</a></li>";
		$body .= "<li><a href=\"index.php?starttime=" . (time() - (86400 * 7)) . "\">" . gettext("The last week") . "</a></li>";
		$body .= "<li><a href=\"index.php?starttime=" . (time() - (86400 * 30)) . "\">" . gettext("The last month") . "</a></li></ul>";
				
		$activities = db_query("select users.username, weblog_comments.*, weblog_posts.ident as weblogpost, weblog_posts.title as weblogtitle, weblog_posts.weblog as weblog from weblog_comments left join weblog_posts on weblog_posts.ident = weblog_comments.post_id left join users on users.ident = weblog_posts.weblog where weblog_comments.posted >= $starttime and weblog_posts.owner = $page_owner order by weblog_comments.posted desc");
		if (sizeof($activities) > 0) {
			foreach($activities as $activity) {
				$commentbody = stripslashes($activity->body);
				$commentbody .= "<br /><br /><a href=\"" . url . $activity->username . "/weblog/" . $activity->weblogpost . ".html\">" . gettext("Read more") . "</a>";
				$commentposter = sprintf(gettext("<b>$s</b> posted on weblog post:"),stripslashes($activity->postedname)) . " " . stripslashes($activity->weblogtitle);
				$body .= run("templates:draw", array(
									'context' => 'databox1',
									'name' => $commentposter,
									'column1' => $commentbody
								)
								);
			}
		} else {
			$body .= "<p>" . gettext("No activity during this time period.") . "</p>";
		}
		
		$body = run("templates:draw", array(
						'context' => 'contentholder',
						'title' => $title,
						'body' => $body
					)
					);
		
		echo run("templates:draw:page", array(
					$title, $body
				)
				);

?>