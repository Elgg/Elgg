<?php

	global $page_owner;
	
	if ($page_owner != -1) {
		if (run("users:type:get", $page_owner) == "person") {
			$result = db_query("select users.ident from friends
										left join users on users.ident = friends.friend
										where friends.owner = $page_owner
										and users.user_type = 'community'
										group by friends.friend
										limit 8");
				
			$friends = array();
			if (sizeof($result) > 0) {
				foreach($result as $row) {
					$friends[] = (int) $row->ident;
				}
			}
			$run_result .= "<div class=\"box_communities\">";
			$run_result .= run("users:infobox",
										array(
												"Member Of",
												$friends,
												"<a href=\"".url."_communities/?owner=$profile_id\">Communities</a>"
												)
						);			
			$run_result .= "</div>";
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
			$run_result .= "<div class=\"box_community_members\">";
			$run_result .= run("users:infobox",
										array(
												"Members",
												$friends,
												"<a href=\"".url."_communities/members.php?owner=$profile_id\">Members</a>"
												)
						);			
			$run_result .= "</div>";
		}
	}
	
?>