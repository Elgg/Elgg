<?php

	global $page_owner;
		
	if (logged_on && $page_owner != $_SESSION['userid'] && isset($parameter)) {

		$page_url = $_SERVER['REQUEST_URI'];
	
		$run_result .= "<p>&nbsp;</p>";
		$run_result .= "<form action=\"\" method=\"post\" >";
		
		$interesting = db_query("select count(*) as interesting from weblog_watchlist where weblog_post = $parameter and owner = " . $_SESSION['userid']);
		$interesting = $interesting[0]->interesting;
		
		if ($interesting) {
			$name = gettext("Stop keeping track of this post");
			$column1 = "<p>" . gettext("You have marked this post as interesting; all comments will appear on your 'recent activity' page. If you would like to remove this flag, click here.") . "</p>";
			$column2 = "<input type=\"submit\" value=\"" . gettext("Remove interesting flag") . "\" /><input type=\"hidden\" name=\"action\" value=\"weblog:interesting:off\" /><input type=\"hidden\" name=\"weblog_post\" value=\"$parameter\" />";
		} else {
			$name = gettext("Keep track of this post");
			$column1 = "<p>" . gettext("Click the 'Mark interesting' button to monitor new comments on your 'recent activity' page.") . "</p>";
			$column2 = "<input type=\"submit\" value=\"" . gettext("Mark interesting") . "\" /><input type=\"hidden\" name=\"action\" value=\"weblog:interesting:on\" /><input type=\"hidden\" name=\"weblog_post\" value=\"$parameter\" />";
		}
		
		$run_result .= run("templates:draw", array(
							'context' => 'flagContent',
							'name' => "<h5>" . $name . "</h5>",
							'column1' => $column1,
							'column2' => $column2
						)
						);
		$run_result .= "</form>";
		
	}

?>