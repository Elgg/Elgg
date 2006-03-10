<?php

global $page_owner;

// Actions to perform on the friends screen

if (isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		
		// Create a new community
		case "community:create":
			if (logged_on
				&& isset($_REQUEST['comm_name'])
				&& isset($_REQUEST['comm_username'])) {
					
					if (!preg_match("/^[A-Za-z0-9]{3,12}$/",$_REQUEST['comm_username'])) {
						$messages[] = gettext("Error! The community username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.");
					} else if (trim($_REQUEST['comm_name']) == "") {
						$messages[] = gettext("Error! The community name cannot be blank.");
					} else {
						$username = strtolower(trim($_REQUEST['comm_username']));
						$usernametaken = db_query("select count(*) as taken from users where username = '$username'");
						$usernametaken = $usernametaken[0]->taken;
						if ($usernametaken > 0) {
							$messages[] = sprintf(gettext("The username $s is already taken by another user. You will need to pick a different one."), stripslashes($username));
						} else {
							$name = trim($_REQUEST['comm_name']);
							db_query("insert into users set name = '$name', username = '$username', user_type = 'community', owner = " . $_SESSION['userid']);
							$ident = db_id();
							
							$rssresult = run("weblogs:rss:publish", array($ident, false));
							$rssresult = run("files:rss:publish", array($ident, false));
							$rssresult = run("profile:rss:publish", array($ident, false));
							
							db_query("insert into friends set owner = ". $_SESSION['userid'] .", friend = $ident");
							$messages[] = gettext("Your community was created and you were added as its first member.");
						}
					}
					
			}
			// There is deliberately not a break here - creating a community should automatically make you a member.
				
		// Friend someone
		case "friend":
			if (isset($_REQUEST['friend_id']) && logged_on) {
				
				$friend_id = (int) $_REQUEST['friend_id'];
				
				if (run("users:type:get", $friend_id) == "community") {
					$friend = db_query("select * from users where ident = $friend_id");
					$friend = $friend[0];
					if ($friend->moderation == "no") {
						$messages[] = sprintf(gettext("You joined %s."), stripslashes($friend->name));
					} else if ($friend->moderation == "yes") {
						$messages[] = sprintf(gettext("Membership of %s needs to be approved. Your request has been added to the list."), stripslashes($friend->name));
					} else if ($friend->moderation == "priv") {
						$messages[] = sprintf(gettext("%s is a private community. Your membership request has been declined."), stripslashes($friend->name));
					}
				}
			}
			break;
			
			
			
		// Unfriend someone
		case "unfriend":
			if (isset($_REQUEST['friend_id']) && logged_on) {
				if (run("users:type:get", $_REQUEST['friend_id']) == "community") {
					$communityname = run("users:id_to_name",$_REQUEST['friend_id']);
					$messages[] = sprintf(gettext("You left %s."), $communityname);
				}
			}
			break;
			
			
			
		case "weblogs:post:add":
			if (run("users:type:get",$page_owner) == "community") {
					$messages[] = gettext("Your post has been added to the community weblog.");
				}
			break;
			
		// Approve a membership request
				case "community:approve:request":
										if (isset($_REQUEST['request_id']) && logged_on && run("users:type:get", $page_owner) == "community") {
											
											$request_id = (int) $_REQUEST['request_id'];
											$request = db_query("select users.name, friends_requests.owner, friends_requests.friend from friends_requests left join users on users.ident = friends_requests.owner where friends_requests.ident = $request_id");
											if (sizeof($request) > 0) {
												$request = $request[0];
												if (run("permissions:check",array("userdetails:change", $page_owner))) {
													db_query("delete from friends_requests where ident = $request_id");
													db_query("insert into friends set owner = " . $request->owner . ", friend = " . $request->friend);
													$messages[] = sprintf(gettext("You approved the membership request. %s is now a member of this community."),stripslashes($request->name));
												} else {
													$messages[] = gettext("Error: you do not have authority to modify this membership request.");
												}
											} else {
												$messages[] = gettext("An error occurred: the membership request could not be found.");
											}
											
										}
										break;
				// Reject a membership request
				case "community:decline:request":
										if (isset($_REQUEST['request_id']) && logged_on && run("users:type:get", $page_owner) == "community") {
											
											$request_id = (int) $_REQUEST['request_id'];
											$request = db_query("select users.name, friends_requests.owner, friends_requests.friend from friends_requests left join users on users.ident = friends_requests.owner where friends_requests.ident = $request_id");
											if (sizeof($request) > 0) {
												$request = $request[0];
												if (run("permissions:check",array("userdetails:change", $page_owner))) {
													db_query("delete from friends_requests where ident = $request_id");
													$messages[] = sprintf(gettext("You declined the membership request. %s is not a member of this community."),stripslashes($request->name));
												} else {
													$messages[] = gettext("Error: you do not have authority to modify this membership request.");
												}
											} else {
												$messages[] = gettext("An error occurred: the membership request could not be found.");
											}
											
										}
										break;
				
			}
		
	}

?>