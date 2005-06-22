<?php

	global $page_owner;
	
	if ($page_owner != -1) {
		if (run("users:type:get", $page_owner) == "person") {
			$result = db_query("select users.ident from users
										where users.owner = $page_owner
										and users.user_type = 'community'
										limit 8");
			$friends = array();
			if (sizeof($result) > 0) {
				foreach($result as $row) {
					$friends[] = (int) $row->ident;
				}
				$run_result .= "<div class=\"box_moderator_of\">";
				$run_result .= run("users:infobox",
											array(
													"Moderator Of",
													$friends,
													"<a href=\"".url."_communities/owned.php?owner=$profile_id\">Owned Communities</a>"
													)
							);			
				$run_result .= "</div>";
			}
		}
	}
	
?>