<?php

	// If we've been passed a valid user ID as a parameter ...
		if (isset($parameter) && (isset($parameter[0])) && ($parameter[0] != $_SESSION['userid']) && logged_on) {
			
			$user_id = (int) $parameter[0];
			$result = db_query("select count(ident) as friend from friends 
								where owner = " . $_SESSION['userid'] . "
								  and friend = $user_id");
			$result = (int) $result[0]->friend;
			if ($result == 0) {
				$run_result = "
					<form action=\"/".$_SESSION['username']."/friends/\" method=\"post\" style=\"display: inline\">
						<input type=\"hidden\" name=\"action\" value=\"friend\" />
						<input type=\"hidden\" name=\"friend_id\" value=\"$user_id\" />
						<input type=\"image\" src=\"/_friends/gfx/friend.gif\" border=\"0\" width=\"17\" height=\"18\" alt=\"Friend\" title=\"Friend\" onClick=\"return confirm('Are you sure you want to add this user to your friends list?')\" />
					</form>";
			} else {
				$run_result = "
					<form action=\"/".$_SESSION['username']."/friends/\" method=\"post\" style=\"display: inline\">
						<input type=\"hidden\" name=\"action\" value=\"unfriend\" />
						<input type=\"hidden\" name=\"friend_id\" value=\"$user_id\" />
						<input type=\"image\" src=\"/_friends/gfx/unfriend.gif\" border=\"0\" width=\"17\" height=\"18\" alt=\"Un-Friend\" title=\"Un-Friend\" onClick=\"return confirm('Are you sure you want to remove this user from your friends list?')\"/>
					</form>";
			}
			
		}

?>