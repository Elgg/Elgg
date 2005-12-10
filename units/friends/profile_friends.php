<?php

	global $page_owner;
	
	if ($page_owner != -1 && run("users:type:get", $page_owner) == "person") {
		$result = db_query("select users.ident from friends
									left join users on users.ident = friends.friend
									where friends.owner = $page_owner
									and users.user_type = 'person'
									group by friends.friend
									limit 8");
			
		$friends = array();
		if (sizeof($result) > 0) {
			foreach($result as $row) {
				$friends[] = (int) $row->ident;
			}
		}
		$run_result .= "<div class=\"box_friends\">";
		if ($page_owner != $_SESSION['userid']) {
			$run_result .= run("users:infobox",
												array(
														gettext("Friends"),
														$friends,
														"<a href=\"".url."_friends/?owner=$profile_id\">" . gettext("Friends Screen") . "</a> (<a href=\"".url."_friends/foaf.php?owner=$profile_id\">FOAF</a>)"
														)
								);
			
		} else {
			$run_result .= run("users:infobox",
												array(
														gettext("Your Friends"),
														$friends,
														"<a href=\"".url.$_SESSION['username']."/friends/\">" . gettext("Friends Screen") . "</a> (<a href=\"".url.$_SESSION['username']."/foaf/\">FOAF</a>)"
													)
								);
		}
		$run_result .= "</div>";
			
	}

?>