<?php
	
	if (logged_on) {
		$result = db_query("select users.ident from friends
									join users on users.ident = friends.friend
									where owner = ".$_SESSION['userid']."
									and users.user_type = 'person'
									limit 8");
	
			
		$friends = array();
		if (sizeof($result) > 0) {
			foreach($result as $row) {
				$friends[] = $row->ident;
			}
		}
		run("users:infobox",array(".". gettext("Your Friends") ."",array($friends),"<a href=\"friends/\">". gettext("Friends Screen") ."</a>"));
			
	}

?>