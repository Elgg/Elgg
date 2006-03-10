<?php

	global $page_owner;
	
	if ($page_owner != -1) {
		if (run("users:type:get", $page_owner) == "person") {
			$result = db_query("select users.ident, users.username, users.name from users
										where users.owner = $page_owner
										and users.user_type = 'community'");
			if (sizeof($result) > 0) {
				$body = "<ul>";
				foreach($result as $row) {
					$body .= "<li><a href=\"" . url . stripslashes($row->username) . "/\">" . stripslashes($row->name) . "</a></li>";
				}
				$body .= "</ul>";
				// $run_result .= $body;
				$run_result .= "<li id=\"community_owned\">";
				$run_result .= run("templates:draw", array(
						'context' => 'sidebarholder',
						'title' => gettext("Owned communities"),
						'body' => $body
					)
					);
				$run_result .= "</li>";
			} else {
				$run_result .= "";
			}
		}
	}
	
?>