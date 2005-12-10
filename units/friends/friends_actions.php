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
												if (sizeof($friendalready) == 0) {
													db_query("insert into friends 
																set owner = 	" . $_SESSION['userid'] . ",
																    friend = 	$friend_id");
													if (run("users:type:get", $friend_id) == "person") {
														$messages[] = $friend[0]->name . gettext(" was added to your friends list.");
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
				
			}
			
		}

?>