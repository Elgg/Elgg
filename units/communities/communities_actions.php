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
													$messages[] = "Error! The community username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.";
												} else if ($_REQUEST['comm_name'] == "") {
													$messages[] = "Error! The community name cannot be blank.";
												} else {
													$username = strtolower(addslashes($_REQUEST['comm_username']));
													$usernametaken = db_query("select count(ident) as taken from users where username = '$username'");
													$usernametaken = $usernametaken[0]->taken;
													if ($usernametaken > 0) {
														$messages[] = "The username '$username' is already taken by another user. You will need to pick a different one.";
													} else {
														$name = addslashes($_REQUEST['comm_name']);
														db_query("insert into users set name = '$name', username = '$username', user_type = 'community', owner = " . $_SESSION['userid']);
														$ident = db_id();
														db_query("insert into friends set owner = ". $_SESSION['userid'] .", friend = $ident");
														$messages[] = "Your community was created and you were added as its first member.";
													}
												}
												
											}
				
				// Friend someone
				case "friend":			if (isset($_REQUEST['friend_id']) && logged_on) {
											if (run("users:type:get", $friend_id) == "community") {
												$messages[] = "You joined " . $friend[0]->name . ".";
											}
										}
										break;
				// Unfriend someone
				case "unfriend":		if (isset($_REQUEST['friend_id']) && logged_on) {
											if (run("users:type:get", $friend_id) == "community") {
												$messages[] = "You left " . $friend[0]->name . ".";
											}
										}
										break;
				case "weblogs:post:add":
											if (run("users:type:get",$page_owner) == "community") {
												$messages[] = "Your post has been added to the community weblog.";
											}
										break;
				
			}
			
		}

?>