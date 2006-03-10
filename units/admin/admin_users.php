<?php

	// List of users in the system
	
	if (logged_on && run("users:flags:get", array("admin", $_SESSION['userid']))) {
	
	
	// We're only displaying 50 users a page, so if this variable exists it will serve as the offset
		if (isset($_REQUEST['offset'])) {
			$offset = (int) $_REQUEST['offset'];
		} else {
			$offset = 0;
		}
	
		$run_result .= "<p>" . gettext("The following is a list of all the users in the system, 50 users at a time. You can click each one to edit their user details as if you were logged in as them, as well as set user flags (including 'ban user' and 'set user as administrator').") . "</p>";
		$run_result .= "<p>" . gettext("If you know the username of the user you would like to edit, you can also enter it below.") . "</p>";
		
		$run_result .= "<form action=\"". url . "_userdetails/\" method=\"get\">";
		$run_result .= run("templates:draw", array(
					'context' => 'adminTable',
					'name' => "<h4>" . gettext("Enter username") ."</h4>",
					'column1' => "<input type=\"text\" name=\"profile_name\" value=\"\" /><input type=\"hidden\" name=\"context\" value=\"admin\" />",
					'column2' => "<input type=\"submit\" value=\"".gettext("Edit user") . "\" />"
				)
				);
		$run_result .= "</form>";
		
		$maxusers = db_query("select count(*) as maxusers from users where users.user_type = 'person'");
		$maxusers = $maxusers[0]->maxusers;
		
		$users = db_query("select users.* from users where users.user_type = 'person' order by username asc limit $offset, 50");
		
		if (sizeof($users) > 0) {
			$run_result .= run("templates:draw", array(
					'context' => 'adminTable',
					'name' => "<h5>" . gettext("Username") . "</h5>",
					'column1' => "<h5>" . gettext("Full name") . "</h5>",
					'column2' => "<h5>" . gettext("Email address") . "</h5>"
				)
				);
			foreach($users as $user) {
				$run_result .= run("admin:users:panel",$user);
			}
			if ($maxusers > ($offset + 50)) {
				$next = "<a href=\"" . url . "_admin/users.php?offset=" . ($offset + 50) . "\">" . gettext("Next") . "</a>";
			} else {
				$next = "";
			}
			$prevoffset = $offset - 50;
			if ($prevoffset < 0) {
				$prevoffset = 0;
			}
			if ($prevoffset != $offset) {
				$prev = "<a href=\"" . url . "_admin/users.php?offset=" . ($prevoffset) . "\">" . gettext("Previous") . "</a>";
			} else {
				$prev = "";
			}
			
			$run_result .= run("templates:draw", array(
						'context' => 'adminTable',
						'name' => "&nbsp;",
						'column1' => $prev . "&nbsp" . $next,
						'column2' => "&nbsp;"
					)
					);
			
		}
	}

?>