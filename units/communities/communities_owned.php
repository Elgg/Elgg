<?php

	global $page_owner;
	
	if ($page_owner != -1) {
		if (run("users:type:get", $page_owner) == "person") {
			$result = db_query("select users.ident, users.username, users.name from users
										where users.owner = $page_owner
										and users.user_type = 'community'");
			if (sizeof($result) > 0) {
				$body = "<p>";
				foreach($result as $row) {
					$body .= "<a href=\"" . url . stripslashes($row->username) . "/\">" . stripslashes($row->name) . "</a><br />";
				}
				$body .= "</p>";
				// $run_result .= $body;
				$run_result .= run("templates:draw", array(
						'context' => 'contentholder',
						'title' => gettext("Owned communities"),
						'body' => $body,
						'submenu' => ''
					)
					);
			} else {
				$run_result .= "";
			}
		}
	}
	
?>