<?php

	global $user_type;

	// If we've been passed a valid user ID as a parameter ...
		if (isset($parameter) && (isset($parameter[0])) && ($parameter[0] != $_SESSION['userid']) && logged_on) {
			
			$user_id = (int) $parameter[0];
			
			if (run("users:type:get", $user_id) == "community") {
				$result = db_query("select count(users.ident) as friend from friends 
									left join users on users.ident = friends.friend
									where friends.owner = " . $_SESSION['userid'] . "
									  and friends.friend = $user_id");
				$result = $result[0]->friend;
				if ($result == 0) {
					$run_result = "
						<form action=\"".url.$_SESSION['username']."/communities/\" method=\"post\" style=\"display: inline\">
							<input type=\"hidden\" name=\"action\" value=\"friend\" />
							<input type=\"hidden\" name=\"friend_id\" value=\"$user_id\" />
							<input type=\"image\" src=\"".url."_friends/gfx/friend.gif\" border=\"0\" width=\"17\" height=\"18\" alt=\"Join Community\" title=\"Join Community\" onClick=\"return confirm('". gettext("Are you sure you want to join this community?") ."')\" />
						</form>";
				} else {
					$run_result = "
						<form action=\"".url.$_SESSION['username']."/communities/\" method=\"post\" style=\"display: inline\">
							<input type=\"hidden\" name=\"action\" value=\"unfriend\" />
							<input type=\"hidden\" name=\"friend_id\" value=\"$user_id\" />
							<input type=\"image\" src=\"".url."_friends/gfx/unfriend.gif\" border=\"0\" width=\"17\" height=\"18\" alt=\"Leave Community\" title=\"Leave Community\" onClick=\"return confirm('". gettext("Are you sure you want to leave this community?") ."')\"/>
						</form>";
				}
			}
		}

?>