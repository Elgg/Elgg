<?php
	
	if (logged_on) {
		$result = db_query("select users.ident from friends
									left join users on users.ident = friends.friend
									where owner = ".$_SESSION['userid']."
									limit 8");
	
			
		$friends = array();
		if (sizeof($result) > 0) {
			foreach($result as $row) {
				$friends[] = $row->ident;
			}
		}
		run("users:infobox",array("Your Friends",array($friends),"<a href=\"friends/\">Friends Screen</a>"));
			
	}

?>