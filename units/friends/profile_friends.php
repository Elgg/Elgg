<?php

	global $page_owner;
	
	if ($page_owner != -1) {
		$result = db_query("select users.ident from friends
									left join users on users.ident = friends.friend
									where friends.owner = $page_owner
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
														"Friends",
														$friends,
														"<a href=\"/_friends/?owner=$profile_id\">Friends Screen</a>
														 (<a href=\"/_friends/foaf.php?owner=$profile_id\">FOAF</a>)"
														)
								);
			
		} else {
			$run_result .= run("users:infobox",
												array(
														"Your Friends",
														$friends,
														"<a href=\"/".$_SESSION['username']."/friends/\">Friends Screen</a>
														 (<a href=\"/".$_SESSION['username']."/foaf/\">FOAF</a>)"
													)
								);
		}
		$run_result .= "</div>";
			
	}

?>