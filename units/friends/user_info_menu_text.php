<?php

	global $user_type;

	// If we've been passed a valid user ID as a parameter ...
		if (isset($parameter) && (isset($parameter[0])) && ($parameter[0] != $_SESSION['userid']) && logged_on) {
			
			$user_id = (int) $parameter[0];
			
			if (run("users:type:get", $user_id) == "person") {
				$result = db_query("select count(*) as friend from friends 
									join users on users.ident = friends.friend
									where friends.owner = " . $_SESSION['userid'] . "
									  and friends.friend = $user_id");
				$result = $result[0]->friend;
				if ($result == 0) {
					$moderation = db_query("select moderation from users where ident = $user_id");
					$moderation = $moderation[0]->moderation;
					switch($moderation) {
						case "no":
						case "yes":		$run_result = "<a href=\"".url."_friends/index.php?friends_name=".$_SESSION['username']."&amp;action=friend&amp;friend_id=$user_id\" onClick=\"return confirm('". gettext("Are you sure you want to add this user as a friend?") ."')\">" . gettext("Click here to add this user as a friend."). "</a>";
										break;
						case "priv":	$run_result = "";
										break;
					}
				} else {
					$run_result = "<a href=\"".url."_friends/index.php?friends_name=".$_SESSION['username']."&amp;action=unfriend&amp;friend_id=$user_id\" onClick=\"return confirm('". gettext("Are you sure you want to remove this user from your friends list?") ."')\">" . gettext("Click here to remove this user from your friends list."). "</a>";
				}
			}
		}

?>