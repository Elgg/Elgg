<?php
	$friend_id = (int) $parameter[0];
	$friend_calendar_id = db_query("SELECT ident FROM calendar WHERE owner=" . $friend_id);
	
	$query = "SELECT DISTINCT * FROM event " .
		     "WHERE owner= " . $friend_calendar_id[0]->ident . " " .
		     "AND (access='PUBLIC' " .
		     "OR access='LOGGED_IN' ";
		     
	$groups = db_query("SELECT group_id FROM group_membership " .
					   "WHERE user_id=" . $_SESSION["userid"]);
	foreach ($groups as $group) {
		$query .= "OR access='group" . $group->group_id ."' ";
	}
	
	
	$query .= ") ORDER BY date_start DESC";
	
	$run_result = db_query($query);
?>
