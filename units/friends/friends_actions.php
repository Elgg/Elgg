<?php

	// Actions to perform on the friends screen
	
		if (isset($_REQUEST['action'])) {
			switch($_REQUEST['action']) {
				
				// Friend someone
				case "friend":			if (isset($_REQUEST['friend_id']) && logged_on) {
											$friend_id = (int) $_REQUEST['friend_id'];
											$friend = db_query("select * from users where ident = $friend_id");
											if (sizeof($friend) > 0) {
												$friendalready = db_query("select * from friends 
																					where owner = " . $_SESSION['userid'] . "
																					and friend = $friend_id");
												$requestedalready = db_query("select * from friends_requests
																					where owner = " . $_SESSION['userid'] . "
																					and friend = $friend_id");
												if (sizeof($friendalready) == 0 && sizeof($requestedalready) == 0) {
													if ($friend[0]->moderation == "no") {
														db_query("insert into friends 
																	set owner = 	" . $_SESSION['userid'] . ",
																		friend = 	$friend_id");
														if (run("users:type:get", $friend_id) == "person") {
															$messages[] = sprintf(gettext("%s was added to your friends list."),$friend[0]->name);
														}
													} else if ($friend[0]->moderation == "yes") {
														db_query("insert into friends_requests
																	set owner = 	" . $_SESSION['userid'] . ",
																		friend = 	$friend_id");
														if (run("users:type:get", $friend_id) == "person") {
															$messages[] = sprintf(gettext("%s has elected to moderate friendship requests. Your request has been added to their moderation queue."),$friend[0]->name);
														}
													} else if ($friend[0]->moderation == "priv" && run("users:type:get", $friend_id) == "person") {
														$messages[] = sprintf(gettext("%s has decided not to allow any new friendship requests at this time. Your friendship request has been declined."),$friend[0]->name);
													}
												}
											}
										}
										break;
				// Unfriend someone
				case "unfriend":		if (isset($_REQUEST['friend_id']) && logged_on) {
											$friend_id = (int) $_REQUEST['friend_id'];
											$friend = db_query("select * from users where ident = $friend_id");
											if (sizeof($friend) > 0) {
												db_query("delete from friends 
															where owner = 	" . $_SESSION['userid'] . "
															and friend = 	$friend_id");
												if (run("users:type:get", $friend_id) == "person") {
													$messages[] = $friend[0]->name . gettext(" was removed from your friends.");
												}
											}
										}
										break;
				// Approve a friendship request
				case "friends:approve:request":
										if (isset($_REQUEST['request_id']) && logged_on && run("users:type:get", $page_owner) == "person") {
											
											$request_id = (int) $_REQUEST['request_id'];
											$request = db_query("select users.name, friends_requests.owner, friends_requests.friend from friends_requests left join users on users.ident = friends_requests.owner where friends_requests.ident = $request_id");
											if (sizeof($request) > 0) {
												$request = $request[0];
												if (run("permissions:check",array("userdetails:change", $page_owner))) {
													db_query("delete from friends_requests where ident = $request_id");
													db_query("insert into friends set owner = " . $request->owner . ", friend = " . $request->friend);
													$messages[] = sprintf(gettext("You approved the friendship request. %s now lists you as a friend."),stripslashes($request->name));
												} else {
													$messages[] = gettext("Error: you do not have authority to modify this friendship request.");
												}
											} else {
												$messages[] = gettext("An error occurred: the friendship request could not be found.");
											}
											
										}
										break;
				// Reject a friendship request
				case "friends:decline:request":
										if (isset($_REQUEST['request_id']) && logged_on && run("users:type:get", $page_owner) == "person") {
											
											$request_id = (int) $_REQUEST['request_id'];
											$request = db_query("select users.name, friends_requests.owner, friends_requests.friend from friends_requests left join users on users.ident = friends_requests.owner where friends_requests.ident = $request_id");
											if (sizeof($request) > 0) {
												$request = $request[0];
												if (run("permissions:check",array("userdetails:change", $page_owner))) {
													db_query("delete from friends_requests where ident = $request_id");
													$messages[] = sprintf(gettext("You declined the friendship request. %s does not list you as a friend."),stripslashes($request->name));
												} else {
													$messages[] = gettext("Error: you do not have authority to modify this friendship request.");
												}
											} else {
												$messages[] = gettext("An error occurred: the friendship request could not be found.");
											}
											
										}
										break;
				
			}
			
		}

?>