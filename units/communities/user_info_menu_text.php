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
					$run_result = "<a href=\"".url."_communities/index.php?friends_name=".$_SESSION['username']."&action=friend&friend_id=$user_id\" onClick=\"return confirm('". gettext("Are you sure you want to join this community?") ."')\">" . gettext("Click here to join this community."). "</a>";
				} else {
					$run_result = "<a href=\"".url."_communities/index.php?friends_name=".$_SESSION['username']."&action=unfriend&friend_id=$user_id\" onClick=\"return confirm('". gettext("Are you sure you want to leave this community?") ."')\">" . gettext("Click here to leave this community."). "</a>";
				}
			}
		}

?>