<?php

	global $page_owner;
	
	if ($page_owner != -1) {
		if (run("users:type:get", $page_owner) == "person") {
			$result = db_query("select users.ident, users.username, users.name from friends
										join users on users.ident = friends.friend
										where friends.owner = $page_owner
										and users.user_type = 'community'
										and users.owner != " . $page_owner);
				
			if (sizeof($result) > 0) {
				$body = "<ul>";
				foreach($result as $row) {
					$body .= "<li><a href=\"" . url . stripslashes($row->username) . "/\">" . stripslashes($row->name) . "</a></li>";
				}
				$body .= "</ul>";
				$run_result .= "<li id=\"community_membership\">";
				$run_result .= run("templates:draw", array(
						'context' => 'sidebarholder',
						'title' => gettext("Community memberships"),
						'body' => $body
					)
					);
				$run_result .= "</li>";
			} else {
				$run_result .= "";
			}
		} else if (run("users:type:get", $page_owner) == "community") {
			$result = db_query("select users.ident from friends
										join users on users.ident = friends.owner
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