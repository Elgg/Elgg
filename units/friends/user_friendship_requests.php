<?php

	// Lists friendship requests from other users

	if (logged_on) {
		
		global $page_owner;
		
		if (run("users:type:get", $page_owner) == "person" && run("permissions:check",array("userdetails:change", $page_owner))) {

			$title = run("profile:display:name") . " :: ". gettext("Friendship requests") ."";
			
			$pending_requests = db_query("select friends_requests.ident as request_id, users.*, icons.filename from friends_requests left join users on users.ident = friends_requests.owner left join icons on icons.ident = users.icon where friends_requests.friend = $page_owner order by users.name asc");
			if (sizeof($pending_requests) > 0) {
			
				$body .= "<p>" . gettext("The following users would like to add you as a friend. They need your approval to do this (to change this setting, visit the 'account settings' page).") . "</p>";
					
				foreach($pending_requests as $pending_user) {
					
					$where = run("users:access_level_sql_where",$_SESSION['userid']);
					$description = db_query("select * from profile_data where ($where) and name = 'minibio' and owner = " . $pending_user->ident);
					if (sizeof($description) > 0) {
						$description = "<p>" . stripslashes($description[0]->value) . "</p>";
					} else {
						$description = "<p>&nbsp;</p>";
					}

					$icon = $pending_user->filename;
					
					$request_id = $pending_user->request_id;
					
					$col1 = "<p><b>" . stripslashes($pending_user->name) . "</b></p>" . $description;
					$col1 .= "<p>";
					$col1 .= "<a href=\"" . url . $pending_user->username . "/\">" . gettext("Profile") . "</a> | ";
					$col1 .= "<a href=\"" . url . $pending_user->username . "/weblog/\">" . gettext("Blog") . "</a></p>";
					$col2 = "<p><a href=\"" .url. "_friends/requests.php?action=friends:approve:request&request_id=$request_id\">Approve</a> | <a href=\"" .url. "_friends/requests.php?action=friends:decline:request&request_id=$request_id\">Decline</a></p>";
					$ident = $pending_user->ident;

					$body .= run("templates:draw", array(
														'context' => 'adminTable',
														'name' => "<img src=\"" . url . "_icons/data/" . $icon . "\" />",
														'column1' => $col1,
														'column2' => $col2
													)
													);
					
				}
				
			} else {
				$body .= "<p>" . gettext("You have no pending friendship requests.") . "</p>";
			}
			
			$run_result = run("templates:draw", array(
						'context' => 'contentholder',
						'title' => $title,
						'body' => $body
					)
					);
		
		}
		
	}

?>