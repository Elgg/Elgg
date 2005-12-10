<?php

	global $page_owner;
	
	if ($page_owner != -1) {
		if (run("users:type:get", $page_owner) == "person") {
			$result = db_query("select users.ident, users.username, users.name from friends
										left join users on users.ident = friends.friend
										where friends.owner = $page_owner
										and users.user_type = 'community'
										and users.owner != $page_owner
										group by friends.friend");
				
			if (sizeof($result) > 0) {
				$body = "<p>";
				foreach($result as $row) {
					$body .= "<a href=\"" . url . stripslashes($row->username) . "/\">" . stripslashes($row->name) . "</a><br />";
				}
				$body .= "</p>";
				$run_result .= run("templates:draw", array(
						'context' => 'contentholder',
						'title' => gettext("Community memberships"),
						'body' => $body,
						'submenu' => ''
					)
					);
			} else {
				$run_result .= "";
			}
		} else if (run("users:type:get", $page_owner) == "community") {
			$result = db_query("select users.ident from friends
										left join users on users.ident = friends.owner
										where friends.friend = $page_owner
										group by friends.owner
										limit 8");
			$friends = array();
			if (sizeof($result) > 0) {
				foreach($result as $row) {
					$friends[] = (int) $row->ident;
				}
			}
			$run_result .= run("users:infobox",
										array(
												gettext("Members"),
												$friends,
												"<a href=\"".url."_communities/members.php?owner=$profile_id\">" . gettext("Members") . "</a>"
												)
						);
		}
	}
	
?>